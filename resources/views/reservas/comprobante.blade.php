<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Comprobante Reserva #{{ $reservation->id }} | AutoAlquiler</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            font-size: 14px;
            color: #111827;
            background: #f9fafb;
            padding: 32px 16px;
        }

        /* ── Wrapper igual al max-w-3xl de show.blade.php ── */
        .wrap {
            max-width: 768px;
            margin: 0 auto;
        }

        /* ── Barra superior (solo visible en pantalla) ── */
        .top-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }
        .brand {
            font-size: 20px;
            font-weight: 700;
            letter-spacing: -0.02em;
        }
        .print-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            font-size: 13px;
            font-weight: 500;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            background: #fff;
            cursor: pointer;
            text-decoration: none;
            color: #111827;
        }
        .print-btn:hover { background: #f3f4f6; }

        /* ── Card principal (replica el bg-[var(--card)] border rounded) ── */
        .card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
        }

        /* ── Cabecera del card (border-b px-6 py-6) ── */
        .card-header {
            border-bottom: 1px solid #e5e7eb;
            padding: 24px;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
        }
        .card-header-info {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .reserva-num {
            font-size: 11px;
            font-weight: 500;
            color: #6b7280;
        }
        .vehiculo-nombre {
            font-size: 20px;
            font-weight: 700;
            color: #111827;
        }
        .vehiculo-cat {
            font-size: 14px;
            color: #6b7280;
        }
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 600;
            background: #111827;
            color: #ffffff;
            white-space: nowrap;
            flex-shrink: 0;
        }

        /* ── Cuerpo del card (px-6 py-6 space-y-6) ── */
        .card-body {
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        /* ── Título de sección ── */
        .section-title {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #6b7280;
            margin-bottom: 12px;
        }

        /* ── Tabla de detalles (replica el border rounded overflow-hidden) ── */
        .detail-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
            font-size: 14px;
        }
        .detail-table td {
            padding: 10px 16px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: middle;
        }
        .detail-table tr:last-child td {
            border-bottom: none;
        }
        .detail-table .lbl {
            background: #f9fafb;
            color: #6b7280;
            font-weight: 500;
            width: 140px;
        }
        .detail-table .val {
            font-weight: 600;
            color: #111827;
        }

        /* ── Tabla de extras ── */
        .extras-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
            font-size: 14px;
        }
        .extras-table td {
            padding: 10px 16px;
            border-bottom: 1px solid #e5e7eb;
        }
        .extras-table tr:last-child td {
            border-bottom: none;
        }
        .extras-table .right { text-align: right; font-weight: 500; }

        /* ── Total (replica border-t flex justify-between) ── */
        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
        }
        .total-label { font-weight: 700; color: #111827; }
        .total-amount { font-size: 24px; font-weight: 700; color: #111827; }

        /* ── Footer del documento ── */
        .doc-footer {
            margin-top: 24px;
            padding-top: 16px;
            border-top: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            color: #9ca3af;
        }

        /* ── Print ── */
        @media print {
            body { background: #fff; padding: 0; }
            .top-bar { display: none; }
            .card { border: none; box-shadow: none; border-radius: 0; }
            .card-header { padding: 16px 0; }
            .card-body { padding: 16px 0; }
            .detail-table, .extras-table { page-break-inside: avoid; }
            @page { margin: 1.5cm; size: A4; }
        }
    </style>
</head>
<body>
<div class="wrap">

    {{-- Barra superior (oculta al imprimir) --}}
    <div class="top-bar">
        <span class="brand">AutoAlquiler</span>
        <button class="print-btn" onclick="window.print()">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Imprimir
        </button>
    </div>

    {{-- Card principal ── idéntico a show.blade.php --}}
    <div class="card">

        {{-- Cabecera --}}
        <div class="card-header">
            <div class="card-header-info">
                <span class="reserva-num">Reserva #{{ $reservation->id }}</span>
                <span class="vehiculo-nombre">{{ $reservation->vehicle->brand }} {{ $reservation->vehicle->model }}</span>
                <span class="vehiculo-cat">{{ $reservation->vehicle->category->name }}</span>
            </div>
            <span class="badge">{{ ucfirst($reservation->status) }}</span>
        </div>

        {{-- Cuerpo --}}
        <div class="card-body">

            {{-- Detalle de la reserva --}}
            <div>
                <p class="section-title">Detalle de la reserva</p>
                <table class="detail-table">
                    <tbody>
                        <tr>
                            <td class="lbl">Inicio</td>
                            <td class="val">{{ $reservation->start_date->format('d/m/Y') }}</td>
                            <td class="lbl">Fin</td>
                            <td class="val">{{ $reservation->end_date->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <td class="lbl">Días</td>
                            <td class="val">{{ $reservation->start_date->diffInDays($reservation->end_date) }}</td>
                            <td class="lbl">Pasajeros</td>
                            <td class="val">{{ $reservation->passenger_count }}</td>
                        </tr>
                        <tr>
                            <td class="lbl">Precio / día</td>
                            <td class="val" colspan="3">$ {{ number_format($reservation->vehicle->price_per_day, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Extras --}}
            @if ($reservation->extras->isNotEmpty())
                <div>
                    <p class="section-title">Extras</p>
                    <table class="extras-table">
                        <tbody>
                            @foreach ($reservation->extras as $extra)
                                <tr>
                                    <td>{{ $extra->name }} × {{ $extra->pivot->quantity }}</td>
                                    <td class="right">$ {{ number_format($extra->price * $extra->pivot->quantity, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            {{-- Total --}}
            <div class="total-row">
                <span class="total-label">Total</span>
                <span class="total-amount">$ {{ number_format($reservation->total_cost, 2) }}</span>
            </div>

            {{-- Datos de entrega --}}
            @if (in_array($reservation->status, ['confirmada', 'completada']) && $reservation->delivery_plate)
                <div>
                    <p class="section-title">Datos de entrega</p>
                    <table class="detail-table">
                        <tbody>
                            <tr>
                                <td class="lbl">Placa</td>
                                <td class="val" style="font-family:monospace">{{ $reservation->delivery_plate }}</td>
                                <td class="lbl">Combustible</td>
                                <td class="val">{{ $reservation->delivery_fuel_level }}%</td>
                            </tr>
                            <tr>
                                <td class="lbl">Kilometraje</td>
                                <td class="val" colspan="3">{{ number_format($reservation->delivery_mileage) }} km</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endif

        </div>
    </div>

    {{-- Pie del documento --}}
    <div class="doc-footer">
        <span>Generado el {{ now()->format('d/m/Y \a \l\a\s H:i') }}</span>
    </div>

</div>
</body>
</html>
