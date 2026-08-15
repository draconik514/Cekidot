<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Arsip Surat - CEKIDOT</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
            color: #1e293b;
            padding: 32px;
            font-size: 13px;
        }
        .kop {
            text-align: center;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 3px solid #0f3b5e;
        }
        .kop .instansi { font-size: 18px; font-weight: 800; color: #0f3b5e; }
        .kop .nama { font-size: 20px; font-weight: 800; color: #eab308; letter-spacing: 1px; }
        .kop .sub { font-size: 11px; color: #64748b; margin-top: 4px; }
        .kop .line { width: 120px; height: 3px; background: #eab308; margin: 8px auto 0; border-radius: 3px; }
        .title {
            text-align: center;
            margin-bottom: 20px;
        }
        .title h2 { font-size: 16px; color: #0f3b5e; text-transform: uppercase; letter-spacing: 0.5px; }
        .title p { font-size: 12px; color: #64748b; margin-top: 4px; }
        .meta { margin-bottom: 16px; font-size: 12px; color: #475569; }
        .meta span { display: inline-flex; margin-right: 24px; }
        .meta strong { color: #0f3b5e; }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th {
            background: #0f3b5e;
            color: #fff;
            padding: 10px 8px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        table td {
            padding: 8px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 12px;
            vertical-align: top;
        }
        table tr:nth-child(even) td { background: #f8fafc; }
        .footer {
            margin-top: 32px;
            display: flex;
            justify-content: flex-end;
        }
        .ttd { text-align: center; font-size: 12px; }
        .ttd .kota { margin-bottom: 60px; }
        .ttd .nama { font-weight: 700; text-decoration: underline; margin-top: 4px; }
        .ttd .jabatan { font-size: 11px; color: #64748b; }
        .empty {
            text-align: center;
            padding: 40px;
            color: #94a3b8;
        }
        @media print {
            body { padding: 16px; }
        }
    </style>
</head>
<body>
    <div class="kop">
        <div class="instansi">DINAS PARIWISATA</div>
        <div class="nama">CEK<span style="color:#0f3b5e;">IDOT</span></div>
        <div class="sub">Cek IKU Dan Dokumen Terpadu</div>
        <div class="line"></div>
    </div>

    <div class="title">
        <h2>Laporan Arsip Surat</h2>
        <p>Bidang: {{ $bidangNama }}</p>
    </div>

    <div class="meta">
        <span>Total Arsip: <strong>{{ count($arsip) }}</strong></span>
        <span>Tanggal Cetak: <strong>{{ date('d M Y') }}</strong></span>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:30px;">#</th>
                <th>Nomor Surat</th>
                <th>Perihal</th>
                <th>Tanggal</th>
                <th>Jenis</th>
                <th>Bidang</th>
            </tr>
        </thead>
        <tbody>
            @if($arsip->isEmpty())
            <tr>
                <td colspan="6"><div class="empty">Tidak ada arsip untuk kriteria ini.</div></td>
            </tr>
            @else
            @php $no = 1; @endphp
            @foreach($arsip as $s)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $s->nomor_surat }}</td>
                <td>{{ $s->perihal }}</td>
                <td>{{ $s->tanggal_surat->format('d M Y') }}</td>
                <td>{{ ucfirst($s->jenis_surat) }}</td>
                <td>{{ $s->bidang?->nama_bidang ?? '-' }}</td>
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>

    <div class="footer">
        <div class="ttd">
            <div class="kota">Palu, {{ date('d F Y') }}</div>
            <div>Kepala Dinas Pariwisata</div>
            <div class="nama">______________________</div>
            <div class="jabatan">NIP. ________________</div>
        </div>
    </div>

    <script>window.print();</script>
</body>
</html>