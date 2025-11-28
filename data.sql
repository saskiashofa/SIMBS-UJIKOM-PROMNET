
USE SIMBS;

CREATE TABLE kategori(
  id_kategori INT PRIMARY KEY AUTO_INCREMENT,
  n_kategori VARCHAR(255) NOT NULL,
  tanggal TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO kategori (n_kategori) VALUES
('NOVEL'),
('DONGENG'),
('KOMIK');

CREATE TABLE buku (
  id_buku INT PRIMARY KEY AUTO_INCREMENT,
  judul VARCHAR(255) NOT NULL,
  sinopsis TEXT NOT NULL,
  id_kategori INT NOT NULL,
  penulis VARCHAR(255) NOT NULL,
  penerbit VARCHAR(255) NOT NULL,
  tahun_terbit INT NOT NULL,
  gambat VARCHAR(255) NOT NULL,
  tanggal TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (id_kategori) REFERENCES kategori(id_kategori)
);

INSERT INTO buku (judul, id_kategori, sinopsis, penulis, penerbit, tahun_terbit, gambar) VALUES
('Dilan: Dia adalah Dilanku Tahun 1990', 1, 'Dilan 1990 bercerita tentang kisah cinta antara Dilan dan Milea di Bandung pada tahun 1990. Milea, seorang siswi pindahan dari Jakarta, bertemu dengan Dilan, seorang siswa bandel yang terkenal di sekolahnya. Dilan punya cara unik untuk mendekati Milea, mulai dari memberikan tebak-tebakan lucu, puisi-puisi gombal, sampai tindakan-tindakan yang bikin Milea bingung sekaligus penasaran.', 'Pidi Baiq', 'Pastel Books', 2014, 'dilan.jpg'),
('Geez & Ann #3', 1, 'Geez kembali dari Berlin dengan kondisi baik dan siap untuk melanjutkan ceritanya dengan Ann. Semua yang Geez kira akan mudah dan indah, ternyata berbeda dengan Ann yang sudah mulai belajar merapikan hidupnya dan memulai cerita baru tanpa Geez sampai ketika Geez kembali hendak menjemput dan membawanya ke Berlin.', 'Rintik Sedu', 'Gagas Media', 2020, 'gezzan.jpg'),
('Koloni Si Unis 01', 3, 'Manusia itu memang menarik. Unis adalah kucing gembul yang sangat menyukai manusia. Si Unis sangat suka berkeliling komplek dan berinteraksi dengan berbagai macam manusia. Hal menarik apa yang akan Unis temui hari ini?', 'RINFAN / NARUCHACHA', 'M&C', 2020, 'unis.jpg'),
('Opredo Pop Up Book Dongeng Dunia: Putri Duyung', 2, 'Ariel Putri Duyung yang ceria, selalu penasaran dengan dunia di atas laut . Kesempatan pun datang, ia menuju ke permukaan. Di sana ia menemukan banyak hal yang menakjubkan, salah satunya adalah Pangeran Erik. Sejak hari itu, dimulailah petualangan Ariel yang tidak pernah dia duga sebelumnya.', 'Hafez Achda', 'Elex Media Komputindo', 2022, 'pd.jpg')
;

CREATE TABLE user (
  id_user INT PRIMARY KEY AUTO_INCREMENT,
  username VARCHAR(20) NOT NULL,
  email VARCHAR(100) NOT NULL,
  password VARCHAR(255) NOT NULL
);

COMMIT;
