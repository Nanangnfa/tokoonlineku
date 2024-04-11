<?php
require "session.php";
require "../koneksi.php";

$id = $_GET['p'];

$query = mysqli_query($con, "SELECT a.*, b.nama AS nama_kategori FROM produk a JOIN kategori b ON a.kategori_id=b.id WHERE a.id='$id'");
$data = mysqli_fetch_array($query);

$queryKategori = mysqli_query($con, "SELECT * FROM kategori WHERE id!='$data[kategori_id]'");

function generationRandomString($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk Detail</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<style>
    form div {
        margin-bottom: 10px;
    }
</style>

<body>
    <?php require "navbar.php"; ?>

    <div class="container mt-5">
        <h3>Detail Produk</h3>

        <div class="col-12 col-md-6">
            <form action="" method="post" enctype="multipart/form-data">
                <div>
                    <label for="nama" class="mb-2">Nama</label>
                    <input type="text" id="nama" class="form-control" name="nama" value="<?php echo $data['nama'] ?>"
                        autocomplete="off">
                </div>
                <div>
                    <label for="kategori" class="mb-2">Kategori</label>
                    <select name="kategori" id="kategori" class="form-control" required>
                        <option value="<?php echo $data['kategori_id']; ?>">
                            <?php echo $data['nama_kategori']; ?>
                        </option>
                        <?php
                        while ($dataKategori = mysqli_fetch_array($queryKategori)) {
                            ?>
                            <option value="<?php echo $dataKategori['id']; ?>">
                                <?php echo $dataKategori['nama']; ?>
                            </option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <div>
                    <label for="harga">Harga</label>
                    <input type="number" class="form-control" name="harga" required
                        value="<?php echo $data['harga']; ?>">
                </div>
                <div>
                    <label for="currentFoto">Foto Produk Saat Ini</label>
                    <br>
                    <img src="../image/<?php echo $data['foto'] ?>" alt="" width="200px" class="rounded-3">
                </div>
                <div>
                    <label for="foto">Foto</label>
                    <input type="file" class="form-control" name="foto" id="foto">
                </div>
                <div>
                    <label for="detail">Detail</label>
                    <textarea class="form-control" name="detail" id="detail" cols="25"
                        row="10"><?php echo $data['detail'] ?></textarea>
                </div>
                <div>
                    <label for="ketersediaan_stok">Ketersediaan Stok</label>
                    <select name="ketersediaan_stok" id="ketersediaan_stok" class="form-control">
                        <option value="<?php echo $data['ketersediaan_stok'] ?>">
                            <?php echo $data['ketersediaan_stok'] ?>
                        </option>
                        <?php
                        if ($data['ketersediaan_stok'] == 'tersedia') {
                            ?>
                            <option value="habis">Habis</option>
                            <?php
                        } else {
                            ?>
                            <option value="tersedia">Tersedia</option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary" name="simpan">Simpan</button>
                    <button type="submit" class="btn btn-danger" name="hapus">Hapus</button>
                </div>
            </form>

            <?php
            if (isset($_POST['simpan'])) {
                $nama = htmlspecialchars($_POST['nama']);
                $kategori = htmlspecialchars($_POST['kategori']);
                $harga = htmlspecialchars($_POST['harga']);
                $detail = htmlspecialchars($_POST['detail']);
                $ketersediaan_stok = htmlspecialchars($_POST['ketersediaan_stok']);

                $target_dir = "../image/";
                $nama_file = basename($_FILES["foto"]["name"]);
                $target_file = $target_dir . $nama_file;
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                $image_size = $_FILES["foto"]["size"];
                $random_name = generationRandomString(20);
                $new_name = $random_name . "." . $imageFileType;

                if ($nama == '' || $kategori == '' || $harga == '') {
                    ?>
                    <div class="alert alert-warning mt-3" role="alert">
                        Nama, kategori, harga wajib diisi!
                    </div>
                    <?php
                } else {
                    $queryUpdate = mysqli_query($con, "UPDATE produk SET kategori_id='$kategori', nama='$nama', harga='$harga', detail='$detail', ketersediaan_stok='$ketersediaan_stok' WHERE id=$id");

                    if ($nama_file != '') {
                        if ($image_size > 500000) {
                            ?>
                            <div class="alert alert-warning mt-3" role="alert">
                                File tidak lebih dari 500 Kb
                            </div>
                            <?php
                        } else {
                            if ($imageFileType != 'jpg' && $imageFileType != 'png' ) {
                                ?>
                                <div class="alert alert-warning mt-3" role="alert">
                                    File wajib bertipe jpg atau png
                                </div>
                                <?php
                            } else {
                                move_uploaded_file($_FILES["foto"]["tmp_name"], $target_dir . $new_name);

                                $queryUpdate = mysqli_query($con, "UPDATE produk SET foto='$new_name' WHERE id='$id'");

                                if ($queryUpdate) {
                                    ?>
                                    <div class="alert alert-warning mt-3" role="alert">
                                        Produk Berhasil Tersimpan
                                    </div>

                                    <meta http-equiv="refresh" content="1; url=produk.php" />
                                    <?php
                                }
                            }
                        }
                    }
                }
            }

            if (isset($_POST['hapus'])) {
                $queryDelete = mysqli_query($con, "DELETE FROM produk WHERE id='$id'");

                if ($queryDelete) {
                    ?>
                    <div class="alert alert-primary mt-3" role="alert">
                        Produk Berhasil Dihapus
                    </div>
                    <meta http-equiv="refresh" content="2; url=produk.php" />
                    <?php
                }
            }
            ?>


        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
</body>

</html>