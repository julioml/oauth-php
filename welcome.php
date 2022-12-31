<?php
    require_once 'config.php';

    // authenticate code from Google OAuth Flow
    if (isset($_GET['code'])) {
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        $client->setAccessToken($token['access_token']);

        // get profile info
        $google_oauth = new Google_Service_Oauth2($client);
        $google_account_info = $google_oauth->userinfo->get();
        $userinfo = [
            'email' => $google_account_info['email'],
            'first_name' => $google_account_info['givenName'],
            'last_name' => $google_account_info['familyName'],
            'gender' => $google_account_info['gender'],
            'full_name' => $google_account_info['name'],
            'picture' => $google_account_info['picture'],
            'verifiedEmail' => $google_account_info['verifiedEmail'],
            'token' => $google_account_info['id'],
        ];

        // checking if user is already exists in database
        $sql = "SELECT * FROM users WHERE email ='{$userinfo['email']}'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            // user is exists
            $userinfo = mysqli_fetch_assoc($result);
            $token = $userinfo['token'];
        } else {
            // user is not exists
            $sql = "INSERT INTO users (email, first_name, last_name, gender, full_name, picture, verifiedEmail, token) VALUES ('{$userinfo['email']}', '{$userinfo['first_name']}', '{$userinfo['last_name']}', '{$userinfo['gender']}', '{$userinfo['full_name']}', '{$userinfo['picture']}', '{$userinfo['verifiedEmail']}', '{$userinfo['token']}')";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                $token = $userinfo['token'];
            } else {
                echo "User is not created";
                die();
            }
        }

        // save user data into session
        $_SESSION['user_token'] = $token;
    } else {
        if (!isset($_SESSION['user_token'])) {
            header("Location: index.php");
            die();
        }

        // checking if user is already exists in database
        $sql = "SELECT * FROM users WHERE token ='{$_SESSION['user_token']}'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            // user is exists
            $userinfo = mysqli_fetch_assoc($result);
        }
    }

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./public/styles/bootstrap.min.css">
    <style>
        body{            
            background:#f8f8f8
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <div class="row flex-lg-nowrap">
            <div class="col-12 col-lg-auto mb-3" style="width: 200px;">
                <div class="card p-3">
                    <div class="e-navlist e-navlist--active-bg">
                        <ul class="nav">
                            <li class="nav-item"><a class="nav-link px-2 active" href="#"><i
                                        class="fa fa-fw fa-bar-chart mr-1"></i><span>Dashboard</span></a></li>                            
                            <li class="nav-item"><a class="nav-link px-2"
                                    href="#" target="__blank"><i class="fa fa-fw fa-cog mr-1"></i><span>Ajustes</span></a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="row">
                    <div class="col mb-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="e-profile">
                                    <div class="row">
                                        <div class="col-12 col-sm-auto mb-3">
                                            <div class="mx-auto" style="width: 140px;">
                                                <div class="d-flex justify-content-center align-items-center rounded"
                                                    style="height: 140px; background-color: rgb(233, 236, 239);">
                                                    <img src="<?= $userinfo['picture'] ?>" alt="" width="140px" height="140px">
                                                    <!-- <span
                                                        style="color: rgb(166, 168, 170); font: bold 8pt Arial;">140x140</span> -->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col d-flex flex-column flex-sm-row justify-content-between mb-3">
                                            <div class=" text-sm-left mb-2 mb-sm-0">
                                                <h4 class="pt-sm-2 pb-1 mb-0 text-nowrap"><?= $userinfo['full_name'] ?></h4>
                                                <p class="mb-0"><?= $userinfo['email'] ?></p>
                                                <div class="mt-2">
                                                    <button class="btn btn-primary" type="button">
                                                        <i class="fa fa-fw fa-camera"></i>
                                                        <span>Cambiar foto</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <ul class="nav nav-tabs">
                                        <li class="nav-item"><a href="" class="active nav-link">Actualizar perfil</a></li>
                                    </ul>

                                    <?php
                                        if(isset($_POST['submit'])) {
                                            echo "TRUE";
                                            $first_name = $_POST['first_name'];
                                            $last_name  = $_POST['last_name'];
                                            $gender     = $_POST['gender'];
                                            $token      = $_POST['token'];

                                            $query_update = "UPDATE users SET first_name = '$first_name', last_name = '$last_name', gender = '$gender' WHERE token = '$token'";
                                            $res = mysqli_query($conn, $query_update);

                                            if($res == 1){
                                                echo "TRUE";
                                            }else {
                                                echo "FALSE";
                                            }
                                        }
                                    ?>


                                    <div class="tab-content pt-3">
                                        <div class="tab-pane active">
                                            <form class="form" action="welcome.php" method="post">
                                                <input type="hidden" value="<?= $userinfo['token'] ?>" name="token">
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="row">
                                                            <div class="col">
                                                                <div class="form-group">
                                                                    <label>Nombres</label>
                                                                    <input class="form-control" type="text" name="first_name"
                                                                        value="<?= $userinfo['first_name'] ?>" required>
                                                                </div>
                                                            </div>
                                                            <div class="col">
                                                                <div class="form-group">
                                                                    <label>Apellidos</label>
                                                                    <input class="form-control" type="text" name="last_name"
                                                                        value="<?= $userinfo['last_name'] ?>" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col">
                                                                <div class="form-group">
                                                                    <label>Correo electrónico</label>
                                                                    <input class="form-control" type="text"
                                                                        placeholder="user@example.com" value="<?= $userinfo['email'] ?>">
                                                                </div>
                                                            </div>
                                                            <div class="col">
                                                                <div class="form-group">
                                                                    <label>Género</label>
                                                                    <input class="form-control" type="text"
                                                                        name="gender"
                                                                        value="<?= $userinfo['gender'] ?>" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-primary mt-3">Actualizar</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-3 mb-3">
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="px-xl-3">
                                    <a class="btn btn-block btn-secondary" href="logout.php">
                                        <i class="fa fa-sign-out"></i>
                                        <span>Cerrar sesión</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title font-weight-bold">Soporte</h6>
                                <p class="card-text">Obtenga ayuda rápida y gratuita de nuestros amables asistentes.</p>
                                <button type="button" class="btn btn-primary">Contáctanos</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>

</html>