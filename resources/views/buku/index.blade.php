<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Buku</title>
    <style>
        body { font-family: sans-serif; margin: 40px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; }
        .alert { padding: 10px; background-color: #d4edda; color: #155724; margin-bottom: 15px; }
    </style>
</head>
<body>

    <h2>Daftar Buku Perpustakaan</h2>

    @if(session('success'))
        <div class="alert">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('buku.create') }}" style="padding: 10px; background: #007bff; color: white; text-decoration: none;">+ Tambah Buku Baru</a>

    <table>
        <thead>
            <tr>
                <th>ISBN</th>
                <th>Judul</th>
                <th>Penulis</th>
                <th>Stok</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bukus as $buku)
                <tr>
                    <td>{{ $buku->isbn }}</td>
                    <td>{{ $buku->judul }}</td>
                    <td>{{ $buku->penulis }}</td>
                    <td>{{ $buku->stok }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center;">Belum ada data buku.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>