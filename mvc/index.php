<?php

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
		case 'inventory':
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
