<?php 

    session_start();
    if( !isset($_SESSION["login"]) ) {
        header("Location: login.php");
        exit;
    }

    require("function.php");

    $id_kategori = $_GET['id'];

        $nama = query("SELECT * FROM kategori WHERE id_kategori = $id_kategori")[0];
        $kategori = query("SELECT * FROM kategori"); 

        if(isset($_POST['tombol_submit'])){
            
            if(ubah_kategori($_POST) > 0){
                echo "
                    <script>
                        alert('Data buku berhasil diubah di database!');
                        document.location.href = 'kategori.php';
                    </script>
                ";
            } else {
                echo "
                    <script>
                        alert('Tidak ada perubahan data / gagal update!');
                        document.location.href = 'kategori.php';
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
    <title>Ubah Data Kategori</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container p-4">
    <h2>Ubah Data Kategori</h2>
    <a href="kategori.php">Kembali</a>

    <div class="col-md-6 mt-3">
        <form action="" method="post">
            <input type="hidden" name="id_kategori" value="<?= $nama['id_kategori']; ?>">
                    <div class="mb-3">
                        <label class="form-label">Nama Kategori</label>
                        <input type="text" class="form-control" name="nama" placeholder="Nama Kategori" value="<?= $nama['n_kategori']; ?>">
                    </div>
                    <div class="mb-3">
                        <button class="btn-sm btn btn-primary" name="tombol_submit">Submit</button>
                    </div>
                </form>
    </div>
</div>

</body>
</html>