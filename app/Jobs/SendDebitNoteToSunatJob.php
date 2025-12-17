<?php

namespace App\Jobs;

use App\Models\SalesDocument;
use App\Services\Sunat\SunatDebitNoteSender;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Job para enviar Nota de Débito a SUNAT de forma asíncrona
 */
class SendDebitNoteToSunatJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 120;

    public function __construct(
        public int $salesDocumentId
    ) {
    }

    public function handle(SunatDebitNoteSender $sender): void
    {
        $debitNote = SalesDocument::findOrFail($this->salesDocumentId);

        try {
            $result = $sender->send($debitNote);

            Log::info('Nota de Débito procesada', [
                'id' => $this->salesDocumentId,
                'success' => $result['success'],
                'code' => $result['code'] ?? null,
            ]);

        } catch (\Throwable $e) {
            Log::error('Error al procesar Nota de Débito', [
                'id' => $this->salesDocumentId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
