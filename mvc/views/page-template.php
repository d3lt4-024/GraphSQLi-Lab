<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="<?php echo GRAPHSQLI_LAB_ROOT . '/public/assets/css/pace.css'; ?>">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js"></script>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
    <link rel="icon" type="image/png" sizes="16x16"
          href="<?php echo GRAPHSQLI_LAB_ROOT . '/public/assets/demo/favicon.png'; ?>">
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
    <link href="<?php echo '/' . GRAPHSQLI_LAB_ROOT . '/public/assets/vendors/weather-icons-master/weather-icons.min.css'; ?>"
          rel="stylesheet" type="text/css">
    <link href=" <?php echo GRAPHSQLI_LAB_ROOT . '/public/assets/vendors/weather-icons-master/weather-icons-wind.min.css'; ?>"
          rel="stylesheet"
          type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/2.1.25/daterangepicker.min.css"
          rel="stylesheet" type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css" rel="stylesheet" type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.min.css" rel="stylesheet"
          type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick-theme.min.css" rel="stylesheet"
          type="text/css">
    <link href="<?php echo GRAPHSQLI_LAB_ROOT . '/public/assets/css/style.css" rel="stylesheet'; ?>" type="text/css">
    <!-- Head Libs -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.js"
            integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>

</head>
<body class="header-light sidebar-dark sidebar-expand">
<div id="wrapper" class="wrapper">
    <nav class="navbar">
        <div class="spacer" style="background-color:#5595de;">
            <center><img src="<?php echo GRAPHSQLI_LAB_ROOT . '/public/assets/demo/logo-expand.png'; ?>"
                         alt="GraphSQLi Lab"/></center>
        </div>
    </nav>
    <div class="content-wrapper">
        <aside class="site-sidebar scrollbar-enabled clearfix">
            <div class="side-user">
                <a class="col-sm-12 media clearfix">
                    <div class="media-body hide-menu">
                        <?php echo $data["info"] ?>
                    </div>
                </a>
            </div>
            <nav class="sidebar-nav">
                <ul class="nav in side-menu">
                    <?php echo $data["menu"] ?>
                </ul>
            </nav>
        </aside>
    </div>
    <div class="main-wrapper clearfix">
        <div class="widget-list">
            <?php echo $data["page_body"] ?>
        </div>
    </div>
</div>
<!-- /.widget-list -->
</main>
<!-- /.main-wrappper -->
</div>
<!-- /.content-wrapper -->
</div>
<!--/ #wrapper -->
<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.2/umd/popper.min.js"></script>
<script src="<?php echo GRAPHSQLI_LAB_ROOT . '/public/assets/js/bootstrap.min.js'; ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/mediaelement/4.1.3/mediaelementplayer.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/metisMenu/2.7.0/metisMenu.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/0.7.0/js/perfect-scrollbar.jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.4/sweetalert2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Counter-Up/1.0.0/jquery.counterup.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.min.js"></script>
<script src="<?php echo GRAPHSQLI_LAB_ROOT . '/public/assets/vendors/charts/utils.js'; ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-Knob/1.2.13/jquery.knob.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-sparklines/2.1.2/jquery.sparkline.min.js"></script>
<script src="<?php echo GRAPHSQLI_LAB_ROOT . '/public/assets/vendors/charts/excanvas.js'; ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/mithril/1.1.1/mithril.js"></script>
<script src="<?php echo GRAPHSQLI_LAB_ROOT . 'public/assets/vendors/theme-widgets/widgets.js'; ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/clndr/1.4.7/clndr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.2.7/raphael.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/2.1.25/daterangepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.min.js"></script>
<script src="<?php echo GRAPHSQLI_LAB_ROOT . '/public/assets/js/theme.js'; ?>"></script>
<script src="<?php echo GRAPHSQLI_LAB_ROOT . '/public/assets/js/custom.js?v=101'; ?>"></script>
</body>

</html>
