@php
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=Laporan Pengguna " . $akun->nik . "_" . $akun->username . "TANGSPOR ". date('d-m-Y') .".xls");

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

<p align='center'>LAPORAN PENGGUNA TANGSPOR</p>

<p>NIK: {{ $akun->nik }}</p>
<p>Nama Lengkap: {{ $akun->name }}</p>
<p>Username: {{ $akun->username }}</p>
<p>Email: {{ $akun->email }}</p>
<p>No. Telepon: {{ $akun->telp }}</p>
<p>Jabatan: {{ $akun->lvl }}</p>
<p>Status: {{ $akun->aktif == '0' ? 'Tidak Aktif' : 'Aktif' }}</p>

<table border="1" align="center">
    <tr>
        <th>No.</th>
        <th>Tanggal Kejadian</th>
        <th>Judul</th>
        <th>Isi Laporan</th>
        <th>Status</th>
        <th>foto</th>
    </tr>
    @foreach ($pengaduan as $index => $i)
        <tr height=100>
            <td style="text-align: center" width="50">'{{ $index + 1 }}'</td>
            <td style="text-align: center" width="80">'{{ $i->tgl_pengaduan }}'</td>
            <td style="text-align: center" width="300">'{{ $i->judul }}'</td>
            <td style="text-align: center" width="500">'{{ $i->isi_laporan }}'</td>
            <td style="text-align: center" width="80">'{{ statusPengaduan($i) }}'</td>
            <td style="text-align: center" width="200"><img width=100 src="{{ asset('img_pengaduan/' . $i->foto) }}" alt="foto_not_found.png"></td>
        </tr>
    @endforeach
</table>