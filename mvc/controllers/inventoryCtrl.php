<?php

	class InventoryCtrl extends ValidationCtrl{
		private $model;
	  
		function __construct(){
	    	require('models/inventoryMdl.php');
	    	$this->model=new InventoryMdl();
		}
	  
		function run(){
			switch($_GET['act']){
				case 'create':
				  	//User is valid and have permissions
				  	$this->create();
				  	break;
				case 'select':
					//User is valid and have permissions
					$this->select();
					break;
				case 'list':
					//User is valid and have permissions
					$this->listInventories();
					break;
				default:
				  break;
			}
		}
	  
		/**
		 * Create a new Inventory register
		 */
		private function create(){
			$Correct=TRUE;//Flag to determine if it can create a new Inventory
			$NoSet=FALSE; //Flag to determine if the variables are set
			//Validate variables and if variables is set 
			$Mileage=isset($_POST['Mileage'])?$this->validateNumber($_POST['Mileage']):$NoSet=TRUE;
			$Gasoline=isset($_POST['Gasoline'])?$this->validateNumber($_POST['Gasoline']):$NoSet=TRUE;
			$IDPiece=isset($_POST['IDPiece'])?$this->validateID($_POST['IDPiece']):$NoSet=TRUE;
			$Severity=isset($_POST['Severity'])?$this->validateText($_POST['Severity']):$NoSet=TRUE;
			$IDVehicle=isset($_POST['IDVehicle'])?$this->validateNumber($_POST['IDVehicle']):$NoSet=TRUE;
			$Observations=isset($_POST['Observations'])?$this->validateText($_POST['Observations']):$NoSet=TRUE;
			
			if($NoSet==FALSE){
				
				if($Correct==TRUE){
					//Insert a new Inventory
					$Result=$this->model->create($Mileage,$Gasoline,$IDPiece,$Severity,$IDVehicle,$Observations);
					
					if($Result!=FALSE){
						require('views/inventoryInserted.php');
						require('mail.php');
						$subject = 'Correo de registro de inventario';
						$body = 'El inventario se registro exitosamente.';
						$mail = new Email($Email, $subject, $body);
						$mail->send();
					}
					else{
						require('views/Error.php');
					}
				}
				else{
					require('views/Error.php');
				}
			}
			else{
				require('views/Error.php');
			}
	  	
		}
	   
		/**
		 * Show information of an inventory 
		 */
		private function select(){
			//Validate variables and if variables is set 
			$IDInventory=isset($_POST['IDInventory'])?$this->validateID($_POST['IDInventory']):FALSE;
			
			if($IDInventory!=FALSE){
				//Select Inventory
				$Inventory=$this->model->select($IDInventory);
				
				if(is_array($Inventory)){
					require('views/inventorySelected.php');
				}
				else{
					require('views/Error.php');
				}
			}
			else{
				require('views/Error.php');
			}
		}

		private function listInventories(){
			//Select all Inventories
			$Inventories=$this->model->listInventories();
			if(is_array($Inventories)){
				require('views/inventoryList.php');
			}
			else{
				require('views/Error.php');
			}
		}
	}
?>