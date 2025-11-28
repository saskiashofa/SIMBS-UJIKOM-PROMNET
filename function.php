<?php
// koneksi database
$conn = mysqli_connect("localhost", "root", "", "simbs");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// fungsi untuk menampilkan data dari database
function query($query) {
    global $conn;
    $result = mysqli_query($conn, $query);
    $rows = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

// fungsi untuk menambahkan buku ke database
function tambah_data($data){
    global $conn;
    $judul = htmlspecialchars($data['judul']);
    $id_kategori = htmlspecialchars($data['id_kategori']);
    $sinopsis = htmlspecialchars($data['sinopsis']);
    $penulis = htmlspecialchars($data['penulis']);
    $penerbit = htmlspecialchars($data['penerbit']);
    $tahun_terbit = htmlspecialchars($data['tahun_terbit']);

    // upload gambar
    $gambar = upload_gambar($judul);  // outputnya adalah nim_nama.eksentsi
    if( !$gambar ) {
        return false;
    }

    $query = "INSERT INTO buku 
              (judul, id_kategori, sinopsis, penulis, penerbit, tahun_terbit, gambar)
              VALUES 
              ('$judul', '$id_kategori', '$sinopsis', '$penulis', '$penerbit', '$tahun_terbit', '$gambar')";

    mysqli_query($conn, $query);
    return mysqli_affected_rows($conn);
}

// fungsi untuk menghapus buku dari database
function hapus_data($id) {
    global $conn;

    $query = "DELETE FROM buku WHERE id_buku = $id";
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

// fungsi untuk mengedit buku 
function ubah_data($data) {
    global $conn;

    $id         = $data["id_buku"];
    $judul      = htmlspecialchars($data["judul"]);
    $kategori   = $data["id_kategori"];
    $sinopsis   = htmlspecialchars($data["sinopsis"]);
    $penulis    = htmlspecialchars($data["penulis"]);
    $penerbit   = htmlspecialchars($data["penerbit"]);
    $tahun      = $data["tahun_terbit"];

    $gambarLama = $data['gambarLama'];
    $folder = 'dist/assets/img/';

    // apakah user upload gambar baru?
    if($_FILES['gambar']['error'] === 4){
        $gambar = $gambarLama;
    } else {

        // upload gambar baru
        $gambar = upload_gambar($judul);
        if(!$gambar){
            return false;
        }

        // hapus gambar lama
        if(file_exists($folder . $gambarLama)){
            unlink($folder . $gambarLama);
        }
    }

    $query = "UPDATE buku SET
                judul = '$judul',
                id_kategori = '$kategori',
                sinopsis = '$sinopsis',
                penulis = '$penulis',
                penerbit = '$penerbit',
                tahun_terbit = '$tahun',
                gambar = '$gambar'
              WHERE id_buku = $id";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

// fungsi search buku
function search_buku($keyword, $awalData, $jumlahDataPerHalaman){
    global $conn;

    $keyword = mysqli_real_escape_string($conn, $keyword);
    $query = "SELECT b.*, k.n_kategori
              FROM buku b
              JOIN kategori k ON b.id_kategori = k.id_kategori
              WHERE b.judul LIKE '%$keyword%'
              OR b.penulis LIKE '%$keyword%'
              OR b.penerbit LIKE '%$keyword%'
              OR k.n_kategori LIKE '%$keyword%'
              ORDER BY b.tanggal DESC LIMIT $awalData, $jumlahDataPerHalaman";

    return query($query);
}


// fungsi untuk upload gambar
function upload_gambar($judul) {

    // setting gambar
    $namaFile = $_FILES['gambar']['name'];
    $ukuranFile = $_FILES['gambar']['size'];
    $error = $_FILES['gambar']['error'];
    $tmpName = $_FILES['gambar']['tmp_name'];

    // cek apakah tidak ada gambar yang diupload
    if( $error === 4 ) {
        echo "<script>
                alert('pilih gambar terlebih dahulu!');
              </script>";
        return false;
    }

    // cek apakah yang diupload adalah gambar
    $ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
    $ekstensiGambar = explode('.', $namaFile);
    $ekstensiGambar = strtolower(end($ekstensiGambar));
    if( !in_array($ekstensiGambar, $ekstensiGambarValid) ) {
        echo "<script>
                alert('yang anda upload bukan gambar!');
              </script>";
        return false;
    }

    // cek jika ukurannya terlalu besar
    // maks --> 2MB
    if( $ukuranFile > 2000000 ) {
        echo "<script>
                alert('ukuran gambar terlalu besar!');
              </script>";
        return false;
    }

    // lolos pengecekan, gambar siap diupload
    // generate nama gambar baru
    $namaFileBaru = $judul;
    $namaFileBaru .= '.';
    $namaFileBaru .= $ekstensiGambar;

    move_uploaded_file($tmpName, 'dist/assets/img/' . $namaFileBaru);

    return $namaFileBaru;
}

// fungsi register 
function register($data) {
    global $conn;

    $username = strtolower(trim($data['username']));
    $email    = trim($data['email']);
    $password = mysqli_real_escape_string($conn, $data['password']);

    // Cek username sudah ada
    $cek = mysqli_query($conn, "SELECT username FROM user WHERE username = '$username'");
    if (mysqli_fetch_assoc($cek)) {
        return "Username sudah digunakan!";
    }

    $cek = mysqli_query($conn, "SELECT email FROM user WHERE email = '$email'");
    if (mysqli_fetch_assoc($cek)) {
        return "Email sudah digunakan!";
    }

    if (strlen($password) < 8) {
        return "Password minimal berjumlah 8 karakter!";
    }

    $password = password_hash($password, PASSWORD_DEFAULT);

    mysqli_query($conn,
        "INSERT INTO user (username, email, password)
         VALUES('$username', '$email', '$password')"
    );

    return true;
}

// fungsi login
function login($data) {
    global $conn;

    $username = $data['username'];
    $password = $data['password'];

    $result = mysqli_query($conn, "SELECT * FROM user WHERE username = '$username'");

    if(mysqli_num_rows($result) === 1){
        $row = mysqli_fetch_assoc($result);

        if(password_verify($password, $row['password'])){
            $_SESSION['login']    = true;
            $_SESSION['username'] = $row['username'];
            $_SESSION['email']    = $row['email'];
            return 1;
        } else {
            return "Password salah!";
        }
    }

    return "Username tidak ditemukan!";
}

// fungsi tambah kategori
function tambah_kategori($data){
    global $conn;
    $nama_k = htmlspecialchars($data['n_kategori']);

    $query = "INSERT INTO kategori (n_kategori) VALUES ('$nama_k')";

    mysqli_query($conn, $query);
    return mysqli_affected_rows($conn);
}

// fungsi untuk mengedit kategori 
function ubah_kategori($data) {
    global $conn;

    $id         = $data["id_kategori"];
    $nama_k     = htmlspecialchars($data["nama"]);
    $kategori   = $data["id_kategori"];

    $query = "UPDATE kategori SET
                n_kategori = '$nama_k'
              WHERE id_kategori= $id";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

// fungsi untuk menghapus buku dari database
function hapus_kategori($id) {
    global $conn;

    $query = "DELETE FROM kategori WHERE id_kategori = $id";
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

// fungsi search kategori
function search_kategori($keyword, $awalData, $jumlahDataPerHalaman){
    global $conn;

    $keyword = mysqli_real_escape_string($conn, $keyword);
    $query = "SELECT * FROM kategori
              WHERE n_kategori LIKE '%$keyword%'
              ORDER BY id_kategori DESC
              LIMIT $awalData, $jumlahDataPerHalaman";

    return query($query);
}

?>
