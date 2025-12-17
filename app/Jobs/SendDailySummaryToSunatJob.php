<?php

namespace App\Jobs;

use App\Models\DailySummary;
use App\Services\Sunat\SunatSummarySender;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Job para enviar Resumen Diario a SUNAT
 */
class SendDailySummaryToSunatJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 120;

    public function __construct(
        public int $dailySummaryId
    ) {
    }

    public function handle(SunatSummarySender $sender): void
    {
        $summary = DailySummary::findOrFail($this->dailySummaryId);

        try {
            $result = $sender->send($summary);

            if ($result['success'] && isset($result['ticket'])) {
                // Programar verificación de estado después de 10 segundos
                CheckDailySummaryStatusJob::dispatch($this->dailySummaryId)
                    ->delay(now()->addSeconds(10));
                
                Log::info('Resumen Diario enviado, ticket obtenido', [
                    'id' => $this->dailySummaryId,
                    'ticket' => $result['ticket'],
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('Error al enviar Resumen Diario', [
                'id' => $this->dailySummaryId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
