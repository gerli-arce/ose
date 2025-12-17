<?php

namespace App\Http\Controllers;

use App\Models\SalesDocument;
use App\Services\Pdf\SunatPdfGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PdfController extends Controller
{
    public function __construct(
        private SunatPdfGenerator $pdfGenerator
    ) {
    }

    /**
     * Generar y descargar PDF en formato A4
     */
    public function downloadA4(SalesDocument $document)
    {
        $companyId = session('current_company_id');

        if ($document->company_id != $companyId) {
            abort(403);
        }

        $pdf = $this->pdfGenerator->generateA4($document);
        $filename = $this->getFilename($document, 'A4');

        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }

    /**
     * Generar y descargar PDF en formato Ticket
     */
    public function downloadTicket(SalesDocument $document)
    {
        $companyId = session('current_company_id');

        if ($document->company_id != $companyId) {
            abort(403);
        }

        $pdf = $this->pdfGenerator->generateTicket($document);
        $filename = $this->getFilename($document, 'ticket');

        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }

    /**
     * Ver PDF en el navegador (formato A4)
     */
    public function viewA4(SalesDocument $document)
    {
        $companyId = session('current_company_id');

        if ($document->company_id != $companyId) {
            abort(403);
        }

        $pdf = $this->pdfGenerator->generateA4($document);
        $filename = $this->getFilename($document, 'A4');

        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', "inline; filename=\"{$filename}\"");
    }

    /**
     * Generar y guardar PDF en storage
     */
    public function store(SalesDocument $document, string $format = 'A4'): string
    {
        $pdf = $format === 'ticket' 
            ? $this->pdfGenerator->generateTicket($document)
            : $this->pdfGenerator->generateA4($document);

        $path = $this->getStoragePath($document, $format);
        Storage::put($path, $pdf);

        return $path;
    }

    /**
     * Obtener nombre de archivo para el PDF
     */
    private function getFilename(SalesDocument $document, string $format): string
    {
        $ruc = $document->company?->tax_id ?? '00000000000';
        $tipoDoc = $document->documentType?->code ?? '01';
        $serie = $document->series?->prefix ?? 'F001';
        $numero = str_pad($document->number ?? 0, 8, '0', STR_PAD_LEFT);
        
        $formatSuffix = $format === 'ticket' ? '_ticket' : '';
        
        return "{$ruc}-{$tipoDoc}-{$serie}-{$numero}{$formatSuffix}.pdf";
    }

    /**
     * Obtener ruta de almacenamiento
     */
    private function getStoragePath(SalesDocument $document, string $format): string
    {
        $tipoDoc = $document->documentType?->code ?? '01';
        $filename = $this->getFilename($document, $format);
        
        return "edocs/pdf/{$tipoDoc}/{$filename}";
    }
}
