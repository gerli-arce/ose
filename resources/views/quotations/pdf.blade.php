<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Cotización {{ $quotation->full_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #333; }
        .container { padding: 20px; }
        
        /* Header */
        .header { display: table; width: 100%; margin-bottom: 20px; }
        .header-left { display: table-cell; width: 60%; vertical-align: top; }
        .header-right { display: table-cell; width: 40%; text-align: right; vertical-align: top; }
        .company-name { font-size: 18px; font-weight: bold; color: #2c3e50; margin-bottom: 5px; }
        .company-info { font-size: 10px; color: #666; line-height: 1.5; }
        
        /* Documento */
        .doc-box { background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 15px 20px; border-radius: 8px; }
        .doc-title { font-size: 14px; margin-bottom: 5px; }
        .doc-number { font-size: 20px; font-weight: bold; }
        
        /* Cliente */
        .section { margin-bottom: 20px; }
        .section-title { font-size: 12px; font-weight: bold; color: #2c3e50; border-bottom: 2px solid #667eea; padding-bottom: 5px; margin-bottom: 10px; }
        .info-row { display: table; width: 100%; margin-bottom: 8px; }
        .info-label { display: table-cell; width: 120px; font-weight: bold; color: #666; }
        .info-value { display: table-cell; }
        
        /* Tabla Items */
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .items-table th { background: #667eea; color: white; padding: 10px 8px; text-align: left; font-size: 10px; }
        .items-table th:last-child { text-align: right; }
        .items-table td { padding: 8px; border-bottom: 1px solid #eee; font-size: 10px; }
        .items-table td:last-child { text-align: right; }
        .items-table tr:nth-child(even) { background: #f9f9f9; }
        .text-right { text-align: right !important; }
        .text-center { text-align: center !important; }
        
        /* Totales */
        .totals-container { display: table; width: 100%; }
        .totals-notes { display: table-cell; width: 60%; vertical-align: top; padding-right: 20px; }
        .totals-box { display: table-cell; width: 40%; vertical-align: top; }
        .totals-table { width: 100%; }
        .totals-table td { padding: 8px 10px; }
        .totals-table .label { text-align: right; color: #666; }
        .totals-table .value { text-align: right; font-weight: bold; }
        .total-row { background: linear-gradient(135deg, #667eea, #764ba2); color: white; font-size: 14px; }
        .total-row td { padding: 12px 10px; }
        
        /* Footer */
        .footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; }
        .notes-box { background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 15px; }
        .notes-title { font-weight: bold; margin-bottom: 5px; }
        
        .validity-box { background: #fff3cd; border: 1px solid #ffc107; padding: 10px 15px; border-radius: 5px; text-align: center; margin-top: 20px; }
        .validity-box strong { color: #856404; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-left">
                <div class="company-name">{{ $quotation->company?->name ?? 'Empresa' }}</div>
                <div class="company-info">
                    RUC: {{ $quotation->company?->tax_id ?? '' }}<br>
                    {{ $quotation->company?->address ?? '' }}<br>
                    {{ $quotation->company?->phone ? 'Tel: ' . $quotation->company->phone : '' }}
                    {{ $quotation->company?->email ? ' | ' . $quotation->company->email : '' }}
                </div>
            </div>
            <div class="header-right">
                <div class="doc-box">
                    <div class="doc-title">COTIZACIÓN</div>
                    <div class="doc-number">{{ $quotation->full_number }}</div>
                </div>
            </div>
        </div>

        <!-- Información del Cliente -->
        <div class="section">
            <div class="section-title">DATOS DEL CLIENTE</div>
            <div class="info-row">
                <div class="info-label">Cliente:</div>
                <div class="info-value">{{ $quotation->customer?->name ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">RUC/DNI:</div>
                <div class="info-value">{{ $quotation->customer?->tax_id ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Dirección:</div>
                <div class="info-value">{{ $quotation->customer?->address ?? 'N/A' }}</div>
            </div>
        </div>

        <!-- Información del Documento -->
        <div class="section">
            <div class="section-title">INFORMACIÓN DE LA COTIZACIÓN</div>
            <div class="info-row">
                <div class="info-label">Fecha Emisión:</div>
                <div class="info-value">{{ $quotation->issue_date->format('d/m/Y') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Válida hasta:</div>
                <div class="info-value">{{ $quotation->expiry_date->format('d/m/Y') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Moneda:</div>
                <div class="info-value">{{ $quotation->currency?->name ?? 'Soles' }} ({{ $quotation->currency?->code ?? 'PEN' }})</div>
            </div>
            @if($quotation->seller)
            <div class="info-row">
                <div class="info-label">Vendedor:</div>
                <div class="info-value">{{ $quotation->seller->name }}</div>
            </div>
            @endif
        </div>

        <!-- Items -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 30px;">#</th>
                    <th>Descripción</th>
                    <th class="text-center" style="width: 60px;">Cant.</th>
                    <th class="text-right" style="width: 80px;">P. Unit.</th>
                    <th class="text-center" style="width: 50px;">Desc.</th>
                    <th class="text-right" style="width: 80px;">Subtotal</th>
                    <th class="text-right" style="width: 80px;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($quotation->items as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->description }}</td>
                    <td class="text-center">{{ number_format($item->quantity, 2) }}</td>
                    <td class="text-right">{{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-center">{{ $item->discount_percent > 0 ? number_format($item->discount_percent, 0) . '%' : '-' }}</td>
                    <td class="text-right">{{ number_format($item->subtotal, 2) }}</td>
                    <td class="text-right">{{ number_format($item->total, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totales y Notas -->
        <div class="totals-container">
            <div class="totals-notes">
                @if($quotation->notes)
                <div class="notes-box">
                    <div class="notes-title">Notas:</div>
                    <div>{{ $quotation->notes }}</div>
                </div>
                @endif
                
                @if($quotation->terms)
                <div class="notes-box">
                    <div class="notes-title">Términos y Condiciones:</div>
                    <div>{{ $quotation->terms }}</div>
                </div>
                @endif
            </div>
            
            <div class="totals-box">
                <table class="totals-table">
                    <tr>
                        <td class="label">Subtotal:</td>
                        <td class="value">{{ $quotation->currency?->symbol ?? 'S/' }} {{ number_format($quotation->subtotal, 2) }}</td>
                    </tr>
                    @if($quotation->discount_total > 0)
                    <tr>
                        <td class="label">Descuento:</td>
                        <td class="value">- {{ $quotation->currency?->symbol ?? 'S/' }} {{ number_format($quotation->discount_total, 2) }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td class="label">IGV (18%):</td>
                        <td class="value">{{ $quotation->currency?->symbol ?? 'S/' }} {{ number_format($quotation->tax_total, 2) }}</td>
                    </tr>
                    <tr class="total-row">
                        <td class="label">TOTAL:</td>
                        <td class="value">{{ $quotation->currency?->symbol ?? 'S/' }} {{ number_format($quotation->total, 2) }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Validez -->
        <div class="validity-box">
            <strong>Esta cotización es válida por {{ $quotation->validity_days }} días desde la fecha de emisión.</strong>
        </div>
    </div>
</body>
</html>
