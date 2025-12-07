<?php

namespace App\Http\Controllers;

use App\Models\EDocument;
use App\Models\EDocumentLog;
use Illuminate\Http\Request;

class EDocumentController extends Controller
{
    // Simulate sending to SUNAT
    public function send($id)
    {
        $eDoc = EDocument::findOrFail($id);
        
        // Simulation Logic
        // For demo: Randomly accept or reject, or just accept. 
        // Let's make it deterministic or successful for now.
        
        $status = 'accepted';
        $message = 'El comprobante ha sido ACEPTADO';
        
        // Random failure chance? No, user wants to verify flow
        
        $eDoc->response_status = $status;
        $eDoc->sent_at = now();
        $eDoc->save();

        // Log
        EDocumentLog::create([
            'e_document_id' => $eDoc->id,
            'message' => $message,
            'status' => 'success'
        ]);

        return back()->with('success', 'Documento enviado a SUNAT (Simulación): ' . $status);
    }
}
