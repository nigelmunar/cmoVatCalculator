<?php

declare(strict_types=1);

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>CMO - Test</title>
        <link href="https://use.typekit.net/eli6ita.css" rel="stylesheet" />
        <script src="https://kit.fontawesome.com/524ac9d949.js" crossorigin="anonymous"></script>
		<!-- Ionicons -->
		<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
		<!-- Theme style -->
		<link rel="stylesheet" href="<?php echo $siteURL; ?>dist/css/adminlte.min.css">
        <!-- Toastr -->
        <link rel="stylesheet" href="<?php echo $siteURL; ?>plugins/toastr/toastr.min.css">

        <!-- <link href="<?php echo $siteURL; ?>plugins/magnific-popup/magnific-popup.css" rel="stylesheet" /> -->

        <link rel="stylesheet" href="<?php echo $siteURL; ?>dist/css/style.css" />
        <!-- <link rel="stylesheet" href="<?php echo $siteURL; ?>dist/css/media.css" /> -->

        <!-- NEW ADMIN STYLE -->
        <link rel="stylesheet" href="<?php echo $siteURL; ?>dist/css/admin.css">

        <?php
			for($i = 0; $i < count($styles); $i++)
			{
				echo '<link href="' . $styles[$i][0] . '" rel="stylesheet" ';

				for($j = 1; $j < count($styles[$i]); $j++)
				{
					echo ' ' . $styles[$i][$j];
				}

				echo '/>';
			}
		?>
        <!-- END Custom CSS-->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script>var sSiteURL = '<?php echo $siteURL; ?>';</script>
        <style>
            .main-sidebar { background: #fafafa!important; }

            .sidebar-dark-primary .nav-sidebar>.nav-item>.nav-link.active, .sidebar-light-primary .nav-sidebar>.nav-item>.nav-link.active { background: #130B91; }
            .sidebar .user-panel  { background: #130B91; }
            </style>
	</head>
	<body class="hold-transition sidebar-mini">
		<div class="wrapper">
            <!-- Navbar -->
            <nav class="main-header navbar navbar-expand navbar-white navbar-light">
                <!-- Right navbar links -->
                
                <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
            </nav>
		
            <!-- Main Sidebar Container -->
            <aside class="main-sidebar sidebar-light-primary elevation-4">
                <!-- Brand Logo -->
                <a href="<?php echo $siteURL?>" class="brand-link">
                    <span class="brand-text font-weight-dark" style="color: #130B91;">CMO - TEST</span>
                </a>
   
                <nav class="mt-2">

                </nav>

            </aside>
