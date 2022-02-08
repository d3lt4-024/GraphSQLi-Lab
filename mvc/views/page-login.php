<!DOCTYPE html>
<html lang="en">

<head>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js"></script>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
    <link rel="icon" type="image/png" sizes="14x16"
          href=""<?php echo GRAPHSQLI_LAB_ROOT . '/public/assets/demo/favicon.png'; ?>">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title><?php echo $data["title"] ?></title>
    <!-- CSS -->
    <link href="<?php echo GRAPHSQLI_LAB_ROOT . '/public/assets/vendors/material-icons/material-icons.css'; ?>"
          rel="stylesheet" type="text/css">
    <link href="<?php echo GRAPHSQLI_LAB_ROOT . '/public/assets/vendors/mono-social-icons/monosocialiconsfont.css'; ?>"
          rel="stylesheet" type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.4/sweetalert2.css" rel="stylesheet"
          type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css" rel="stylesheet"
          type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mediaelement/4.1.3/mediaelementplayer.min.css" rel="stylesheet"
          type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/0.7.0/css/perfect-scrollbar.min.css"
          rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:400,600,700" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,400i,500,700" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600" rel="stylesheet" type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"
          type="text/css">
    <link href="<?php echo GRAPHSQLI_LAB_ROOT . '/public/assets/css/style.css'; ?>" rel="stylesheet" type="text/css">
    <!-- Head Libs -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.js"
            integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymouss"></script>
</head>

<body class="body-bg-full profile-page"
      style="background-image: url(<?php echo GRAPHSQLI_LAB_ROOT . '/public/assets/demo/night.jpg'; ?>)">
<div id="wrapper" class="row wrapper">
    <div class="col-10 ml-sm-auto col-sm-6 col-md-4 ml-md-auto login-center mx-auto">
        <div class="navbar-header text-center">
            <a href="/">
                <img alt="" src="<?php echo GRAPHSQLI_LAB_ROOT . '/public/assets/demo/logo-expand-dark.png'; ?>">
            </a>
        </div>
        <!-- /.navbar-header -->
        <form method="POST" class="form-material" id="loginForm">
            <div class="form-group">
                <input type="text" placeholder="Your Username" class="form-control form-control-line" name="username"
                       id="UsernameField">
                <label for="username">Username</label>
            </div>
            <div class="form-group">
                <input type="password" placeholder="Your Password" class="form-control form-control-line"
                       name="password" id="PasswordField">
                <label>Password</label>
            </div>
            <div class="form-group">
                <button class="btn btn-block btn-lg btn-color-scheme ripple" type="submit" value="login" name="login"
                        id="LoginBtn">
                    Login
                </button>
            </div>
            <div class="form-group">
                <input type='hidden' name='user_token' value='<?php echo $data['csrf_token'] ?>' id="UserTokenField"/>
            </div>
        </form>
        <script type="text/javascript">
            $(document).ready(function () {
                $('#loginForm').submit(function (e) {
                    e.preventDefault();
                    var formData = {
                        'username': document.getElementById('UsernameField').value,
                        'password': document.getElementById('PasswordField').value,
                        'user_token': document.getElementById('UserTokenField').value,
                        'action': document.getElementById('LoginBtn').value
                    };
                    $.ajax({
                        type: "POST",
                        url: '/api/login',
                        datatype: 'json',
                        data: JSON.stringify(formData),
                        success: function (result) {
                            if (typeof result.error == "undefined") {
                                swal({
                                    title: 'Login Success',
                                    text: result.result,
                                    type: 'success'
                                });
                                window.location.href = "\home";
                            } else {
                                swal({
                                    title: 'Login Error',
                                    text: result.error.message,
                                    type: 'error'
                                });
                            }
                        }
                    });
                });
            });
        </script>
        <!-- /.login-center -->
    </div>
    <!-- /.body-container -->
    <!-- Scripts -->
    <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript"
            src="http://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script type="text/javascript"
            src="<?php echo GRAPHSQLI_LAB_ROOT . '/public/assets/js/material-design.js'; ?>"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.2/umd/popper.min.js"></script>
    <script src="<?php echo GRAPHSQLI_LAB_ROOT . '/public/assets/js/bootstrap.min.js'; ?>"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mediaelement/4.1.3/mediaelementplayer.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/metisMenu/2.7.0/metisMenu.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/0.7.0/js/perfect-scrollbar.jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.4/sweetalert2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.1/jquery.toast.min.js"></script>
</body>

</html>