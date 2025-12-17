<?php

namespace App\Jobs;

use App\Models\Company;
use App\Models\DailySummary;
use App\Services\Sunat\SunatSummaryBuilder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Job para generar Resumen Diario de Boletas
 * Se ejecuta al final del día o manualmente
 */
class GenerateDailySummaryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 300;

    public function __construct(
        public int $companyId,
        public string $referenceDate // YYYY-MM-DD
    ) {
    }

    public function handle(SunatSummaryBuilder $builder): void
    {
        $company = Company::find($this->companyId);
        if (!$company) {
            Log::warning('Empresa no encontrada para resumen diario', ['company_id' => $this->companyId]);
            return;
        }

        $date = new \DateTime($this->referenceDate);

        try {
            $summary = $builder->createFromPendingBoletas($this->companyId, $date);

            if (!$summary) {
                Log::info('No hay boletas pendientes para resumen', [
                    'company_id' => $this->companyId,
                    'date' => $this->referenceDate,
                ]);
                return;
            }

            Log::info('Resumen Diario generado', [
                'id' => $summary->id,
                'identifier' => $summary->identifier,
                'total_docs' => $summary->total_documents,
            ]);

            // Enviar a SUNAT
            SendDailySummaryToSunatJob::dispatch($summary->id);

        } catch (\Throwable $e) {
            Log::error('Error al generar Resumen Diario', [
                'company_id' => $this->companyId,
                'date' => $this->referenceDate,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
