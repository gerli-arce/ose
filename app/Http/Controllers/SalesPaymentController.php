<?php

namespace App\Http\Controllers;

use App\Models\SalesDocument;
use App\Models\SalesPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesPaymentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'sales_document_id' => 'required|exists:sales_documents,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method_id' => 'required',
            'payment_date' => 'required|date'
        ]);

        $companyId = session('current_company_id');
        $document = SalesDocument::where('id', $request->sales_document_id)
            ->where('company_id', $companyId)
            ->firstOrFail();

        try {
            DB::beginTransaction();

            SalesPayment::create([
                'sales_document_id' => $document->id,
                'payment_method_id' => $request->payment_method_id,
                'amount' => $request->amount,
                'payment_date' => $request->payment_date,
                'reference' => $request->reference
            ]);

            // Update Status
            $totalPaid = $document->payments()->sum('amount') + $request->amount; // + new one (transaction isolation issues possible but unlikely for demo)
            
            // Re-fetch to be safe or use memory
            // Actually sum() is query so it includes new one if committed? No, transaction. 
            // Eloquent relationship cache? 
            // Better:
            $totalPaid = SalesPayment::where('sales_document_id', $document->id)->sum('amount'); // This query sees the new one inside transaction? Yes usually.

            if ($totalPaid >= $document->total) {
                $document->payment_status = 'paid';
            } elseif ($totalPaid > 0) {
                $document->payment_status = 'partial';
            } else {
                $document->payment_status = 'pending';
            }
            $document->save();

            DB::commit();

            return redirect()->back()->with('success', 'Pago registrado correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al registrar pago: ' . $e->getMessage());
        }
    }
}
