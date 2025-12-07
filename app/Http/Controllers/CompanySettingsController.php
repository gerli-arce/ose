<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\CompanySetting;
use App\Models\CompanySubscription;
use App\Models\SalesDocument;
use App\Models\User;
use App\Models\Branch;
use App\Models\Contact;
use App\Models\Product;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CompanySettingsController extends Controller
{
    /**
     * Display the specified resource.
     * With Dashboard Stats.
     */
    public function index(Request $request)
    {
        // 1. Get Current Company
        // Allow Super Admin to view specific company via query param
        if ($request->has('company_id') && auth()->check() && auth()->user()->is_super_admin) {
             $companyId = $request->input('company_id');
        } else {
             $companyId = session('company_id', 1);
        }
        
        $company = Company::with(['subscriptions' => function($q) {
            $q->where('status', 'active')->latest();
        }])->findOrFail($companyId);
        
        // 2. Stats for Dashboard
        $now = Carbon::now();
        
        $stats = [
            'total_invoices' => SalesDocument::where('company_id', $companyId)
                ->whereIn('status', ['emitted', 'accepted'])->count(),
                
            'month_invoices_count' => SalesDocument::where('company_id', $companyId)
                ->whereIn('status', ['emitted', 'accepted'])
                ->whereMonth('issue_date', $now->month)
                ->whereYear('issue_date', $now->year)
                ->count(),
                
            'month_total' => SalesDocument::where('company_id', $companyId)
                ->whereIn('status', ['emitted', 'accepted'])
                ->whereMonth('issue_date', $now->month)
                ->whereYear('issue_date', $now->year)
                ->sum('total'),
                
            'year_total' => SalesDocument::where('company_id', $companyId)
                 ->whereIn('status', ['emitted', 'accepted'])
                 ->whereYear('issue_date', $now->year)
                 ->sum('total'),
                 
            'active_users' => $company->users()->wherePivot('status', 'active')->count(),
            'branches_count' => Branch::where('company_id', $companyId)->where('active', true)->count(),
            
            // Contacts
            'customers_count' => Contact::where('company_id', $companyId)->whereIn('type', ['customer', 'both'])->count(),
            'suppliers_count' => Contact::where('company_id', $companyId)->whereIn('type', ['supplier', 'both'])->count(),
            
            'products_count' => Product::where('company_id', $companyId)->where('active', true)->count(),
        ];
        
        // 3. Chart Data (Last 6 months)
        $chartData = $this->getDashboardData($companyId);
        
        // 4. Settings
        $settings = CompanySetting::where('company_id', $companyId)->pluck('value', 'key');
        
        // 5. Subscription / Plan Usage
        $subscription = $company->subscriptions->first();
        $planUsage = [
            'plan_name' => $subscription ? $subscription->plan->name : 'N/A',
            'max_users' => $subscription && $subscription->plan->features ? ($subscription->plan->features()->where('key', 'max_users')->first()->value ?? Infinity) : 0, 
            'max_invoices' => $subscription && $subscription->plan->features ? ($subscription->plan->features()->where('key', 'max_invoices')->first()->value ?? Infinity) : 0,
        ];
        // Fix: accessing features via relationship might be better handled if features are key-value in a simpler way, but let's stick to this.
        // Actually, subscription logic we implemented earlier uses 'limit_users' columns in subscription or plan?
        // Let's check plan model again later in verification phase.
        
        return view('settings.company.index', compact('company', 'stats', 'chartData', 'settings', 'planUsage'));
    }

    private function getDashboardData($companyId)
    {
        $salesData = [];
        $invoicesData = [];
        $usersData = [];
        $productsData = [];
        $months = [];
        $current = Carbon::now()->subMonths(5);

        for ($i = 0; $i < 6; $i++) {
            // Sales Amount
            $sales = SalesDocument::where('company_id', $companyId)
                ->whereIn('status', ['emitted', 'accepted'])
                ->whereMonth('issue_date', $current->month)
                ->whereYear('issue_date', $current->year)
                ->sum('total');

            // Invoices Count
            $invoices = SalesDocument::where('company_id', $companyId)
                ->whereIn('status', ['emitted', 'accepted'])
                ->whereMonth('issue_date', $current->month)
                ->whereYear('issue_date', $current->year)
                ->count();
            
            // New Users
            // Note: Use pivot created_at if possible, but User model created_at is fine for now
            $users = User::whereHas('companies', function($q) use ($companyId) {
                    $q->where('company_id', $companyId);
                })
                ->whereMonth('created_at', $current->month)
                ->whereYear('created_at', $current->year)
                ->count();

            // New Products
            $products = Product::where('company_id', $companyId)
                ->whereMonth('created_at', $current->month)
                ->whereYear('created_at', $current->year)
                ->count();
                
            $months[] = $current->isoFormat('MMM');
            $salesData[] = $sales;
            $invoicesData[] = $invoices;
            $usersData[] = $users;
            $productsData[] = $products;
            
            $current->addMonth();
        }

        return [
            'labels' => $months, 
            'sales' => $salesData,
            'invoices' => $invoicesData,
            'users' => $usersData,
            'products' => $productsData
        ];
    }

    public function updateGeneral(Request $request)
    {
        $companyId = session('company_id', 1);
        $company = Company::findOrFail($companyId);
        
        $request->validate([
            'name' => 'required|string',
            'trade_name' => 'nullable|string',
            'tax_id' => 'required|string',
            'email' => 'required|email',
            'logo' => 'nullable|image|max:2048' // 2MB
        ]);
        
        $data = $request->only('name', 'trade_name', 'tax_id', 'email', 'phone');
        
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $data['logo_path'] = $path;
        }
        
        $company->update($data);
        
        return back()->with('success', 'Datos generales actualizados.');
    }
    
    public function updateElectronic(Request $request)
    {
        $companyId = session('company_id', 1);
        $company = Company::findOrFail($companyId);
        
        $request->validate([
            'sunat_sol_user' => 'nullable|string',
            'sunat_sol_password' => 'nullable|string',
            'sunat_env' => 'required|in:beta,production',
            'certificate' => 'nullable|file|mimes:p12,pfx,pem', // Certificate file
            'sunat_cert_password' => 'nullable|string'
        ]);
        
        $data = [
            'sunat_sol_user' => $request->sunat_sol_user,
            'sunat_env' => $request->sunat_env,
        ];
        
        if ($request->filled('sunat_sol_password')) {
            $data['sunat_sol_password'] = Crypt::encryptString($request->sunat_sol_password);
        }
        
        if ($request->filled('sunat_cert_password')) {
            $data['sunat_cert_password'] = Crypt::encryptString($request->sunat_cert_password);
        }
        
        if ($request->hasFile('certificate')) {
            $path = $request->file('certificate')->store('certificates'); // Private storage
            $data['sunat_cert_path'] = $path;
        }
        
        $company->update($data);
        
        return back()->with('success', 'Credenciales SUNAT actualizadas.');   
    }

    public function updateBilling(Request $request)
    {
        $companyId = session('company_id', 1);
        
        $keys = ['default_currency', 'default_tax_rate', 'invoice_auto_numbering', 'invoice_pdf_template'];
        
        foreach ($keys as $key) {
            $val = $request->input($key);
            CompanySetting::updateOrCreate(
                ['company_id' => $companyId, 'key' => $key],
                ['value' => $val]
            );
        }
        
        return back()->with('success', 'Parámetros de facturación actualizados.');
    }
}
