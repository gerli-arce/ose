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
 * Job para consultar estado de Resumen Diario en SUNAT
 */
class CheckDailySummaryStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 10; // Reintentar varias veces
    public $timeout = 60;
    public $backoff = [10, 30, 60, 120]; // Backoff exponencial

    public function __construct(
        public int $dailySummaryId
    ) {
    }

    public function handle(SunatSummarySender $sender): void
    {
        $summary = DailySummary::findOrFail($this->dailySummaryId);

        // Si ya tiene estado final, no hacer nada
        if (in_array($summary->status, ['accepted', 'rejected'])) {
            Log::info('Resumen Diario ya tiene estado final', [
                'id' => $this->dailySummaryId,
                'status' => $summary->status,
            ]);
            return;
        }

        if (!$summary->ticket) {
            Log::warning('Resumen Diario sin ticket', ['id' => $this->dailySummaryId]);
            return;
        }

        try {
            $result = $sender->checkStatus($summary);

            Log::info('Consulta de estado de Resumen Diario', [
                'id' => $this->dailySummaryId,
                'result' => $result,
            ]);

            // Si aún está procesando, reintentar más tarde
            if ($result['status'] === 'processing') {
                // Re-encolar con delay
                self::dispatch($this->dailySummaryId)
                    ->delay(now()->addMinutes(1));
            }
        } catch (\Throwable $e) {
            Log::error('Error al consultar estado de Resumen Diario', [
                'id' => $this->dailySummaryId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
