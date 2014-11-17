<?php

	class InventoryCtrl extends StandardCtrl{
		private $model;
	  
		function __construct(){
			parent::__construct();
	    	require('models/inventoryMdl.php');
	    	$this->model=new InventoryMdl();
		}
	  
		function run(){
			if(isset($_GET['act'])){
				switch($_GET['act']){
					case 'create':
					  	//User is valid and have permissions
					  	if($this->isLogged()){
			  				if($this->isManager() or $this->isUser()){
			  					$this->create();
			  				}
							else{
								require('views/NoAccess.php');
							}
			  			}
						else{
							require('views/needLogin.php');
						}
					  	break;
					case 'select':
						//User is valid and have permissions
						if($this->isLogged()){
			  				if($this->isManager() or $this->isUser()){
								$this->select();
			  				}
							else{
								require('views/NoAccess.php');
							}
			  			}
						else{
							require('views/needLogin.php');
						}
						break;
					case 'list':
						//User is valid and have permissions
						if($this->isLogged()){
			  				if($this->isManager() or $this->isUser()){
								$this->listInventories();
			  				}
							else{
								require('views/NoAccess.php');
							}
			  			}
						else{
							require('views/needLogin.php');
						}
						break;
					default:
					  break;
				}
			}
			else{
				$data = array(
					'page_title' => "Inventario",
					'general_content' => file_get_contents("views/inventoryMenu.html")
				);
				$this->createTemplate($data);
			}
		}
	  
		/**
		 * Create a new Inventory register
		 */
		private function create(){
			if(empty($_POST)){
				$data['page_title']='Registrar Inventario';
				$data['general_content']=file_get_contents('./views/inventoryCreate.html');
				$this->createTemplate($data);
			}
			else{
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
							require('controllers/mail.php');
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
			if(isset($_GET['act']) and $_GET['act']=='list'){
				$vista = file_get_contents("./views/inventoryList.html");
				$inicio_fila = strrpos($vista,'<tr>');
				$final_fila = strrpos($vista,'</tr>') + 5;
				$fila = substr($vista,$inicio_fila,$final_fila-$inicio_fila);
				$filas="";
				foreach ($Inventories as $row) {
					$new_fila = $fila;
					$fecha = new DateTime($row['date']);
					$diccionario = array(
						'{id}' => $row['idInventory'], 
						'{status}' => $row['status'],
						'{mileage}' => $row['mileage'],
						'{gasoline}' => $row['gasoline'],
						'{vehicle}' => $row['idVehicle'],
						'{date}' => $fecha->format('Y-m-d')
						);
					$new_fila = strtr($new_fila,$diccionario);
					$filas .= $new_fila;
				}

				$diccionario = array(
						'{alert}' => '');
				$vista = strtr($vista,$diccionario);

				$vista = str_replace($fila, $filas, $vista);
				$data['page_title']='Listado de Inventarios';
				$data['general_content']=$vista;
				$this->createTemplate($data);
			}
			return $Inventories;
		}
	}
?>