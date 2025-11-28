<?php

session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

require("function.php");

// ======================
// SEARCH (REQUEST METHOD: GET)
// ======================
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : "";

// pagination
    // konfigurasi
    $jumlahDataPerHalaman = 2;
    $jumlahData = count(query("SELECT * FROM kategori"));
    $jumlahHalaman = ceil($jumlahData / $jumlahDataPerHalaman);
    $halamanAktif = ( isset($_GET["halaman"]) ) ? $_GET["halaman"] : 1;
    $awalData = ( $jumlahDataPerHalaman * $halamanAktif ) - $jumlahDataPerHalaman;

// search kategori
$kategori = search_kategori($keyword, $awalData, $jumlahDataPerHalaman);
?>

<!doctype html>
<html lang="en">
  <!--begin::Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Sistem Informasi Manajemen Buku Sederhana</title>

    <!--begin::Accessibility Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta name="color-scheme" content="light dark" />
    <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
    <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />
    <!--end::Accessibility Meta Tags-->

    <!--begin::Primary Meta Tags-->
    <meta name="title" content="AdminLTE v4 | Dashboard" />
    <meta name="author" content="ColorlibHQ" />
    <meta
      name="description"
      content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS. Fully accessible with WCAG 2.1 AA compliance."
    />
    <meta
      name="keywords"
      content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard, accessible admin panel, WCAG compliant"
    />
    <!--end::Primary Meta Tags-->

    <!--begin::Accessibility Features-->
    <!-- Skip links will be dynamically added by accessibility.js -->
    <meta name="supported-color-schemes" content="light dark" />
    <link rel="preload" href="dist/css/adminlte.css" as="style" />
    <!--end::Accessibility Features-->

    <!--begin::Fonts-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
      integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
      crossorigin="anonymous"
      media="print"
      onload="this.media='all'"
    />
    <!--end::Fonts-->

    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css"
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(OverlayScrollbars)-->

    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(Bootstrap Icons)-->

    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="dist/css/adminlte.min.css" />
    <!--end::Required Plugin(AdminLTE)-->

    <!-- apexcharts -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
      integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0="
      crossorigin="anonymous"
    />

    <!-- jsvectormap -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css"
      integrity="sha256-+uGLJmmTKOqBr+2E6KDYs/NRsHxSkONXFHUL0fy2O/4="
      crossorigin="anonymous"
    />
  </head>
  <!--end::Head-->
  <!--begin::Body-->
  <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
      <!--begin::Header-->
      <nav class="app-header navbar navbar-expand bg-body">
        <div class="container-fluid">

          <!--begin::Start Navbar Links-->
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                <i class="bi bi-list"></i>
              </a>
            </li>
            <li class="nav-item d-none d-md-block">
              <a href="kategori.php" class="nav-link">Home</a>
            </li>
            <li class="nav-item d-none d-md-block">
              <a href="#" class="nav-link">Contact</a>
            </li>
          </ul>
          <!--end::Start Navbar Links-->

          <!--begin::End Navbar Links-->
          <ul class="navbar-nav ms-auto">
          <!-- Dropdown User -->
          <li class="nav-item dropdown">
              <a class="nav-link d-flex align-items-center" data-bs-toggle="dropdown" href="#" role="button">
                  <!-- Icon profil default -->
                  <i class="bi bi-person-circle fs-4 me-2"></i>
                  <!-- Username tampil di kanan icon -->
                  <span class="fw-bold">
                      <?= htmlspecialchars($_SESSION['username']); ?>
                  </span>
              </a>
              <!-- ISI DROPDOWN -->
              <div class="dropdown-menu dropdown-menu-end p-3" style="width: 250px;">
                  <!-- HEADER DROPDOWN -->
                  <div class="text-center mb-3">
                      <!-- Icon besar -->
                      <i class="bi bi-person-circle" style="font-size: 60px;"></i>
                      <h6 class="mt-2 mb-0"><?= htmlspecialchars($_SESSION['username']); ?></h6>
                      <small class="text-muted">
                        <?= htmlspecialchars($_SESSION['email']); ?>
                      </small>
                  </div>
                  <div class="d-grid gap-2">
                      <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
                  </div>
              </div>
          </li>

      </ul>
      <!--end::Menu Footer-->
        </ul>
        </li>
      <!--end::User Menu Dropdown-->
        </ul>
      <!--end::End Navbar Links-->
        </div>
        <!--end::Container-->
      </nav>     
      <!--end::Header-->
      <!--begin::Sidebar-->
      <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <!--begin::Sidebar Brand-->
        <div class="sidebar-brand">
          <!--begin::Brand Link-->
          <a href="index.php" class="brand-link">
            <!--begin::Brand Image-->
            <img
              src="dist/assets/img/AdminLTELogo.png"
              alt="AdminLTE Logo"
              class="brand-image opacity-75 shadow"
            />
            <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <span class="brand-text fw-light">SIMBS</span>
            <!--end::Brand Text-->
          </a>
          <!--end::Brand Link-->
        </div>
        <!--end::Sidebar Brand-->
        <!--begin::Sidebar Wrapper-->
        <div class="sidebar-wrapper">
          <nav class="mt-2">
            <!--begin::Sidebar Menu-->
            <ul
              class="nav sidebar-menu flex-column"
              data-lte-toggle="treeview"
              role="navigation"
              aria-label="Main navigation"
              data-accordion="false"
              id="navigation"
            >
              <li class="nav-item menu-open">
                <a href="#" class="nav-link active">
                  <i class="nav-icon bi bi-speedometer"></i>
                  <p>
                    Data Master
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="index.php" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Data Buku</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="kategori.php" class="nav-link active">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Data Kategori</p>
                    </a>
                  </li>
                </ul>
              </li>

              <li class="nav-header">AUTENTIKASI</li>
              <li class="nav-item">
                <a href="./generate/theme.html" class="nav-link">
                  <i class="nav-icon bi bi-palette"></i>
                  <p>Logout</p>
                </a>
              </li>

            </ul>
            <!--end::Sidebar Menu-->
          </nav>
        </div>
        <!--end::Sidebar Wrapper-->
      </aside>
      <!--end::Sidebar-->

      <!-- ====================================================================================== -->
      <!--  MAIN SECTION -->
      <!-- ====================================================================================== -->
      <!--begin::App Main-->
      <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <!--begin::Container-->
          <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
              <div class="col-sm-6">
                <h3 class="mb-3">Data Kategori</h3>
                <a href="tambah_kategori.php">
                  <button class="btn-sm btn btn-primary">Tambah Kategori</button>
                </a>
              </div>
              <div class="col-sm-6 d-flex flex-column align-items-end">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="kategori.php">Data Kategori</a></li>
                </ol>
                <form method="GET" class="mt-2">
                  <div class="input-group">
                      <input 
                          type="text" 
                          class="form-control" 
                          name="keyword" 
                          placeholder="Cari kategori..."
                          value="<?= $keyword; ?>"
                      >
                      <button class="btn btn-primary" type="submit">
                          <i class="bi bi-search"></i> Cari
                      </button>
                  </div>
                </form>
              </div>
            </div>
            <!--end::Row-->
          </div>
          <!--end::Container-->
        </div>
        <!--end::App Content Header-->

        <!--begin::App Content-->
        <div class="app-content">
          <!--begin::Container-->
          <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
              <!--begin::Col-->
              <div class="col">
              <!-- =============== ISI TABEL ADA DI SINI =============== -->
                <table class="table table-striped table-hover mt-3">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID</th>
                            <th>Nama Kategori</th>
                            <th>Tanggal Input</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php if (count($kategori) == 0): ?>
                        <tr><td colspan="4" class="text-center">Tidak ada data.</td></tr>
                    <?php endif; ?>

                    <?php $no = 1; foreach ($kategori as $row): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $row['id_kategori']; ?></td>
                            <td><?= htmlspecialchars($row['n_kategori']); ?></td>
                            <td><?= date('d-m-Y', strtotime($row['tanggal'])); ?></td>

                            <td>
                                <a href="ubah_kategori.php?id=<?= $row['id_kategori']; ?>" class="btn btn-success btn-sm">
                                    Edit
                                </a>

                                <a href="hapus_kategori.php?id=<?= $row['id_kategori']; ?>"
                                   onclick="return confirm('Yakin ingin menghapus?')"
                                   class="btn btn-danger btn-sm">
                                    Hapus
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <!-- Pagination -->
                <div class="row mt-3">
                <div class="col d-flex justify-content-center">
                    <nav aria-label="Page navigation example">
                        <ul class="pagination">
                            <?php if ($halamanAktif > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?halaman=<?= $halamanAktif - 1; ?>">&laquo;</a>
                                </li>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $jumlahHalaman; $i++): ?>
                                <li class="page-item <?= ($i == $halamanAktif ? 'active' : '') ?>">
                                    <a class="page-link" href="?halaman=<?= $i; ?>"><?= $i; ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($halamanAktif < $jumlahHalaman): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?halaman=<?= $halamanAktif + 1; ?>">&raquo;</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
                </div>
              </div>
              <!--end::Col-->
            </div>
            <!--end::Row-->
          </div>
          <!--end::Container-->
        </div>
        <!--end::App Content-->
      </main>
      <!--end::App Main-->
      <!--begin::Footer-->
      <footer class="app-footer">
        <!--begin::To the end-->
        <div class="float-end d-none d-sm-inline">Sistem Informasi Manajemen Buku Sederhana (SIMBS)</div>
        <!--end::To the end-->
        <!--begin::Copyright-->
        <strong>
          Copyright &copy; 2025&nbsp;
        </strong>
        <!--end::Copyright-->
      </footer>
      <!--end::Footer-->
    </div>
    <!--end::App Wrapper-->
    <!--begin::Script-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script
      src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"
      crossorigin="anonymous"
    ></script>
    <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
    <script src="dist/js/adminlte.js"></script>
    <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
   
  </body>
  <!--end::Body-->
</html>