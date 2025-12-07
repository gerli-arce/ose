<?php

namespace App\Http\Controllers;

use App\Models\PurchaseDocument;
use App\Models\PurchasePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchasePaymentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'purchase_document_id' => 'required|exists:purchase_documents,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_method_id' => 'required|exists:payment_methods,id',
        ]);

        try {
            DB::beginTransaction();

            $document = PurchaseDocument::findOrFail($request->purchase_document_id);

            // Create Payment
            PurchasePayment::create([
                'purchase_document_id' => $document->id,
                'payment_method_id' => $request->payment_method_id,
                'amount' => $request->amount,
                'payment_date' => $request->payment_date,
                'reference' => $request->reference,
            ]);

            // Update Status
            $totalPaid = $document->payments()->sum('amount');
            // Better: use the newly created one.
            
            // Re-fetch sum including the new one
            // Actually simple logic:
            if ($totalPaid >= $document->total) {
                $document->status = 'paid';
            } elseif ($totalPaid > 0) {
                $document->status = 'partial';
            } else {
                $document->status = 'registered';
            }
            $document->save();

            DB::commit();

            return back()->with('success', 'Pago registrado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al registrar pago: ' . $e->getMessage());
        }
    }
}
