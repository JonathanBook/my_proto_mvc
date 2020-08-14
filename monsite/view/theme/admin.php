<?php
$user = $_SESSION['User'];


?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Administration</title>

    <!-- Custom fonts for this template-->
    <link href="<?= BASE_URL . '/webroot/vendor/fontawesome-free/css/all.min.css' ?>" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?= BASE_URL . '/webroot/css/sb-admin-2.min.css' ?>" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?php echo Router::url('admin/posts/index'); ?>">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Administration</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="<?php echo Router::url('admin/posts/index'); ?>">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Tableau de bord</span></a>
            </li>



            <!-- Divider -->
            <hr class="sidebar-divider">


            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="<?php echo Router::url('/'); ?>" aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Retour au site</span>
                </a>

            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Édition
            </div>



            <!-- Nav Item - Posts Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="<?php echo Router::url('admin/medias/galerie'); ?>" aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Galerie d'images</span>
                </a>

            </li>





            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">



        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">



                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $user->login ?> </span>
                                <img class="img-profile rounded-circle" src="https://source.unsplash.com/QAB-WJcbgJk/60x60">
                            </a>

                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="<?php echo Router::url('users/profil') ?>">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Activity Log
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="<?php echo Router::url('users/logout') ?>">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Déconnection
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- affiche les info de la flach -->
                    <div aria-labelledby="headingPages" data-parent="#accordionSidebar">
                        <?php echo $this->Session->flash(); ?>
                        <?php echo $content_for_theme; ?>
                    </div>


                    <!-- Content Row -->

                    <div class="row">



                    </div>
                    <!-- End of Main Content -->

                    <!-- Footer -->
                    <footer class="sticky-footer bg-white">
                        <div class="container my-auto">
                            <div class="copyright text-center my-auto">
                                <span>Copyright &copy; My Website 2020</span>
                            </div>
                        </div>
                    </footer>
                    <!-- End of Footer -->

                </div>
                <!-- End of Content Wrapper -->

            </div>
            <!-- End of Page Wrapper -->




            <!-- Bootstrap core JavaScript-->
            <script src="<?= BASE_URL . '/webroot/vendor/jquery/jquery.min.js ' ?>"></script>
            <script src="<?= BASE_URL . '/webroot/vendor/bootstrap/js/bootstrap.bundle.min.js ' ?>"></script>

            <!-- Core plugin JavaScript-->
            <script src="<?= BASE_URL . '/webroot/vendor/jquery-easing/jquery.easing.min.js ' ?>"></script>

            <!-- Custom scripts for all pages-->
            <script src="<?= BASE_URL . '/webroot/js/sb-admin-2.min.js' ?>"></script>

            <!-- Page level plugins -->
            <script src="<?= BASE_URL . '/webroot/vendor/chart.js/Chart.min.js' ?>"></script>



</body>

</html>