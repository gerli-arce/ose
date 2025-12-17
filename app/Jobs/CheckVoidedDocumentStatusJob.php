<?php

namespace App\Jobs;

use App\Models\VoidedDocument;
use App\Services\Sunat\SunatVoidedSender;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Job para consultar estado de Comunicación de Baja en SUNAT
 */
class CheckVoidedDocumentStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 10; // Reintentar varias veces
    public $timeout = 60;
    public $backoff = [10, 30, 60, 120]; // Backoff exponencial

    public function __construct(
        public int $voidedDocumentId
    ) {
    }

    public function handle(SunatVoidedSender $sender): void
    {
        $voidedDocument = VoidedDocument::findOrFail($this->voidedDocumentId);

        // Si ya tiene estado final, no hacer nada
        if (in_array($voidedDocument->status, ['accepted', 'rejected'])) {
            Log::info('Comunicación de Baja ya tiene estado final', [
                'id' => $this->voidedDocumentId,
                'status' => $voidedDocument->status,
            ]);
            return;
        }

        if (!$voidedDocument->ticket) {
            Log::warning('Comunicación de Baja sin ticket', ['id' => $this->voidedDocumentId]);
            return;
        }

        try {
            $result = $sender->checkStatus($voidedDocument);

            Log::info('Consulta de estado de Comunicación de Baja', [
                'id' => $this->voidedDocumentId,
                'result' => $result,
            ]);

            // Si aún está procesando, reintentar más tarde
            if ($result['status'] === 'processing') {
                // Re-encolar con delay
                self::dispatch($this->voidedDocumentId)
                    ->delay(now()->addMinutes(1));
            }
        } catch (\Throwable $e) {
            Log::error('Error al consultar estado de Comunicación de Baja', [
                'id' => $this->voidedDocumentId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
