<?php 
session_start();
if( !isset($_SESSION["login"]) ) {
    header("Location: login.php");
    exit;
}
    require("function.php");

    $id = $_GET['id'];

    // jalankan fungsi hapus_data() dari functions.php
    if(hapus_kategori($id) > 0){
        echo "
            <script>
                alert('Data berhasil dihapus dari database!');
                document.location.href = 'kategori.php';
            </script>
        ";
    }else{
        echo "
            <script>
                alert('Data gagal dihapus dari database!');
                document.location.href = 'kategori.php';
            </script>
        ";
    }
?>
