@php
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=Laporan Pengaduan TANGSPOR ". date('d-m-Y') .".xls");

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

@if (isset($request->tgl_awal) && isset($request->tgl_akhir))
    <p>Dari: {{ $request->tgl_awal }}</p>
    <p>Hingga: {{ $request->tgl_akhir }}</p>
@endif

<table border="1" align="center">
    <tr>
        <th>No.</th>
        <th>NIK</th>
        <th>Tanggal</th>
        <th>Judul</th>
        <th>Isi Laporan</th>
        <th>Status</th>
        <th>foto</th>
    </tr>
    @foreach ($pengaduan as $index => $i)
        <tr height=100>
            <td style="text-align: center" width="50">'{{ $index + 1 }}'</td>
            <td style="text-align: center" width="80">'{{ $i->masyarakat_nik }}'</td>
            <td style="text-align: center" width="80">{{ $i->tgl_pengaduan }}</td>
            <td style="text-align: center" width="300">'{{ $i->judul }}'</td>
            <td style="text-align: center" width="500">'{{ $i->isi_laporan }}'</td>
            <td style="text-align: center" width="80">'{{ statusPengaduan($i) }}'</td>
            <td style="text-align: center" width="200"><img width=100 src="{{ asset('img_pengaduan/' . $i->foto) }}" alt="foto_not_found.png"></td>
        </tr>
    @endforeach
</table>