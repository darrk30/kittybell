<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ticket de Venta</title>
    <style>
        @page { margin: 10px; }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 0;
            text-align: center;
        }
        .logo {
            width: 120px;
            margin: 0 auto 5px auto;
        }
        .title {
            font-weight: bold;
            font-size: 13px;
            margin-bottom: 2px;
        }
        .subtitle {
            font-size: 11px;
            margin-bottom: 2px;
        }
        table {
            width: 100%;
        }
        th, td {
            text-align: left;
        }
        th {
            text-align: center;
            font-weight: bold;
        }
        .right {
            text-align: right;
        }
        .total {
            font-weight: bold;
        }
        .footer {
            margin-top: 10px;
            text-align: center;
            font-size: 10px;
        }
    </style>
</head>
<body>
    {{-- LOGO --}}
    <img src="{{ public_path('images/logoKittyBell.png') }}" alt="Logo" class="logo">

    {{-- CABECERA --}}
    <div class="title">{{ $sale->document_type }}</div>
    <div class="title">{{ $sale->series }}-{{ str_pad($sale->correlative, 6, '0', STR_PAD_LEFT) }}</div>
    <div class="subtitle">Lambayeque - Chiclayo</div>

    <hr style="border-top:1px  #000;">

    {{-- DATOS DEL CLIENTE --}}
    <table>
        <tr>
            <td><strong>Cliente:</strong></td>
            <td>{{ $sale->client->name ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>DNI:</strong></td>
            <td>{{ $sale->client->document_number ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Teléfono:</strong></td>
            <td>{{ $sale->client->phone ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Fecha:</strong></td>
            <td>{{ $sale->created_at->format('d/m/Y H:i') }}</td>
        </tr>
    </table>

    <hr style="border-top:1px #000;">

    {{-- DETALLE DE PRODUCTOS --}}
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th class="right">Cant.</th>
                <th class="right">Precio</th>
                <th class="right">Importe</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sale->details as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td class="right">{{ $item->quantity }}</td>
                    <td class="right">S/ {{ number_format($item->price, 2) }}</td>
                    <td class="right">S/ {{ number_format($item->subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <hr style="border-top:1px  #000;">

    {{-- TOTALES --}}
    <table>
        <tr>
            <td><strong>Descuento Total:</strong></td>
            <td class="right">S/ {{ number_format($sale->discount ?? 0, 2) }}</td>
        </tr>
        <tr>
            <td class="total">TOTAL VENTA:</td>
            <td class="right total">S/ {{ number_format($sale->total_amount, 2) }}</td>
        </tr>
    </table>

    <div class="footer">
        ¡Gracias por su compra ;)!
    </div>
</body>
</html>
