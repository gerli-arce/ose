<?php

namespace App\Services\Pdf;

use App\Models\SalesDocument;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

/**
 * Servicio para generar PDFs de documentos electrónicos SUNAT
 */
class SunatPdfGenerator
{
    /**
     * Generar PDF en formato A4
     */
    public function generateA4(SalesDocument $document): string
    {
        $document->loadMissing([
            'company',
            'customer',
            'series',
            'documentType',
            'items.product.unitOfMeasure',
            'eDocument',
            'relatedDocument.series',
            'creditNoteType',
            'debitNoteType',
        ]);

        $data = $this->prepareData($document);
        $data['format'] = 'A4';

        $pdf = Pdf::loadView('pdf.document-a4', $data);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->output();
    }

    /**
     * Generar PDF en formato Ticket (80mm)
     */
    public function generateTicket(SalesDocument $document): string
    {
        $document->loadMissing([
            'company',
            'customer',
            'series',
            'documentType',
            'items.product.unitOfMeasure',
            'eDocument',
        ]);

        $data = $this->prepareData($document);
        $data['format'] = 'ticket';

        $pdf = Pdf::loadView('pdf.document-ticket', $data);
        // Ancho 80mm, altura variable
        $pdf->setPaper([0, 0, 226.77, 600], 'portrait'); // 80mm = 226.77 points

        return $pdf->output();
    }

    /**
     * Preparar datos comunes para el PDF
     */
    private function prepareData(SalesDocument $document): array
    {
        return [
            'document' => $document,
            'company' => $document->company,
            'customer' => $document->customer,
            'items' => $document->items,
            'qrCode' => $this->generateQrCode($document),
            'hash' => $document->eDocument?->hash ?? '',
            'amountInWords' => $this->numberToWords($document->total, $document->currency ?? 'PEN'),
            'documentTypeName' => $this->getDocumentTypeName($document),
            'emissionDate' => $document->issue_date->format('d/m/Y'),
            'fullNumber' => $this->getFullNumber($document),
        ];
    }

    /**
     * Generar código QR según especificación SUNAT
     * Formato: RUC|TIPO_DOC|SERIE|NUMERO|IGV|TOTAL|FECHA|TIPO_DOC_CLIENTE|NUM_DOC_CLIENTE|HASH
     */
    private function generateQrCode(SalesDocument $document): string
    {
        $company = $document->company;
        $customer = $document->customer;
        
        $qrContent = implode('|', [
            $company->tax_id ?? '',                                    // RUC del emisor
            $document->documentType?->code ?? '01',                    // Tipo de documento
            $document->series?->prefix ?? '',                          // Serie
            str_pad($document->number ?? 0, 8, '0', STR_PAD_LEFT),    // Número
            number_format($document->tax_total ?? 0, 2, '.', ''),     // IGV
            number_format($document->total ?? 0, 2, '.', ''),         // Total
            $document->issue_date?->format('Y-m-d') ?? '',            // Fecha emisión
            $customer?->sunat_doc_type_code ?? '1',                   // Tipo doc cliente
            $customer?->tax_id ?? '00000000',                         // Número doc cliente
            $document->eDocument?->hash ?? '',                        // Hash (DigestValue)
        ]);

        // Generar QR como imagen base64
        try {
            $qr = QrCode::format('svg')
                ->size(150)
                ->margin(1)
                ->generate($qrContent);
            
            return 'data:image/svg+xml;base64,' . base64_encode($qr);
        } catch (\Exception $e) {
            // Si falla el QR, retornar vacío
            return '';
        }
    }

    /**
     * Convertir número a letras (español)
     */
    private function numberToWords(float $amount, string $currency = 'PEN'): string
    {
        $currencyName = match($currency) {
            'PEN' => 'SOLES',
            'USD' => 'DÓLARES AMERICANOS',
            'EUR' => 'EUROS',
            default => 'SOLES',
        };

        $entero = (int) $amount;
        $decimal = round(($amount - $entero) * 100);

        $texto = $this->convertNumber($entero);
        
        if ($decimal > 0) {
            return strtoupper($texto) . ' CON ' . str_pad($decimal, 2, '0', STR_PAD_LEFT) . '/100 ' . $currencyName;
        }
        
        return strtoupper($texto) . ' CON 00/100 ' . $currencyName;
    }

    /**
     * Convertir número entero a texto
     */
    private function convertNumber(int $number): string
    {
        $unidades = ['', 'uno', 'dos', 'tres', 'cuatro', 'cinco', 'seis', 'siete', 'ocho', 'nueve', 'diez', 'once', 'doce', 'trece', 'catorce', 'quince'];
        $decenas = ['', '', 'veinti', 'treinta', 'cuarenta', 'cincuenta', 'sesenta', 'setenta', 'ochenta', 'noventa'];
        $centenas = ['', 'ciento', 'doscientos', 'trescientos', 'cuatrocientos', 'quinientos', 'seiscientos', 'setecientos', 'ochocientos', 'novecientos'];

        if ($number == 0) return 'cero';
        if ($number == 100) return 'cien';

        $texto = '';

        // Millones
        if ($number >= 1000000) {
            $millones = (int)($number / 1000000);
            if ($millones == 1) {
                $texto .= 'un millón ';
            } else {
                $texto .= $this->convertNumber($millones) . ' millones ';
            }
            $number %= 1000000;
        }

        // Miles
        if ($number >= 1000) {
            $miles = (int)($number / 1000);
            if ($miles == 1) {
                $texto .= 'mil ';
            } else {
                $texto .= $this->convertNumber($miles) . ' mil ';
            }
            $number %= 1000;
        }

        // Centenas
        if ($number >= 100) {
            $texto .= $centenas[(int)($number / 100)] . ' ';
            $number %= 100;
        }

        // Decenas y unidades
        if ($number >= 1 && $number <= 15) {
            $texto .= $unidades[$number];
        } elseif ($number >= 16 && $number <= 19) {
            $texto .= 'dieci' . $unidades[$number - 10];
        } elseif ($number >= 20 && $number <= 29) {
            if ($number == 20) {
                $texto .= 'veinte';
            } else {
                $texto .= 'veinti' . $unidades[$number - 20];
            }
        } elseif ($number >= 30) {
            $dec = (int)($number / 10);
            $uni = $number % 10;
            $texto .= $decenas[$dec];
            if ($uni > 0) {
                $texto .= ' y ' . $unidades[$uni];
            }
        }

        return trim($texto);
    }

    /**
     * Obtener nombre del tipo de documento
     */
    private function getDocumentTypeName(SalesDocument $document): string
    {
        return match($document->documentType?->code) {
            '01' => 'FACTURA ELECTRÓNICA',
            '03' => 'BOLETA DE VENTA ELECTRÓNICA',
            '07' => 'NOTA DE CRÉDITO ELECTRÓNICA',
            '08' => 'NOTA DE DÉBITO ELECTRÓNICA',
            default => 'DOCUMENTO ELECTRÓNICO',
        };
    }

    /**
     * Obtener número completo del documento
     */
    private function getFullNumber(SalesDocument $document): string
    {
        $prefix = $document->series?->prefix ?? '';
        $number = str_pad($document->number ?? 0, 8, '0', STR_PAD_LEFT);
        return "{$prefix}-{$number}";
    }
}
