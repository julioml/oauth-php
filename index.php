<?php
    require_once 'config.php';
    
    if(isset($_SESSION['user_token'])) {
        header('Location: welcome.php');
    } else {
        $tmp = "<a class='btn btn-google btn-login text-uppercase fw-bold' href='".$client->createAuthUrl()."'>Iniciar sesión con Google</a>";
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>

    <link rel="stylesheet" href="./public/styles/bootstrap.min.css">
    <link rel="stylesheet" href="./public/styles/styles_form.css">
    
</head>

<body>
    <div class="container-fluid ps-md-0">
        <div class="row g-0">
            <div class="d-none d-md-flex col-md-4 col-lg-6 bg-image"></div>
            <div class="col-md-8 col-lg-6">
                <div class="login d-flex align-items-center py-5">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-9 col-lg-8 mx-auto">
                                <h3 class="login-heading mb-4">Welcome back!</h3>

                                <form>
                                    <div class="form-floating mb-3">
                                        <input type="email" class="form-control" id="floatingInput"
                                            placeholder="name@example.com">
                                        <label for="floatingInput">Correo electrónico</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="password" class="form-control" id="floatingPassword"
                                            placeholder="Password">
                                        <label for="floatingPassword">Contraseña</label>
                                    </div>                                    

                                    <div class="d-grid">
                                        <button class="btn btn-lg btn-primary btn-login text-uppercase fw-bold mb-2"
                                            type="submit">Iniciar sesión</button>
                                    </div>
                                </form>

                                <hr class="my-4">
                                <div class="d-grid mb-2">
                                    <?php
                                        if(isset($tmp))
                                            echo $tmp;
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="./public/js/bootstrap.bundle.min.js"></script>
</body>

</html>