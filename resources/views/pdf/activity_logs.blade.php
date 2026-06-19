<!DOCTYPE html>
<html>
<head>
    <title>Laporan Log Aktivitas</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; color: #0f172a; }
        .header p { margin: 5px 0; color: #64748b; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #cbd5e1; padding: 10px; text-align: left; }
        th { background-color: #f8fafc; font-weight: bold; text-transform: uppercase; color: #475569; }
        .status-sukses { color: #059669; font-weight: bold; }
        .status-gagal { color: #e11d48; font-weight: bold; }
        .footer { margin-top: 30px; text-align: right; font-size: 10px; color: #94a3b8; }
    </style>
</head>
<body>

    <div class="header">
        <h2>Laporan Aktivitas SmartBox IoT</h2>
        <p>Dicetak pada: {{ now()->format('d F Y, H:i') }} WIB</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Waktu</th>
                <th>Aktivitas</th>
                <th>Aktor</th>
                <th>Data / Resi</th>
                <th>Metode</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $index => $log)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $log->tanggal }}</td>
                    <td>{{ $log->aktivitas }}</td>
                    <td>{{ $log->aktor }}</td>
                    <td>{{ $log->data_input }}</td>
                    <td>{{ $log->metode }}</td>
                    <td class="{{ $log->status == 'Sukses' ? 'status-sukses' : 'status-gagal' }}">
                        {{ $log->status }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">Tidak ada data aktivitas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Generate by Sovereign OS System
    </div>

</body>
</html>