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
            font-size: 8pt;
            color: #333;
            line-height: 1.3;
            width: 80mm;
        }
        .container {
            padding: 5mm;
        }
        
        /* Header */
        .header {
            text-align: center;
            border-bottom: 1px dashed #333;
            padding-bottom: 8px;
            margin-bottom: 8px;
        }
        .company-name {
            font-size: 10pt;
            font-weight: bold;
        }
        .company-info {
            font-size: 7pt;
            color: #666;
            margin-top: 3px;
        }
        .ruc {
            font-size: 9pt;
            font-weight: bold;
            margin-top: 5px;
        }

        /* Document Info */
        .document-info {
            text-align: center;
            background: #f0f0f0;
            padding: 5px;
            margin: 8px 0;
            border: 1px solid #ccc;
        }
        .doc-type {
            font-size: 9pt;
            font-weight: bold;
        }
        .doc-number {
            font-size: 10pt;
            font-weight: bold;
            color: #c53030;
        }

        /* Customer */
        .customer-section {
            font-size: 7pt;
            border-bottom: 1px dashed #333;
            padding-bottom: 8px;
            margin-bottom: 8px;
        }
        .customer-section .row {
            margin-bottom: 2px;
        }
        .customer-section .label {
            font-weight: bold;
        }

        /* Items */
        .items-section {
            border-bottom: 1px dashed #333;
            padding-bottom: 8px;
            margin-bottom: 8px;
        }
        .item {
            margin-bottom: 5px;
            padding-bottom: 5px;
            border-bottom: 1px dotted #ccc;
        }
        .item:last-child {
            border-bottom: none;
        }
        .item-desc {
            font-weight: bold;
            font-size: 8pt;
        }
        .item-detail {
            font-size: 7pt;
            color: #666;
        }
        .item-total {
            text-align: right;
            font-weight: bold;
        }

        /* Totals */
        .totals-section {
            font-size: 8pt;
            border-bottom: 1px dashed #333;
            padding-bottom: 8px;
            margin-bottom: 8px;
        }
        .total-row {
            display: table;
            width: 100%;
            margin-bottom: 2px;
        }
        .total-row .label {
            display: table-cell;
            text-align: right;
            padding-right: 10px;
        }
        .total-row .value {
            display: table-cell;
            text-align: right;
            font-weight: bold;
            width: 60px;
        }
        .grand-total {
            font-size: 10pt;
            background: #333;
            color: white;
            padding: 5px;
            margin-top: 5px;
        }

        /* Amount in Words */
        .amount-words {
            font-size: 7pt;
            background: #f0f0f0;
            padding: 5px;
            margin-bottom: 8px;
            text-align: center;
        }

        /* QR Section */
        .qr-section {
            text-align: center;
            margin: 10px 0;
        }
        .qr-section img {
            width: 100px;
            height: 100px;
        }
        .hash {
            font-size: 6pt;
            color: #666;
            word-break: break-all;
            margin-top: 5px;
        }

        /* Footer */
        .footer {
            text-align: center;
            font-size: 6pt;
            color: #999;
            margin-top: 10px;
            border-top: 1px dashed #333;
            padding-top: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="company-name">{{ $company->business_name ?? $company->name }}</div>
            <div class="company-info">
                {{ $company->address ?? '' }}<br>
                @if($company->district || $company->province)
                    {{ $company->district ?? '' }} - {{ $company->province ?? '' }}<br>
                @endif
                @if($company->phone)Tel: {{ $company->phone }}@endif
            </div>
            <div class="ruc">RUC: {{ $company->tax_id }}</div>
        </div>

        <!-- Document Info -->
        <div class="document-info">
            <div class="doc-type">{{ $documentTypeName }}</div>
            <div class="doc-number">{{ $fullNumber }}</div>
            <div style="font-size: 7pt;">{{ $emissionDate }}</div>
        </div>

        <!-- Customer -->
        <div class="customer-section">
            <div class="row">
                <span class="label">Cliente:</span> {{ $customer->name ?? 'CLIENTE VARIOS' }}
            </div>
            <div class="row">
                <span class="label">{{ strlen($customer->tax_id ?? '') == 11 ? 'RUC' : 'DNI' }}:</span> 
                {{ $customer->tax_id ?? '-' }}
            </div>
        </div>

        <!-- Items -->
        <div class="items-section">
            @foreach($items as $item)
            <div class="item">
                <div class="item-desc">{{ $item->description ?? $item->product?->name ?? '-' }}</div>
                <div class="item-detail">
                    {{ number_format($item->quantity, 2) }} x S/ {{ number_format($item->unit_price, 2) }}
                </div>
                <div class="item-total">S/ {{ number_format($item->total, 2) }}</div>
            </div>
            @endforeach
        </div>

        <!-- Totals -->
        <div class="totals-section">
            <div class="total-row">
                <span class="label">Subtotal:</span>
                <span class="value">{{ number_format($document->subtotal, 2) }}</span>
            </div>
            <div class="total-row">
                <span class="label">IGV (18%):</span>
                <span class="value">{{ number_format($document->tax_total, 2) }}</span>
            </div>
            <div class="total-row grand-total">
                <span class="label">TOTAL:</span>
                <span class="value">S/ {{ number_format($document->total, 2) }}</span>
            </div>
        </div>

        <!-- Amount in Words -->
        <div class="amount-words">
            SON: {{ $amountInWords }}
        </div>

        <!-- QR Code -->
        <div class="qr-section">
            @if($qrCode)
                <img src="{{ $qrCode }}" alt="QR">
            @endif
            @if($hash)
            <div class="hash">
                Hash: {{ $hash }}
            </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="footer">
            Representación impresa del documento electrónico<br>
            Consultar en: www.sunat.gob.pe<br>
            {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>
</body>
</html>
