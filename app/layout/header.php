<?php
/**
* LoadAvg - Server Monitoring & Analytics
* http://www.loadavg.com
*
* Main header file 
* 
* @link https://github.com/loadavg/loadavg
* @author Karsten Becker
* @copyright 2014 Sputnik7
*
* This file is licensed under the Affero General Public License version 3 or
* later.
*/

$error = '';
if (isset($_POST['login'])) {
	if ( isset($_POST['username']) && isset($_POST['password']) ) {
		$loadavg->logIn( $_POST['username'], $_POST['password']);
		if ($loadavg->isLoggedIn()) header("Location: index.php");
	} else {
		if (!isset($_POST['username'])) { $error .= "<li>Username is mandatory!</li>"; }
		if (!isset($_POST['password'])) { $error .= "<li>Password is mandatory!</li>"; }
	}
}
?>
<!DOCTYPE html>
<!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>    <html class="lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>    <html class="lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html> <!--<![endif]-->
<head>
	<title><?php echo sprintf($settings['title'], $settings['version'], ''); ?></title>
	
	<!-- Meta -->
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	
	<!-- Bootstrap -->
	<link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
	<link href="assets/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" />

	<!-- Bootstrap Toggle Buttons Script -->
	<link href="assets/bootstrap/extend/bootstrap-toggle-buttons/static/stylesheets/bootstrap-toggle-buttons.css" rel="stylesheet">
	
	<!-- JQueryUI v1.9.2 -->
	<link rel="stylesheet" href="assets/theme/scripts/plugins/system/jquery-ui-1.9.2.custom/css/smoothness/jquery-ui-1.9.2.custom.min.css" />
	
	<!-- Glyphicons -->
	<link rel="stylesheet" href="assets/theme/css/font-awesome.min.css" />
	
	<!-- JQuery v1.8.2 -->
	<script src="assets/theme/scripts/plugins/system/jquery-1.8.2.min.js"></script>
	
	<!-- Modernizr -->
	<script src="assets/theme/scripts/plugins/system/modernizr.custom.76094.js"></script>
	
	<!-- Theme -->
	<link rel="stylesheet" href="assets/theme/css/style.css?<?php echo time(0); ?>" />
	
	<!-- LESS 2 CSS -->
	<script src="assets/theme/scripts/plugins/system/less-1.3.3.min.js"></script>
	
	<!--[if IE]><script type="text/javascript" src="assets/theme/scripts/plugins/other/excanvas/excanvas.js"></script><![endif]-->

	<script type="text/javascript">
	<?php 
	$min = date('Y-m-d');
	$max = date('Y-m-d');
	if (isset($_GET['logdate']) && !empty($_GET['logdate']) && $_GET['logdate'] !== date('Y-m-d'))
	{
		$min = $_GET['logdate'];
		$max = $_GET['logdate'];
	}
	if (isset($_GET['minDate']) && !empty($_GET['minDate']) && isset($_GET['maxDate']) && !empty($_GET['maxDate']))
	{
		$min = $_GET['minDate'];
		$max = $_GET['maxDate'];
	}
	$min = strtotime($min);
	$max = strtotime($max);
	?>
	var today_min = <?php echo mktime(0, 0, 0, date("n", $min), date("j", $min), date("Y", $min))*1000; ?>;
	var today_max = <?php echo mktime(24, 0, 0, date("n", $max), date("j", $max), date("Y", $max))*1000; ?>;
	</script>
</head>
<body>
	
	<!-- Start Content -->
	<div class="container fixed">
		
		<div class="navbar main hidden-print">
			
			<a href="index.php" class="appbrand"><img src="assets/theme/images/loadavg_logo.png" style="float: left; margin-right: 5px;"><span>LoadAvg<span>Advanced Server Analytics</span></span></a>
			
			<?php if ($loadavg->isLoggedIn() || (isset($settings['allow_anyone']) && $settings['allow_anyone'] == "true")) { ?>
			<ul class="topnav pull-right">
				<li<?php if (isset($_GET['page']) && $_GET['page'] == '') : ?> class="active"<?php endif; ?>><a href="index.php"><i class="fa fa-bar-chart-o"></i> Charts</a></li>

				<li <?php if (isset($_GET['page']) && $_GET['page'] == 'server') : ?> class="active"<?php endif; ?>><a href="?page=server"><i class="fa fa-question-circle"></i> Server</a></li>
				
				<li class="account <?php if (isset($_GET['page']) && $_GET['page'] == 'settings') : ?> active<?php endif; ?>">
					<a data-toggle="dropdown" href="" class="logout"><span class="hidden-phone text"><?php echo (isset($settings['allow_anyone']) && $settings['allow_anyone'] == "true" ) ? 'Preferences' : $settings['username']; ?></span> <i class="fa fa-unlock-alt"></i></a>
					<ul class="dropdown-menu pull-right">
						<li><a href="?page=settings">Settings <i class="fa fa-cog pull-right"></i></a></li>
						<?php if ( $loadavg->isLoggedIn() ): ?>
						<li>
							<span>
								<a href="?page=logout" class="btn btn-default btn-small pull-right" style="padding: 2px 10px; background: #fff;" href="">Sign Out</a>
							</span>
						</li>
						<?php endif; ?>
					</ul>
				</li>
			</ul>
			<?php } ?>
		</div>
		
		<div id="wrapper">
		
		<div id="content">

			<?php if (strlen($error)>0) { echo "<ul>" . $error . "</ul>"; }?>
