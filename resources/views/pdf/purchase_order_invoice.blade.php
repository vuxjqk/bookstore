<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Purchase Order Invoice') }}</title>
    <style>
        @page {
            size: A4;
            margin: 1cm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            background: white;
        }

        .invoice {
            max-width: 210mm;
            margin: 0 auto;
            padding: 20px;
            min-height: 297mm;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }

        .logo-section {
            flex: 1;
        }

        .logo {
            width: 80px;
            height: 80px;
            border: 2px solid #333;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .company-info {
            font-size: 11px;
        }

        .company-info h3 {
            font-size: 14px;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .invoice-title {
            flex: 1;
            text-align: center;
        }

        .invoice-title h1 {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
            letter-spacing: 2px;
        }

        .invoice-number {
            font-size: 14px;
            font-weight: bold;
        }

        .supplier-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            gap: 40px;
        }

        .supplier-details,
        .order-details {
            flex: 1;
            border: 1px solid #ddd;
            padding: 15px;
            background-color: #f9f9f9;
        }

        .supplier-details h3,
        .order-details h3 {
            font-size: 14px;
            margin-bottom: 10px;
            border-bottom: 1px solid #333;
            padding-bottom: 5px;
        }

        .info-row {
            display: flex;
            margin-bottom: 8px;
        }

        .info-label {
            font-weight: bold;
            min-width: 80px;
        }

        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .products-table th {
            background-color: #333;
            color: white;
            padding: 12px 8px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #333;
        }

        .products-table td {
            padding: 10px 8px;
            border: 1px solid #ddd;
            vertical-align: top;
        }

        .products-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .summary {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 30px;
        }

        .summary-table {
            width: 300px;
            border-collapse: collapse;
        }

        .summary-table td {
            padding: 8px 12px;
            border: 1px solid #ddd;
        }

        .summary-table .label {
            font-weight: bold;
            background-color: #f5f5f5;
        }

        .summary-table .total-row {
            background-color: #333;
            color: white;
            font-weight: bold;
            font-size: 14px;
        }

        .footer {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }

        .notes,
        .signature {
            flex: 1;
            margin: 0 10px;
        }

        .notes h4,
        .signature h4 {
            font-size: 13px;
            margin-bottom: 10px;
            border-bottom: 1px solid #333;
            padding-bottom: 3px;
        }

        .signature-box {
            border: 1px dashed #333;
            height: 80px;
            margin-top: 10px;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            padding-bottom: 10px;
            font-style: italic;
        }

        .thank-you {
            text-align: center;
            margin-top: 30px;
            font-size: 14px;
            font-style: italic;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>
    <div class="invoice">
        <!-- Header -->
        <div class="header">
            <div class="logo-section">
                <div class="logo">LOGO</div>
                <div class="company-info">
                    <h3>{{ __('BOOKSTORE XYZ') }}</h3>
                    <div>{{ __('Address') }}: {{ __('123 ABC Street, District 1, Ho Chi Minh City') }}</div>
                    <div>{{ __('Phone') }}: {{ __('(028) 1234 5678') }}</div>
                    <div>{{ __('Email') }}: {{ __('info@bookstorexyz.com') }}</div>
                    <div>{{ __('Tax Code') }}: {{ __('0123456789') }}</div>
                </div>
            </div>

            <div class="invoice-title">
                <h1>{{ __('PURCHASE ORDER INVOICE') }}</h1>
                <div class="invoice-number">{{ __('No') }}:
                    {{ 'PO-' . date('Y') . '-' . str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</div>
            </div>
        </div>

        <!-- Supplier Information -->
        <div class="supplier-info">
            <div class="supplier-details">
                <h3>{{ __('SUPPLIER INFORMATION') }}</h3>
                <div class="info-row">
                    <span class="info-label">{{ __('Name') }}:</span>
                    <span>{{ $order->supplier->name ?? __('N/A') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">{{ __('Email') }}:</span>
                    <span>{{ $order->supplier->email ?? __('N/A') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">{{ __('Phone') }}:</span>
                    <span>{{ $order->supplier->phone ?? __('N/A') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">{{ __('Address') }}:</span>
                    <span>{{ $order->supplier->address ?? __('N/A') }}</span>
                </div>
            </div>

            <div class="order-details">
                <h3>{{ __('ORDER INFORMATION') }}</h3>
                <div class="info-row">
                    <span class="info-label">{{ __('Date') }}:</span>
                    <span>{{ $order->order_date->format('d/m/Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">{{ __('Order Code') }}:</span>
                    <span>{{ $order->purchase_order_code }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">{{ __('Status') }}:</span>
                    <span>
                        @switch($order->status)
                            @case('pending')
                                {{ __('Pending') }}
                            @break

                            @case('confirmed')
                                {{ __('Confirmed') }}
                            @break

                            @case('received')
                                {{ __('Received') }}
                            @break

                            @case('cancelled')
                                {{ __('Cancelled') }}
                            @break

                            @default
                                {{ __('N/A') }}
                        @endswitch
                        @if ($order->trashed())
                            ({{ __('Deleted') }})
                        @endif
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">{{ __('Notes') }}:</span>
                    <span>{{ $order->notes ?? __('N/A') }}</span>
                </div>
            </div>
        </div>

        <!-- Products Table -->
        <table class="products-table">
            <thead>
                <tr>
                    <th style="width: 50px;">{{ __('No') }}</th>
                    <th>{{ __('Book Title') }}</th>
                    <th style="width: 80px;" class="text-center">{{ __('Quantity') }}</th>
                    <th style="width: 100px;" class="text-right">{{ __('Unit Price') }}</th>
                    <th style="width: 120px;" class="text-right">{{ __('Subtotal') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $item->book->title }}</td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-right">{{ number_format($item->unit_price) }} ₫</td>
                        <td class="text-right">{{ number_format($item->subtotal) }} ₫</td>
                    </tr>
                @endforeach
                @for ($i = $order->items->count(); $i < 5; $i++)
                    <tr>
                        <td style="height: 20px; border: none;"></td>
                        <td style="border: none;"></td>
                        <td style="border: none;"></td>
                        <td style="border: none;"></td>
                        <td style="border: none;"></td>
                    </tr>
                @endfor
            </tbody>
        </table>

        <!-- Summary -->
        <div class="summary">
            <table class="summary-table">
                <tr>
                    <td class="label">{{ __('Subtotal') }}:</td>
                    <td class="text-right">{{ number_format($order->items->sum('subtotal')) }} ₫</td>
                </tr>
                <tr>
                    <td class="label">{{ __('VAT (10%)') }}:</td>
                    <td class="text-right">{{ number_format($order->items->sum('subtotal') * 0.1) }} ₫</td>
                </tr>
                <tr class="total-row">
                    <td>{{ __('Total') }}:</td>
                    <td class="text-right">{{ number_format($order->total_amount) }} ₫</td>
                </tr>
            </table>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="notes">
                <h4>{{ __('NOTES') }}</h4>
                <p>{{ __('- This invoice serves as proof of purchase.') }}</p>
                <p>{{ __('- Please verify items upon receipt.') }}</p>
                <p>{{ __('- Contact us for any discrepancies within 7 days.') }}</p>
                <p>{{ __('- Thank you for your business!') }}</p>
            </div>

            <div class="signature">
                <h4>{{ __('BUYER SIGNATURE') }}</h4>
                <div class="signature-box">
                    {{ __('(Sign and stamp)') }}
                </div>
                <div style="text-align: center; margin-top: 10px;">
                    <strong>{{ __('Nguyen Thi B') }}</strong><br>
                    <small>{{ __('Store Manager') }}</small>
                </div>
            </div>
        </div>

        <div class="thank-you">
            <strong>{{ __('Thank you for your cooperation!') }}</strong>
        </div>
    </div>
</body>

</html>
