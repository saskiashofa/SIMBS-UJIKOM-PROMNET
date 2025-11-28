<?php 
session_start();
if( !isset($_SESSION["login"]) ) {
    header("Location: login.php");
    exit;
}

    require("function.php");

    $id_buku = $_GET['id'];

        $buku = query("SELECT * FROM buku WHERE id_buku = $id_buku")[0];
        $kategori = query("SELECT * FROM kategori"); 

        if(isset($_POST['tombol_submit'])){
            
            if(ubah_data($_POST) > 0){
                echo "
                    <script>
                        alert('Data buku berhasil diubah di database!');
                        document.location.href = 'index.php';
                    </script>
                ";
            } else {
                echo "
                    <script>
                        alert('Tidak ada perubahan data!');
                        document.location.href = 'index.php';
                    </script>
                ";
        }

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Data Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container p-4">
    <h2>Ubah Data Buku</h2>
    <a href="index.php">Kembali</a>

    <div class="col-md-6 mt-3">
        <form action="" method="post" enctype="multipart/form-data">

        <input type="hidden" name="id_buku" value="<?= $buku['id_buku']; ?>">

                    <div class="mb-3">
                        <label class="form-label">Judul</label>
                        <input type="text" class="form-control" name="judul" placeholder="Judul Buku" value="<?= $buku["judul"]; ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Kategori</label>
                        <select name="id_kategori" class="form-select" required>
                            <option value="">--- Pilih Kategori ---</option>
                            <?php foreach($kategori as $row): ?>
                                <option value="<?= $row['id_kategori']; ?>" 
                                    <?= $buku['id_kategori'] == $row['id_kategori'] ? 'selected' : ''; ?>><?= $row['n_kategori']; ?>
                                </option>
                            <?php endforeach; ?>
                            <!-- <?php foreach($kategori as $row): ?>
                                <option name="kategori" value="<?= $row['id_kategori']; ?>"><?= $row['n_kategori']; ?></option>
                            <?php endforeach; ?> -->
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Sinopsis</label>
                        <textarea class="form-control" name="sinopsis" rows="4"><?= $buku['sinopsis']; ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Penulis</label>
                        <input type="text" name="penulis" placeholder="Penulis" class="form-control" value="<?= $buku['penulis']; ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Penerbit</label>
                        <input type="text" name="penerbit" placeholder="Penerbit" class="form-control" value="<?= $buku['penerbit']; ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Tahun Terbit</label>
                        <input type="number" name="tahun_terbit" placeholder="Tahun Terbit" class="form-control" value="<?= $buku['tahun_terbit']; ?>">
                    </div>

                    <div class="mb-3">
                          <label>Gambar</label><br>

                          <!-- tampilkan gambar lama -->
                          <img src="img/<?= $buku['gambar']; ?>" width="120" class="mb-2">

                          <!-- simpan nama file lama -->
                          <input type="hidden" name="gambarLama" value="<?= $buku['gambar']; ?>">

                          <!-- input file untuk upload baru -->
                          <input type="file" class="form-control" name="gambar">
                      </div>

                    <!-- <div class="mb-3">
                        <label class="form-label fw-bold">Gambar</label>
                        <input type="file" class="form-control mb-2" name="gambar">
                    </div> -->

                    <div class="mb-3">
                        <button class="btn-sm btn btn-primary" name="tombol_submit">Submit</button>
                    </div>
                </form>
    </div>
</div>

</body>
</html>