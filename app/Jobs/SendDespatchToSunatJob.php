<?php

namespace App\Jobs;

use App\Models\DespatchAdvice;
use App\Services\Sunat\SunatDespatchSender;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Job para enviar Guía de Remisión a SUNAT de forma asíncrona
 */
class SendDespatchToSunatJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 120;

    public function __construct(
        private int $despatchId
    ) {
    }

    public function handle(SunatDespatchSender $sender): void
    {
        $despatch = DespatchAdvice::with([
            'company',
            'series',
            'items.product',
            'transferReason',
            'transportModality',
            'originUbigeo',
            'destinationUbigeo',
            'transporter',
            'vehicle',
        ])->find($this->despatchId);

        if (!$despatch) {
            Log::error("Guía de Remisión no encontrada: {$this->despatchId}");
            return;
        }

        $result = $sender->send($despatch);

        if (!$result['success']) {
            Log::warning("Fallo en envío de guía {$despatch->full_number}: " . ($result['message'] ?? 'Error desconocido'));
        }
    }
}
