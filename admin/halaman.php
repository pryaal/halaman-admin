<?php include("inc_header.php") ?>
<?php
$sukses = "";
$katakunci = (isset($_GET["katakunci"])) ? $_GET["katakunci"] : "";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = '';
}

if ($op == 'delete') {
    $id = $_GET['id'];
    $sql1 = "DELETE FROM halaman WHERE id = '$id'";
    $q1 = mysqli_query($koneksi, $sql1);
    if ($q1) {
        $sukses = "Data Berhasil Dihapus";
    }
}
?>
<h1>Halaman Admin</h1>
<p>
    <a href="halaman_input.php">
        <input type="button" class="btn btn-primary" value="Buat Halaman Baru"/>
    </a>
</p>
<?php
if ($sukses) {
    ?>
    <div class="alert alert-primary" role="alert">
        <?php echo $sukses ?>
    </div>
    <?php
}
?>
<form class="row g-3" method="get">
    <div class="col-auto">
        <input type="text" class="form-control" placeholder="Masukan kata kunci" name="katakunci" value="<?php echo $katakunci ?>"/>
    </div>
    <div class="col-auto">
        <input type="submit" name="cari" value="Cari Tulisan" class="btn btn-secondary"/>
    </div>
</form>
<table class="table table-striped">
    <thead>
        <tr>
            <th class="col-1">#</th>
            <th>Judul</th>
            <th>Gambar</th>
            <th class="col-2">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sqltambah = "";
        $per_halaman = 2;

        if ($katakunci != '') {
            $array_katakunci = explode(' ', $katakunci);
            for ($x = 0; $x < count($array_katakunci); $x++) {
                $sqlcari[] = "(judul LIKE '%" . $array_katakunci[$x] . "%' OR gambar LIKE '%" . $array_katakunci[$x] . "%')";
            }
            $sqltambah = "WHERE " . implode(" OR ", $sqlcari);
        }

        $sql1 = "SELECT * FROM halaman $sqltambah ORDER BY id DESC";
        $q1 = mysqli_query($koneksi, $sql1);
        $total = mysqli_num_rows($q1);
        $pages = ceil($total / $per_halaman);
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $mulai = ($page > 1) ? ($page * $per_halaman) - $per_halaman : 0;

        $sql1 = $sql1 . " LIMIT $mulai, $per_halaman";
        $q1 = mysqli_query($koneksi, $sql1);
        $nomor = $mulai + 1;

        while ($row1 = mysqli_fetch_array($q1)) {
            ?>
            <tr>
                <td><?php echo $nomor++ ?></td>
                <td><?php echo $row1['judul'] ?></td>
                <td><?php echo $row1['gambar'] ?></td>
                <td>
                    <a href="halaman_input.php?id=<?php echo $row1['id'] ?>">
                        <span class="badge rounded-pill bg-warning text-dark">Edit</span>
                    </a>
                    <a href="halaman.php?op=delete&id=<?php echo $row1['id'] ?>" onclick="return confirm('Apakah Anda yakin mau hapus data ini kawan?')">
                    <span class="badge rounded-pill bg-danger">Delete</span>
                    </a>
                </td>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>
<nav aria-label="Page navigation example">
    <ul class="pagination">
        <?php
        $cari = (isset($_GET['cari'])) ? $_GET['cari'] : "";
        for ($i = 1; $i <= $pages; $i++) {
            ?>
            <li class="page-item<?php if ($page == $i) echo ' active'; ?>">
                <a class="page-link" href="halaman.php?katakunci=<?php echo $katakunci ?>&cari=<?php echo $cari ?>&page=<?php echo $i ?>"><?php echo $i ?></a>
            </li>
            <?php
        }
        ?>
    </ul>
</nav>
<?php include("inc_footer.php") ?>
