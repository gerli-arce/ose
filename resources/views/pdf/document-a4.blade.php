<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $documentTypeName }} {{ $fullNumber }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10pt;
            color: #333;
            line-height: 1.4;
        }
        .container {
            width: 100%;
            padding: 20px;
        }
        
        /* Header */
        .header {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .header-left {
            display: table-cell;
            width: 60%;
            vertical-align: top;
        }
        .header-right {
            display: table-cell;
            width: 40%;
            vertical-align: top;
        }
        .company-name {
            font-size: 14pt;
            font-weight: bold;
            color: #1a365d;
            margin-bottom: 5px;
        }
        .company-info {
            font-size: 9pt;
            color: #666;
            line-height: 1.5;
        }
        
        /* Document Box */
        .document-box {
            border: 2px solid #1a365d;
            text-align: center;
            padding: 10px;
        }
        .document-box .ruc {
            font-size: 12pt;
            font-weight: bold;
            color: #1a365d;
        }
        .document-box .doc-type {
            font-size: 11pt;
            font-weight: bold;
            color: #c53030;
            margin: 5px 0;
        }
        .document-box .doc-number {
            font-size: 13pt;
            font-weight: bold;
            color: #1a365d;
        }

        /* Customer Section */
        .customer-section {
            margin: 20px 0;
            background: #f7fafc;
            padding: 15px;
            border: 1px solid #e2e8f0;
        }
        .customer-section table {
            width: 100%;
        }
        .customer-section td {
            padding: 3px 10px 3px 0;
            vertical-align: top;
        }
        .customer-section .label {
            font-weight: bold;
            color: #4a5568;
            width: 120px;
        }

        /* Note Reference */
        .note-reference {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            padding: 10px;
            margin-bottom: 15px;
        }
        .note-reference .title {
            font-weight: bold;
            color: #92400e;
            margin-bottom: 5px;
        }

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .items-table thead {
            background: #1a365d;
            color: white;
        }
        .items-table th {
            padding: 8px 10px;
            text-align: left;
            font-size: 9pt;
        }
        .items-table th.number {
            text-align: right;
        }
        .items-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 9pt;
        }
        .items-table td.number {
            text-align: right;
        }
        .items-table tbody tr:nth-child(even) {
            background: #f7fafc;
        }

        /* Totals */
        .totals-section {
            display: table;
            width: 100%;
            margin-top: 20px;
        }
        .totals-left {
            display: table-cell;
            width: 55%;
            vertical-align: top;
        }
        .totals-right {
            display: table-cell;
            width: 45%;
            vertical-align: top;
        }
        .amount-words {
            background: #edf2f7;
            padding: 10px;
            font-size: 9pt;
            border: 1px solid #cbd5e0;
        }
        .amount-words .label {
            font-weight: bold;
            margin-bottom: 3px;
        }
        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }
        .totals-table td {
            padding: 5px 10px;
            border: 1px solid #e2e8f0;
        }
        .totals-table .label {
            text-align: right;
            background: #f7fafc;
        }
        .totals-table .value {
            text-align: right;
            font-weight: bold;
            width: 100px;
        }
        .totals-table .grand-total {
            background: #1a365d;
            color: white;
            font-size: 12pt;
        }

        /* QR and Hash Section */
        .qr-section {
            display: table;
            width: 100%;
            margin-top: 25px;
            border-top: 2px solid #1a365d;
            padding-top: 15px;
        }
        .qr-code {
            display: table-cell;
            width: 160px;
            vertical-align: top;
            text-align: center;
        }
        .qr-code img {
            width: 130px;
            height: 130px;
        }
        .hash-info {
            display: table-cell;
            vertical-align: top;
            padding-left: 20px;
        }
        .hash-info .hash-label {
            font-weight: bold;
            color: #4a5568;
            font-size: 9pt;
        }
        .hash-info .hash-value {
            font-family: monospace;
            font-size: 8pt;
            color: #718096;
            word-break: break-all;
            margin-top: 3px;
        }
        .sunat-text {
            font-size: 8pt;
            color: #718096;
            margin-top: 10px;
        }

        /* Footer */
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 8pt;
            color: #a0aec0;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-left">
                <div class="company-name">{{ $company->business_name ?? $company->name }}</div>
                <div class="company-info">
                    @if($company->trade_name && $company->trade_name != $company->business_name)
                        <strong>Nombre Comercial:</strong> {{ $company->trade_name }}<br>
                    @endif
                    <strong>Dirección:</strong> {{ $company->address ?? '-' }}<br>
                    @if($company->ubigeo)
                        <strong>Ubigeo:</strong> {{ $company->ubigeo }}<br>
                    @endif
                    @if($company->district || $company->province || $company->department)
                        {{ $company->district ?? '' }}{{ $company->district && $company->province ? ' - ' : '' }}{{ $company->province ?? '' }}{{ $company->province && $company->department ? ' - ' : '' }}{{ $company->department ?? '' }}<br>
                    @endif
                    @if($company->phone)
                        <strong>Teléfono:</strong> {{ $company->phone }}<br>
                    @endif
                    @if($company->email)
                        <strong>Email:</strong> {{ $company->email }}<br>
                    @endif
                </div>
            </div>
            <div class="header-right">
                <div class="document-box">
                    <div class="ruc">RUC: {{ $company->tax_id }}</div>
                    <div class="doc-type">{{ $documentTypeName }}</div>
                    <div class="doc-number">{{ $fullNumber }}</div>
                </div>
            </div>
        </div>

        <!-- Customer Section -->
        <div class="customer-section">
            <table>
                <tr>
                    <td class="label">Cliente:</td>
                    <td>{{ $customer->name ?? 'CLIENTE VARIOS' }}</td>
                    <td class="label">Fecha Emisión:</td>
                    <td>{{ $emissionDate }}</td>
                </tr>
                <tr>
                    <td class="label">{{ $customer && strlen($customer->tax_id ?? '') == 11 ? 'RUC' : 'DNI' }}:</td>
                    <td>{{ $customer->tax_id ?? '-' }}</td>
                    <td class="label">Moneda:</td>
                    <td>{{ $document->currency ?? 'PEN' }}</td>
                </tr>
                <tr>
                    <td class="label">Dirección:</td>
                    <td colspan="3">{{ $customer->address ?? '-' }}</td>
                </tr>
            </table>
        </div>

        <!-- Note Reference (for NC/ND) -->
        @if($document->relatedDocument)
        <div class="note-reference">
            <div class="title">Documento de Referencia</div>
            <table>
                <tr>
                    <td style="width: 150px;"><strong>Tipo:</strong> {{ $document->relatedDocument->documentType?->name ?? '-' }}</td>
                    <td style="width: 200px;"><strong>Número:</strong> {{ $document->relatedDocument->series?->prefix ?? '' }}-{{ str_pad($document->relatedDocument->number ?? 0, 8, '0', STR_PAD_LEFT) }}</td>
                    <td><strong>Fecha:</strong> {{ $document->relatedDocument->issue_date?->format('d/m/Y') ?? '-' }}</td>
                </tr>
            </table>
            @if($document->creditNoteType || $document->debitNoteType)
            <div style="margin-top: 5px;">
                <strong>Tipo de Nota:</strong> 
                {{ $document->creditNoteType?->code ?? $document->debitNoteType?->code ?? '' }} - 
                {{ $document->creditNoteType?->name ?? $document->debitNoteType?->name ?? '' }}
            </div>
            @endif
            @if($document->note_reason)
            <div style="margin-top: 3px;">
                <strong>Motivo:</strong> {{ $document->note_reason }}
            </div>
            @endif
        </div>
        @endif

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 40px;">Item</th>
                    <th style="width: 70px;">Código</th>
                    <th>Descripción</th>
                    <th style="width: 50px;">Unidad</th>
                    <th class="number" style="width: 60px;">Cantidad</th>
                    <th class="number" style="width: 80px;">P. Unit.</th>
                    <th class="number" style="width: 80px;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->product?->code ?? '-' }}</td>
                    <td>{{ $item->description ?? $item->product?->name ?? '-' }}</td>
                    <td>{{ $item->product?->unitOfMeasure?->symbol ?? 'UND' }}</td>
                    <td class="number">{{ number_format($item->quantity, 2) }}</td>
                    <td class="number">{{ number_format($item->unit_price, 2) }}</td>
                    <td class="number">{{ number_format($item->total, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals Section -->
        <div class="totals-section">
            <div class="totals-left">
                <div class="amount-words">
                    <div class="label">SON:</div>
                    {{ $amountInWords }}
                </div>
            </div>
            <div class="totals-right">
                <table class="totals-table">
                    <tr>
                        <td class="label">Op. Gravadas:</td>
                        <td class="value">{{ number_format($document->subtotal, 2) }}</td>
                    </tr>
                    @if(($document->exonerated ?? 0) > 0)
                    <tr>
                        <td class="label">Op. Exoneradas:</td>
                        <td class="value">{{ number_format($document->exonerated, 2) }}</td>
                    </tr>
                    @endif
                    @if(($document->unaffected ?? 0) > 0)
                    <tr>
                        <td class="label">Op. Inafectas:</td>
                        <td class="value">{{ number_format($document->unaffected, 2) }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td class="label">IGV (18%):</td>
                        <td class="value">{{ number_format($document->tax_total, 2) }}</td>
                    </tr>
                    <tr class="grand-total">
                        <td class="label" style="background: #1a365d; color: white;">TOTAL:</td>
                        <td class="value">S/ {{ number_format($document->total, 2) }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- QR Code and Hash -->
        <div class="qr-section">
            <div class="qr-code">
                @if($qrCode)
                    <img src="{{ $qrCode }}" alt="Código QR">
                @endif
            </div>
            <div class="hash-info">
                <div class="hash-label">Código Hash:</div>
                <div class="hash-value">{{ $hash }}</div>
                
                <div class="sunat-text">
                    Representación impresa de la {{ $documentTypeName }}<br>
                    Autorizado mediante Resolución de Superintendencia N° 000-2024/SUNAT<br>
                    Para consultar el comprobante ingresar a: <strong>https://www.sunat.gob.pe</strong>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            Documento generado electrónicamente - {{ now()->format('d/m/Y H:i:s') }}
        </div>
    </div>
</body>
</html>
