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
 * Job para enviar Comunicación de Baja a SUNAT
 */
class SendVoidedDocumentToSunatJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 120;

    public function __construct(
        public int $voidedDocumentId
    ) {
    }

    public function handle(SunatVoidedSender $sender): void
    {
        $voidedDocument = VoidedDocument::findOrFail($this->voidedDocumentId);

        try {
            $result = $sender->send($voidedDocument);

            if ($result['success'] && isset($result['ticket'])) {
                // Programar verificación de estado después de 5 segundos
                CheckVoidedDocumentStatusJob::dispatch($this->voidedDocumentId)
                    ->delay(now()->addSeconds(5));
                
                Log::info('Comunicación de Baja enviada, ticket obtenido', [
                    'id' => $this->voidedDocumentId,
                    'ticket' => $result['ticket'],
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('Error al enviar Comunicación de Baja', [
                'id' => $this->voidedDocumentId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
