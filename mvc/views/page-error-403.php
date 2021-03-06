<!DOCTYPE html>
<html lang="en">

<head>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js"></script>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
    <link rel="icon" type="image/png" sizes="16x16" href="/public/assets/demo/favicon.png">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Error 403</title>
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
    <link href="<?php echo GRAPHSQLI_LAB_ROOT . '/public/assets/css/style.css" rel="stylesheet'; ?>" type="text/css">
    <!-- Head Libs -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
</head>

<body class="body-bg-full error-page error-403"
      style="background-image: url(<?php echo GRAPHSQLI_LAB_ROOT . '/public/assets/demo/body-bg.jpg'; ?>)">;
<div id="wrapper" class="wrapper">
    <div class="content-wrapper">
        <main class="main-wrapper">
            <div class="page-title">
                <h1>403</h1>
            </div>
            <h4>Access Denied!</h4>
            <?php
            if (isset ($error_msg) && $error_msg !== "") {
                echo "<p class=\"mr-t-10 mr-b-20\">{$error_msg}</p><a
                    href=\"javascript: history.back();\" class=\"btn btn-info btn-lg btn-rounded mr-b-20 ripple\">Go Back</a>";
            } else {
                echo "<p class=\"mr-t-10 mr-b-20\">You cannot access here!!!!</p><a
                    href=\"javascript: history.back();\" class=\"btn btn-info btn-lg btn-rounded mr-b-20 ripple\">Go Back</a>";
            }
            ?>
        </main>
    </div>
    <!-- .content-wrapper -->
</div>
<!-- .wrapper -->
</body>

</html>