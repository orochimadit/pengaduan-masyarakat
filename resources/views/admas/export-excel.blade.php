@php
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=Laporan Pengaduan E-LAPOR SEHATI ". date('d-m-Y') .".xls");

    function statusPengaduan($i)
    {
        switch ($i->status) {
            case '0':
                return 'Belum Diproses';
            break;
            
            case '1':
                return 'Sedang Diproses';
            break;
            
            case '2':
                return 'Selesai';
            break;
        }
    }
@endphp

<p align='center'>LAPORAN PENGADUAN E-LAPOR SEHATI</p>

@if (isset($request->bulan) && isset($request->tahun))
    <p>Bulan: {{ date('F', mktime(0, 0, 0, $request->bulan, 10)) }}</p>
    <p>Tahun: {{ $request->tahun }}</p>
@endif

<table border="1" align="center">
    <tr>
        <th>No.</th>
        <th>Tanggal Lapor</th>
        <th>Tanggal Selesai</th>
        <th>Judul</th>
        <th>Isi Laporan</th>
        <th>Status</th>
        <th>No HP</th>
    </tr>
    @foreach ($pengaduan as $index => $i)
        <tr height=100>
            <td style="text-align: center" width="50">{{ $index + 1 }}</td>
            <td style="text-align: center" width="80">{{ $i->tgl_pengaduan }}</td>
            <td style="text-align: center" width="80">{{ $i->tgl_selesai }}</td>
            <td style="text-align: center" width="300">{{ $i->judul }}</td>
            <td style="text-align: center" width="500">{{ $i->isi_laporan }}</td>
            <td style="text-align: center" width="80">{{ statusPengaduan($i) }}</td>
            <td style="text-align: center" width="80">+62{{ $i->no_hp }}</td>
        </tr>
    @endforeach
</table>
