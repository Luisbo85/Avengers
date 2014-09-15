<?php

error_reporting(E_ALL);
	 ini_set('display_errors', 1);

	//get query args
	switch($_GET['ctrl']) {
		case 'vehicle':
			//load ctrl file
			require('controllers/vehicleCtrl.php');
			//create object
			$ctrl = new vehicleCtrl();
			break;
		case 'user':
			require('controllers/userCtrl.php');
			$ctrl = new userCtrl();
			break;
		case 'location':
			require('controllers/locationCtrl.php');
			$ctrl = new LocationCtrl();
			break;
		default:
			break;
	}
	
	//execute ctlr
	$ctrl->run();
?>