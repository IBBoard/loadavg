<?php
/**
* LoadAvg - Server Monitoring & Analytics
* http://www.loadavg.com
*
* Main index file
* 
* @version SVN: $Id$
* @link https://github.com/loadavg/loadavg
* @author Karsten Becker
* @copyright 2014 Sputnik7
*
* This file is licensed under the Affero General Public License version 3 or
* later.
*/

require_once '../globals.php';

/* Session */
ob_start(); 
session_start();

/* Initialize LoadAvg */ 
include 'class.LoadAvg.php';
$loadavg = new LoadAvg();

$settings = LoadAvg::$_settings->general;

$settings_file = APP_PATH . '/config/settings.ini';

require_once APP_PATH . '/layout/header.php';


if ( isset( $_GET['check'] ) ) {
	if ( $loadavg->checkWritePermissions( $settings_file ) ) {
		if ( $loadavg->checkInstaller() ) {
			header("Location: index.php");
		} else {
			?>
			<div class="well">
				<h3>Warning!</h3>
				<p>After removal of <span class="label label-info">install.php</span> hit <span class="label label-info">Check again</span> to <span class="label label-info">Login</span></p>
				<button class="btn btn-primary" onclick="location.reload();">Check again!</button>
			</div>
			<script>alert("YOU HAVE TO REMOVE install.php FROM YOUR /public FOLDER!");</script>
			<?php
			exit;
		}
	} else {
		header("Location: install.php?step=1");
	}
} else {
	$loadavg->checkInstall();
}

$loadavg->createFirstLogs();

if ( 
	( isset($_GET['minDate']) && !empty($_GET['minDate']) ) &&
	( isset($_GET['maxDate']) && !empty($_GET['maxDate']) )
	) 
{
	LoadAvg::$period = true;
	LoadAvg::$period_minDate = date("Y-m-d", strtotime($_GET['minDate']));
	LoadAvg::$period_maxDate = date("Y-m-d", strtotime($_GET['maxDate']));
}

$loadavg->setStartTime(); // Setting page load start time

$loaded = LoadAvg::$_settings->general['modules']; 
$logdir = APP_PATH . '/../logs/';

if ($settings['allow_anyone'] == "false" && !$loadavg->isLoggedIn()) {
	include( APP_PATH . '/views/login.php');
} else {
	if (isset($_GET['page']) && file_exists( APP_PATH . '/views/' . $_GET['page'] . '.php' ) ) {
		if ( !$loadavg->isLoggedIn() && $_GET['page'] == "settings" && (isset($settings['allow_anyone']) && $settings['allow_anyone'] == "true")) {
			if (!$loadavg->isLoggedIn()) { include( APP_PATH . '/views/login.php'); }
		} else {
			require_once APP_PATH . '/views/' . $_GET['page'] . '.php';
		}
	} else {
		require_once APP_PATH . '/views/index.php';
	}
}

$loadavg->setFinishTime(); // Setting page load finish time

$page_load = $loadavg->getPageLoadTime(); // Calculating page load time


require_once APP_PATH . '/layout/footer.php'; ?>