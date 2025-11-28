<?php
// ======================
// SESSION (aktifkan bila sudah buat login)
// ======================
// session_start();
// if (!isset($_SESSION['login'])) {
//     header("Location: login.php");
//     exit;
// }

// ======================
// KONEKSI DATABASE
// ======================
$conn = mysqli_connect("localhost", "root", "", "simbs");
if (!$conn) die("Koneksi gagal: " . mysqli_connect_error());

// ======================
// PAGINATION SETTINGS
// ======================
$limit = 5; // jumlah data per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// ======================
// SEARCH
// ======================
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : "";

if ($keyword != "") {
    $stmt = $conn->prepare("
        SELECT b.*, k.n_kategori 
        FROM buku b
        JOIN kategori k ON b.id_kategori = k.id_kategori
        WHERE b.judul LIKE ? 
           OR b.penulis LIKE ?
           OR b.penerbit LIKE ?
           OR k.n_kategori LIKE ?
        ORDER BY b.tanggal DESC
        LIMIT ? OFFSET ?
    ");

    $param = "%$keyword%";
    $stmt->bind_param("ssssii", $param, $param, $param, $param, $limit, $offset);

} else {
    $stmt = $conn->prepare("
        SELECT b.*, k.n_kategori 
        FROM buku b
        JOIN kategori k ON b.id_kategori = k.id_kategori
        ORDER BY b.tanggal DESC
        LIMIT ? OFFSET ?
    ");

    $stmt->bind_param("ii", $limit, $offset);
}

$stmt->execute();
$result = $stmt->get_result();

// Array untuk tabel
$produk = [];
while ($row = $result->fetch_assoc()) {
    $produk[] = $row;
}

// ======================
// HITUNG TOTAL DATA
// ======================
if ($keyword != "") {
    $stmtCount = $conn->prepare("
        SELECT COUNT(*) as total
        FROM buku b
        JOIN kategori k ON b.id_kategori = k.id_kategori
        WHERE b.judul LIKE ? 
           OR b.penulis LIKE ?
           OR b.penerbit LIKE ?
           OR k.n_kategori LIKE ?
    ");
    $param = "%$keyword%";
    $stmtCount->bind_param("ssss", $param, $param, $param, $param);
} else {
    $stmtCount = $conn->prepare("SELECT COUNT(*) as total FROM buku");
}

$stmtCount->execute();
$total = $stmtCount->get_result()->fetch_assoc()['total'];
$totalPages = ceil($total / $limit);

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Sistem Informasi Manajemen Buku Sederhana</title>

    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- AdminLTE -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css" />

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css" />

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" />

</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
<div class="app-wrapper">

    <!-- NAVBAR -->
    <nav class="app-header navbar navbar-expand bg-body">
        <div class="container-fluid">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-lte-toggle="sidebar" href="#">
                        <i class="bi bi-list"></i>
                    </a>
                </li>
                <li class="nav-item d-none d-md-block">
                    <a href="#" class="nav-link">Home</a>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link">
                        <i class="bi bi-person-circle"></i>
                        <!-- <?= $_SESSION['username'] ?> -->
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- SIDEBAR -->
    <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <div class="sidebar-brand">
            <a href="index.php" class="brand-link">
                <img src="dist/assets/img/AdminLTELogo.png" class="brand-image opacity-75" />
                <span class="brand-text fw-light">SIMBS</span>
            </a>
        </div>

        <div class="sidebar-wrapper">
            <nav class="mt-2">
                <ul class="nav sidebar-menu flex-column">

                    <li class="nav-item menu-open">
                        <a href="#" class="nav-link active">
                            <i class="nav-icon bi bi-book"></i>
                            <p>
                                Data Master
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="index.php" class="nav-link active">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>Data Buku</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="kategori.php" class="nav-link">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>Data Kategori</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-header">AUTENTIKASI</li>
                    <li class="nav-item">
                        <a href="logout.php" class="nav-link">
                            <i class="nav-icon bi bi-box-arrow-right"></i>
                            <p>Sign Out</p>
                        </a>
                    </li>

                </ul>
            </nav>
        </div>
    </aside>

    <!-- MAIN -->
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-sm-6">
                        <h3>Data Buku</h3>
                        <a href="tambah_data.php"><button class="btn btn-primary btn-sm">Tambah Buku</button></a>
                    </div>

                    <div class="col-sm-6 d-flex flex-column align-items-end">
                        <form class="mt-2">
                            <div class="input-group">
                                <input type="text" name="keyword" class="form-control" placeholder="Cari buku..." value="<?= htmlspecialchars($keyword) ?>">
                                <button class="btn btn-primary"><i class="bi bi-search"></i> Cari</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>

        <!-- TABEL -->
        <div class="app-content">
            <div class="container-fluid">

                <table class="table table-striped table-hover mt-3">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>ID Buku</th>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Sinopsis</th>
                            <th>Penulis</th>
                            <th>Penerbit</th>
                            <th>Tahun</th>
                            <th>Tanggal Input</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (count($produk) == 0): ?>
                            <tr><td colspan="10" class="text-center">Tidak ada data.</td></tr>
                        <?php endif; ?>

                        <?php $no = $offset + 1; foreach ($produk as $row): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $row['id_buku']; ?></td>
                            <td><?= htmlspecialchars($row['judul']); ?></td>
                            <td><?= $row['n_kategori']; ?></td>
                            <td style="text-align: justify;"><?= $row['sinopsis']; ?></td>
                            <td><?= $row['penulis']; ?></td>
                            <td><?= $row['penerbit']; ?></td>
                            <td><?= $row['tahun_terbit']; ?></td>
                            <td><?= date("d-m-Y", strtotime($row['tanggal'])); ?></td>

                            <td>
                                <a href="ubah_data.php?id=<?= $row['id_buku']; ?>" class="btn btn-success btn-sm">Edit</a>
                                <a href="hapus.php?id=<?= $row['id_buku']; ?>" class="btn btn-danger btn-sm"
                                   onclick="return confirm('Hapus buku ini?')">Hapus</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>

                </table>

                <!-- PAGINATION -->
                <nav>
                    <ul class="pagination justify-content-center">

                        <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $page-1 ?>&keyword=<?= $keyword ?>">Prev</a>
                        </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= ($i == $page ? 'active' : '') ?>">
                            <a class="page-link" href="?page=<?= $i ?>&keyword=<?= $keyword ?>"><?= $i ?></a>
                        </li>
                        <?php endfor; ?>

                        <?php if ($page < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $page+1 ?>&keyword=<?= $keyword ?>">Next</a>
                        </li>
                        <?php endif; ?>

                    </ul>
                </nav>

            </div>
        </div>

    </main>

    <footer class="app-footer">
        <div class="float-end d-none d-sm-inline">SIMBS</div>
        <strong>&copy; 2025</strong>
    </footer>

</div>

</body>
</html>
