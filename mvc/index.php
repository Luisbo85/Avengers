<?php
	error_reporting(E_ALL);
	ini_set('display_errors', '1');

	session_start();

	$_SESSION['IDuser'] = '1';
	$_SESSION['job'] = 'Manager';
	$_SESSION['user'] = 'root';
	$_SESSION['status'] = '1';

	if(isset($_GET['ctrl'])){
		require('controllers/validationCtrl.php');
		require('controllers/standardCtrl.php');
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
			/*case 'session':
				require('controllers/standardCtrl.php');
				$login=new StandardCtrl();
				if($login->isLogged()==FALSE){
					require('views/login.html');
				}
				else{
					require('views/logout.php');
				}
				break;*/
			default:
				break;
		}
	}
	//execute ctlr
	if(isset($ctrl)) {
		$ctrl->run();
	}
?>
