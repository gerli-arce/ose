<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanySetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    public function __construct()
    {
        // Enforce settings permission
        // $this->middleware('can:settings.company.view')->only('show');
        // $this->middleware('can:settings.company.edit')->only('update');
        // Temporarily commented out until permissions are fully propagated or if using super admin
    }

    public function show()
    {
        $companyId = session('current_company_id');
        if (!$companyId) {
            return redirect()->route('select.company');
        }

        $company = Company::findOrFail($companyId);
        
        // Cargar settings y convertirlos a key-value para acceso fácil
        $settings = $company->settings()->pluck('value', 'key')->toArray();

        // Defaults from JSON config if not in settings table
        $config = $company->config_json ?? [];

        return view('settings.company', compact('company', 'settings', 'config'));
    }

    public function update(Request $request)
    {
        $companyId = session('current_company_id');
        if (!$companyId) {
            return redirect()->route('select.company');
        }

        $company = Company::findOrFail($companyId);
        
        // Validación básica
        $request->validate([
            'name' => 'required|string|max:255',
            'trade_name' => 'nullable|string|max:255',
            'tax_id' => 'required|string|max:20',
            'email' => 'nullable|email',
            'logo' => 'nullable|image|max:2048', // 2MB
        ]);

        // 1. Actualizar Datos Básicos
        $company->update($request->only('name', 'trade_name', 'tax_id', 'email', 'phone', 'address'));
        
        // 2. Configuración JSON (Fiscal, etc)
        $config = $company->config_json ?? [];
        $fieldsToJson = ['fiscal_regime', 'igv_percent', 'default_currency', 'invoice_auto_numbering', 'electronic_env'];
        
        foreach($fieldsToJson as $field) {
            // Checkboxes might not be present if unchecked, handle booleans
            if ($field === 'invoice_auto_numbering') {
                $config[$field] = $request->has($field);
            } else {
                if ($request->filled($field)) {
                    $config[$field] = $request->input($field);
                }
            }
        }
        
        // 5. Archivos (Logo)
        if ($request->hasFile('logo')) {
            // Delete old logo if exists?
            
            $path = $request->file('logo')->store('company_logos', 'public');
            // Guardar path en config.
            $config['logo_path'] = 'storage/' . $path; // Relative public path
        }
        
        $company->config_json = $config;
        $company->save();

        // 4. Company Settings (Settings Table for sensitive or extra content)
        // $settingsFields = ['sunat_user', 'sunat_password'];
        // foreach($settingsFields as $key) {
        //     if ($request->filled($key)) {
        //         CompanySetting::updateOrCreate(
        //             ['company_id' => $company->id, 'key' => $key],
        //             ['value' => $request->input($key)]
        //         );
        //     }
        // }

        return redirect()->back()->with('success', 'Configuración actualizada correctamente.');
    }
}
