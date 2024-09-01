<!DOCTYPE html>
<html>
<head>
    <title>Pengaduan Baru</title>
</head>
<body>
    <h1>Pengaduan Baru Telah Diterima</h1>
    <p><strong>Judul:</strong> {{ $pengaduan->judul }}</p>
    <p><strong>Tanggal:</strong> {{ $pengaduan->tgl_pengaduan }}</p>
    <p><strong>Kecamatan:</strong> {{ $pengaduan->kecamatan->kecamatan }}</p>
    <p><strong>Isi:</strong> {{ $pengaduan->isi_laporan }}</p>
</body>
</html>
