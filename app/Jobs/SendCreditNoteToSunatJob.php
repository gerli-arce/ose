<?php

namespace App\Jobs;

use App\Models\SalesDocument;
use App\Services\Sunat\SunatCreditNoteSender;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendCreditNoteToSunatJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 120;

    public function __construct(
        public int $creditNoteId
    ) {
    }

    public function handle(SunatCreditNoteSender $sender): void
    {
        $creditNote = SalesDocument::findOrFail($this->creditNoteId);

        try {
            $result = $sender->send($creditNote);
            Log::info('SUNAT NC envío completado', ['doc' => $creditNote->id, 'result' => $result]);
        } catch (\Throwable $e) {
            Log::error('SUNAT NC envío fallido', [
                'doc' => $creditNote->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }
}
