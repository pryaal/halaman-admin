<?php include("inc_header.php") ?>

<?php
$judul = "";
$gambar = "";
$error = "";
$sukses = "";

if (isset($_GET["id"])) {
    $id = $_GET["id"];
} else {
    $id = "";
}

if ($id != "") {
    $sql1 = "SELECT * FROM halaman WHERE id = '$id'";
    $q1 = mysqli_query($koneksi, $sql1);
    $row1 = mysqli_fetch_array($q1);
    if ($row1) {
        $judul = $row1['judul'];
        $gambar = $row1['gambar'];
    } else {
        $error = "Data tidak ditemukan";
    }
}

if (isset($_POST["simpan"])) {
    $judul = $_POST["judul"];
    $gambar = $_FILES["gambar"]["name"];
    $gambar_tmp = $_FILES["gambar"]["tmp_name"];

    if ($judul == "" || $gambar == "") {
        $error = "Silahkan masukkan semua data ya teman.";
    } else {
        if ($id != "") {
            // Update
            $sql1 = "UPDATE halaman SET judul = '$judul', gambar = '$gambar', tgl_isi = NOW() WHERE id = '$id'";
        } else {
            // Insert
            $sql1 = "INSERT INTO halaman (judul, gambar) VALUES ('$judul', '$gambar')";
        }

        $q1 = mysqli_query($koneksi, $sql1);
        if ($q1) {
            // Upload file
            move_uploaded_file($gambar_tmp, "path/to/your/uploads/folder/$gambar");
            $sukses = "Sukses memasukkan data.";
        } else {
            $error = "Ada kesalahan, coba masukkan data yang benar.";
        }
    }
}
?>

<h1>Halaman Admin Input Data</h1>
<div class="mb-3 row">
    <a href="halaman.php"> << Kembali ke Halaman Admin </a>
</div>

<?php if ($error) { ?>
    <div class="alert alert-danger" role="alert">
        <?php echo $error ?>
    </div>
<?php } ?>

<?php if ($sukses) { ?>
    <div class="alert alert-primary" role="alert">
        <?php echo $sukses ?>
    </div>
<?php } ?>

<form action="" method="post" enctype="multipart/form-data">
    <div class="mb-3 row">
        <label for="judul" class="col-sm-2 col-form-label">Judul</label>
        <div class="col-sm-4">
            <input type="text" class="form-control" id="judul" value="<?php echo $judul ?>" name="judul"/>
        </div>
    </div>

    <div class="mb-3 row">
        <label for="gambar" class="col-sm-2 col-form-label">Gambar</label>
        <div class="col-sm-4">
            <input type="file" class="form-control" id="gambar" name="gambar"/>
        </div>
    </div>

    <div class="mb-3 row">
        <div class="col-sm-2"></div>
        <div class="col-sm-10">
            <input type="submit" name="simpan" value="Simpan Data" class="btn btn-primary"/>
        </div>
    </div>
</form>

<?php include("inc_footer.php") ?>
