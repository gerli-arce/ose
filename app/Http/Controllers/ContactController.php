<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $companyId = session('current_company_id');
        if (!$companyId) return redirect()->route('select.company');

        $query = Contact::where('company_id', $companyId);

        // Filters
        if ($request->filled('type') && $request->type != 'all') {
            $query->where(function($q) use ($request) {
                $q->where('type', $request->type)
                  ->orWhere('type', 'both');
            });
        }
        
        if ($request->filled('status')) {
             if ($request->status == 'active') $query->where('active', true);
             if ($request->status == 'inactive') $query->where('active', false);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('business_name', 'like', "%{$search}%")
                  ->orWhere('tax_id', 'like', "%{$search}%");
            });
        }

        $contacts = $query->orderBy('name')->paginate(15);
        
        return view('contacts.index', compact('contacts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('contacts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $companyId = session('current_company_id');
        
        $request->validate([
            'type' => 'required|in:customer,supplier,both',
            'tax_id' => 'required|string|max:20', // Add unique check manually for company scope if needed
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
        ]);
        
        // Check uniqueness for company
        $exists = Contact::where('company_id', $companyId)
            ->where('tax_id', $request->tax_id)
            ->exists();
            
        if ($exists) {
            return back()->withErrors(['tax_id' => 'El número de documento ya existe para esta empresa.'])->withInput();
        }

        $contact = new Contact($request->all());
        $contact->company_id = $companyId;
        $contact->active = $request->has('active');
        $contact->save();

        return redirect()->route('contacts.index')->with('success', 'Contacto creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact)
    {
        $companyId = session('current_company_id');
        if ($contact->company_id != $companyId) abort(403);
        
        // Placeholders for related data
        $salesDocuments = []; // $contact->salesDocuments()->latest()->get();
        $purchaseDocuments = []; // $contact->purchaseDocuments()->latest()->get();
        
        return view('contacts.show', compact('contact', 'salesDocuments', 'purchaseDocuments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contact $contact)
    {
         $companyId = session('current_company_id');
         if ($contact->company_id != $companyId) abort(403);
         
         return view('contacts.edit', compact('contact'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contact $contact)
    {
        $companyId = session('current_company_id');
        if ($contact->company_id != $companyId) abort(403);

        $request->validate([
            'type' => 'required|in:customer,supplier,both',
            'tax_id' => 'required|string|max:20', 
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
        ]);

        // Check uniqueness ignoring self
        $exists = Contact::where('company_id', $companyId)
            ->where('tax_id', $request->tax_id)
            ->where('id', '!=', $contact->id)
            ->exists();
            
        if ($exists) {
            return back()->withErrors(['tax_id' => 'El número de documento ya existe para esta empresa.'])->withInput();
        }

        $data = $request->all();
        $data['active'] = $request->has('active');
        
        $contact->update($data);

        return redirect()->route('contacts.index')->with('success', 'Contacto actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        $companyId = session('current_company_id');
        if ($contact->company_id != $companyId) abort(403);
        
        $contact->delete();
        
        return redirect()->route('contacts.index')->with('success', 'Contacto eliminado.');
    }
}
