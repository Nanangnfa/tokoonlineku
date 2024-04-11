<?php
    session_start();
    require "../koneksi.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
</head>

<style>
    .main {
        height: 100vh;
    }

    .login-box {
        width: 450px;
        height: 300px;
        border: 1px;
        box-sizing: border-box;
        border-radius: 10px;
    }
</style>

<body>
    <div class="main d-flex flex-column justify-content-center align-items-center">
        <div class="login-box p-5 shadow">
            <h5 class="text-center mb-2">Toko Onlineku</h5>
            <form action="" method="post">
                <div>
                    <label for="username">Username</label>
                    <input type="text" class="form-control" name="username" 
                    id="username">
                </div>
                <div>
                    <label for="password">Pasword</label>
                    <input type="password" class="form-control" name="password"
                    id="password" >
                </div>
                <div>
                    <button class="btn btn-success form-control mt-3" type="submit"
                    name="loginbtn">Login</button>
                </div>
            </form>
        </div>

        <div class="mt-3" style="width: 450px;">
            <?php
            if (isset($_POST['loginbtn'])) {
                $username = htmlspecialchars($_POST['username']);
                $password = htmlspecialchars($_POST['password']);

                $query = mysqli_query($con, "SELECT * FROM users WHERE
                username='$username'");
                $countdata = mysqli_num_rows($query);
                $data = mysqli_fetch_array($query);

                if ($countdata>0){
                    if (password_verify($password, $data['password'])) {
                        $_SESSION['username'] = $data['username'];
                        $_SESSION['login'] = true;
                        header('location: ../adminpanel');

                }
                else{
                        ?>
                        <div class="alert alert-warning" role="alert">
                        Password salah
                        </div>
                        <?php
                    }
                }
                else{
                    ?>
                    <div class="alert alert-warning" role="alert">
                    Akun tidak terdaftar
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </div>

</body>

</html>