<?php

namespace App\Jobs;

use App\Models\SalesDocument;
use App\Services\Sunat\SunatSender;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendSalesDocumentToSunatJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 120;

    public function __construct(
        public int $salesDocumentId
    ) {
    }

    public function handle(SunatSender $sender): void
    {
        $document = SalesDocument::findOrFail($this->salesDocumentId);

        try {
            $result = $sender->send($document);
            Log::info('SUNAT envío completado', ['doc' => $document->id, 'result' => $result]);
        } catch (\Throwable $e) {
            Log::error('SUNAT envío fallido', [
                'doc' => $document->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }
}
