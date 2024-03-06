<?php
$host       = "localhost";
$user       = "root";
$pass       = "";
$db         = "akademik";

$koneksi  = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) { //cek koneksi
    die("tidak bisa terkoneksi ke database");
}
$nim        = "";
$nama       = "";
$alamat     = "";
$fakultas   = "";
$sukses     = "";
$eror       = "";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}
if($op == 'delete'){
    $id   = $_GET['id'];
    $sql1  = "delete from mahasiswa where id = '$id'";
    $q1     = mysqli_query($koneksi,$sql1);
    if($q1){
        $sukses = "Berhasil hapus data";
    }else{
        $eror = "Gagal melakukan delete";
    }
}
if ($op == 'edit') {
    $id       = $_GET['id'];
    $sql1     = "select * from mahasiswa where id = '$id'";
    $q1       = mysqli_query($koneksi, $sql1);
    $r1       = mysqli_fetch_array($q1);
    $nim      = $r1['nim'];
    $nama     = $r1['nama'];
    $alamat   = $r1['alamat'];
    $fakultas = $r1['fakultas'];

    if ($nim == '') {
        $eror = "data tidak di temukan";
    }
}

if (isset($_POST['simpan'])) {
    $nim       = $_POST['nim'];
    $nama      = $_POST['nama'];
    $alamat    = $_POST['alamat'];
    $fakultas  = $_POST['fakultas'];

    if ($nim && $nama && $alamat && $fakultas) {
        if ($op == 'edit') {//untuk di update
            $sql1 = "update mahasiswa set nim ='$nim',nama='$nama',alamat='$alamat',fakultas='$fakultas' where id = '$id'";
            $q1  = mysqli_query($koneksi, $sql1);
            if ($q1) {
                $sukses = "Data berhasil diupdate";
            } else {
                $eror = "Data gagal diupdate";
            }
        } else {// untuk insert
            $sql1  = "insert into mahasiswa(nim,nama,alamat,fakultas) values ('$nim','$nama','$alamat','$fakultas')";
            $q1    = mysqli_query($koneksi, $sql1);
            if ($q1) {
                $sukses    = "Berhasil memasukan data";
            } else {
                $eror      = "Gagal memasukan data";
            }
        }
    } else {
        $eror = "silakan masukan semua data";
    }
}

?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>data mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .mx-auto {
            width: 800px
        }

        .card {
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="mx-auto">
        <!-- unutk memasukan data -->
        <div class="card">
            <div class="card-header">
                DATA MAHASISWA
            </div>
            <div class="card-body">
                <?php
                if ($eror) {
                ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $eror ?>
                    </div>
                <?php
                header("refresh:8;url=index.php");//8 : detik
                }
                ?>
                <?php
                if ($sukses) {
                ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $sukses ?>
                    </div>
                <?php
                header("refresh:8;url=index.php");
                }
                ?>
                <form action="" method="POST">
                    <div class="mb-3 row">
                        <label for="nim" class="col-sm-2 col-form-label">nim</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nim" name="nim" value="<?php echo $nim ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="nama" class="col-sm-2 col-form-label">nama</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $nama ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="alamat" class="col-sm-2 col-form-label">alamat</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="alamat" name="alamat" value="<?php echo $alamat ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="fakultas" class="col-sm-2 col-form-label">fakultas</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="fakultas" id="fakultas">
                                <option value="">-PILIH JURUSAN-</option>
                                <option value="teknik informatika" <?php if ($fakultas == "teknik informatika") echo "selected" ?>>teknik informatika</option>
                                <option value="sistem informasi" <?php if ($fakultas == "sistem informasi") echo "selected" ?>>sistem informaika</option>
                                <option value="akuntansi" <?php if ($fakultas == "akuntansi") echo "selected" ?>>akuntansi</option>
                                <option value="manajemen informatika" <?php if ($fakultas == "manajemen informatika") echo "selected" ?>>manajemen informatika</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <input type="submit" name="simpan" value="simpan data" class="btn btn-primary" />
                    </div>
                </form>
            </div>
        </div>

        <!-- untuk mengeluar data-->
        <div class="card text-white bg-secondary">
            <div class="card-header">
                Data Mahasiswa
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">no</th>
                            <th scope="col">nim</th>
                            <th scope="col">nama</th>
                            <th scope="col">alamat</th>
                            <th scope="col">fakultas</th>
                            <th scope="col">aksi</th>
                        </tr>
                    <tbody>
                        <?php
                        $sql2 = "select * from mahasiswa order by id desc";
                        $q2    = mysqli_query($koneksi, $sql2);
                        $urut = 1;
                        while ($r2 = mysqli_fetch_array($q2)) {
                            $id        = $r2['id'];
                            $nim       = $r2['nim'];
                            $nama      = $r2['nama'];
                            $alamat    = $r2['alamat'];
                            $fakultas  = $r2['fakultas'];

                        ?>
                            <tr>
                                <th scope="row"><?php echo $urut++ ?></th>
                                <th scope="row"><?php echo $nim ?></th>
                                <th scope="row"><?php echo $nama ?></th>
                                <th scope="row"><?php echo $alamat ?></th>
                                <th scope="row"><?php echo $fakultas ?></th>
                                <td scope="row">
                                    <a href="index.php?op=edit&id=<?php echo $id ?>"><button type="button" class="btn btn-danger">Edit</button></a>
                                    <a href="index.php?op=delete&id=<?php echo $id ?>" onclick="return confirm('Yakin mau delete data?')"><button type="button" class="btn btn-warning">Delete</button></a>
                                    
                                    
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                    </thead>
                </table>

            </div>
        </div>
    </div>
</body>
</html>