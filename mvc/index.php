<?php
	//get query args
	switch($_GET['ctrl']) {
		case 'vehicle':
			//load ctrl file
			require('controllers/validationCtrl.php');
			require('controllers/vehicleCtrl.php');
			//create object
			$ctrl = new vehicleCtrl();
			break;
		case 'user':
			require('controllers/validationCtrl.php');
			require('controllers/userCtrl.php');
			$ctrl = new userCtrl();
			break;
		case 'location':
			require('controllers/validationCtrl.php');
			require('controllers/locationCtrl.php');
			$ctrl = new LocationCtrl();
			break;
		case 'inventory':
			require('controllers/validationCtrl.php');
			require('controllers/inventoryCtrl.php');
			$ctrl= new InventoryCtrl();
			break;
		default:
			break;
	}
	
	//execute ctlr
	if(isset($ctrl)) {
		$ctrl->run();
	}
?>
