@php
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=Laporan Pengguna TANGSPOR ". date('d-m-Y') .".xls");

    function aktif($i)
    {
        switch ($i->trashed()) {
            case true:
                return "Tidak Aktif";
                break;
            
            case false:
                return "Aktif";
                break;

        }
    }
@endphp

<p align='center'>LAPORAN PENGGUNA DINKES</p>

@if ($lvl == 'admin' || $lvl == NULL)
    <p>TABEL ADMIN</p>
    <table border="1" align="center">
        <tr>
            <th>No.</th>
            <th>NIK</th>
            <th>Nama Lengkap</th>
            <th>Username</th>
            <th>Email</th>
            <th>No. Telepon</th>
            <th>Jabatan</th>
            <th>Status</th>
            <th>Jumlah Laporan</th>
        </tr>
        @foreach ($akun->where('lvl', '=', 'admin') as $index => $i)
            <tr height=100>
                <td style="text-align: center" width="50">'{{ $index + 1 }}'</td>
                <td style="text-align: center" width="80">'{{ $i->nik }}'</td>
                <td style="text-align: center" width="300">'{{ $i->name }}'</td>
                <td style="text-align: center" width="100">'{{ $i->username }}'</td>
                <td style="text-align: center" width="200">'{{ $i->email }}'</td>
                <td style="text-align: center" width="100">'{{ $i->telp }}'</td>
                <td style="text-align: center" width="100">'{{ $i->lvl }}'</td>
                <td style="text-align: center" width="80">'{{ aktif($i) }}'</td>
                <td style="text-align: center" width="100">'{{ $i->pengaduan->count() }}'</td>
            </tr>
        @endforeach
    </table>
@endif

@if ($lvl == 'petugas' || $lvl == NULL)
    <p>TABEL PETUGAS</p>
    <table border="1" align="center">
        <tr>
            <th>No.</th>
            <th>NIK</th>
            <th>Nama Lengkap</th>
            <th>Username</th>
            <th>Email</th>
            <th>No. Telepon</th>
            <th>Jabatan</th>
            <th>Status</th>
            <th>Jumlah Laporan</th>
        </tr>
        @php
            $no = 1;
        @endphp
        @foreach ($akun->where('lvl', '=', 'petugas') as $index => $i)
            <tr height=100>
                <td style="text-align: center" width="50">'{{ $no++ }}'</td>
                <td style="text-align: center" width="80">'{{ $i->nik }}'</td>
                <td style="text-align: center" width="300">'{{ $i->name }}'</td>
                <td style="text-align: center" width="100">'{{ $i->username }}'</td>
                <td style="text-align: center" width="200">'{{ $i->email }}'</td>
                <td style="text-align: center" width="100">'{{ $i->telp }}'</td>
                <td style="text-align: center" width="100">'{{ $i->lvl }}'</td>
                <td style="text-align: center" width="80">'{{ aktif($i) }}'</td>
                <td style="text-align: center" width="100">'{{ $i->pengaduan->count() }}'</td>
            </tr>
        @endforeach
    </table>
@endif

@if ($lvl == 'masyarakat' || $lvl == NULL)
<p>TABEL MASYARAKAT</p>
<table border="1" align="center">
    <tr>
        <th>No.</th>
        <th>NIK</th>
        <th>Nama Lengkap</th>
        <th>Username</th>
        <th>Email</th>
        <th>No. Telepon</th>
        <th>Jabatan</th>
        <th>Status</th>
        <th>Jumlah Laporan</th>
    </tr>
    @php
        $no = 1;
    @endphp
    @foreach ($akun->where('lvl', '=', 'masyarakat') as $index => $i)
        <tr height=100>
            <td style="text-align: center" width="50">'{{ $no++ }}'</td>
            <td style="text-align: center" width="80">'{{ $i->nik }}'</td>
            <td style="text-align: center" width="300">'{{ $i->name }}'</td>
            <td style="text-align: center" width="100">'{{ $i->username }}'</td>
            <td style="text-align: center" width="200">'{{ $i->email }}'</td>
            <td style="text-align: center" width="100">'{{ $i->telp }}'</td>
            <td style="text-align: center" width="100">'{{ $i->lvl }}'</td>
            <td style="text-align: center" width="80">'{{ aktif($i) }}'</td>
            <td style="text-align: center" width="100">'{{ $i->pengaduan->count() }}'</td>
        </tr>
    @endforeach
</table>
@endif