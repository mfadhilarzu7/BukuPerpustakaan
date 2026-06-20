<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Buku Baru</title>
    <!-- Tambahkan CSS bootstrap atau styling Anda di sini jika diperlukan -->
    <style>
        body { font-family: sans-serif; margin: 40px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"] { width: 300px; padding: 8px; }
        button { padding: 8px 15px; cursor: pointer; }
    </style>
</head>
<body>

    <h2>Langkah 9 — Blade Form dengan JavaScript Auto-fill</h2>

    <!-- Form diarahkan ke route buku.store menggunakan method POST -->
    <form action="{{ route('buku.store') }}" method="POST">
        @csrf

        <!-- Input ISBN & Tombol Cari -->
        <div class="form-group">
            <label for="isbn">ISBN</label>
            <input type="text" id="isbn" name="isbn" placeholder="Scan atau ketik ISBN">
            <button type="button" onclick="fetchISBN()">Cari</button>
        </div>

        <!-- Input Judul -->
        <div class="form-group">
            <label for="judul">Judul Buku</label>
            <input type="text" id="judul" name="judul">
        </div>

        <!-- Input Penulis -->
        <div class="form-group">
            <label for="penulis">Penulis</label>
            <input type="text" id="penulis" name="penulis">
        </div>

        <!-- Preview Cover Buku -->
        <div class="form-group">
            <img id="cover_preview" src="" style="display:none; max-width:120px; border: 1px solid #ccc; margin-top: 10px;">
        </div>

        <br>
        <button type="submit" style="background-color: #28a745; color: white; border: none;">Simpan Buku</button>
    </form>

    <!-- JavaScript Auto-fill script dari image_befc88.png -->
    <script>
    async function fetchISBN() {
        const isbn = document.getElementById('isbn').value;
        if (!isbn) {
            alert('Silakan masukkan nomor ISBN terlebih dahulu');
            return;
        }

        const res = await fetch(`/api/isbn/${isbn}`);
        if (!res.ok) { 
            alert('Buku tidak ditemukan'); 
            return; 
        }
        const data = await res.json();

        // Mengisi otomatis field input berdasarkan response data API
        document.getElementById('judul').value = data.judul;
        document.getElementById('penulis').value = data.penulis;
        
        if (data.cover_url) {
            document.getElementById('cover_preview').src = data.cover_url;
            document.getElementById('cover_preview').style.display = 'block';
        } else {
            document.getElementById('cover_preview').style.display = 'none';
        }
    }
    </script>

</body>
</html>