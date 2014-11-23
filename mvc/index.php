<?php
	session_start();
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
				default:
					break;
			}
			//execute ctlr
			$ctrl->run();
		}
		else{
			require('controllers/validationCtrl.php');
			require('controllers/standardCtrl.php');
			$ctrl=new StandardCtrl();
			if(!isset($_SESSION['user'])){		
				$vista=file_get_contents('views/login.html');
				$vista.=file_get_contents('views/pie.html');
				echo $vista;
			}
			else {
				$data['page_title']='Usuario';
				$data['general_content']=file_get_contents('views/userMenu.html');
				$ctrl->createTemplate($data);	
			}
		}
?>
