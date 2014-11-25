<?php

	class InventoryCtrl extends StandardCtrl{
		private $model;
	  
		function __construct(){
			parent::__construct();
	    	require('models/inventoryMdl.php');
	    	$this->model=new InventoryMdl();
		}
	  
	  	/**
		 * This select the correct method in the class
		 */
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
								$this->noAccess();
							}
			  			}
						else{
							$this->goHome();
						}
					  	break;
					case 'delete':
						//User is valid and have permissions
					  	if($this->isLogged()){
			  				if($this->isManager()){
			  					$this->delete();
			  				}
							else{
								$this->noAccess();
							}
			  			}
						else{
							$this->goHome();
						}
						break;
					case 'update':
						//User is valid and have permissions
					  	if($this->isLogged()){
			  				if($this->isManager()){
			  					$this->update();
			  				}
							else{
								$this->noAccess();
							}
			  			}
						else{
							$this->goHome();
						}
						break;
					case 'piece':
						//User is valid and have permissions
					  	if($this->isLogged()){
			  				if($this->isManager() or $this->isUser()){
			  					$this->piece();
			  				}
							else{
								$this->noAccess();
							}
			  			}
						else{
							$this->goHome();
						}
						break;
					case 'select':
						//User is valid and have permissions
						if($this->isLogged()){
			  				if($this->isManager()){
								$this->select();
			  				}
							else{
								$this->noAccess();
							}
			  			}
						else{
							$this->goHome();
						}
						break;
					case 'list':
						//User is valid and have permissions
						if($this->isLogged()){
			  				if($this->isManager()){
								$this->listInventories();
			  				}
							else{
								$this->noAccess();
							}
			  			}
						else{
							$this->goHome();
						}
						break;
					default:
					  break;
				}
			}
			else{
				if($this->isLogged()){
					$data = array(
						'page_title' => "Inventario",
						'general_content' => file_get_contents("views/inventoryMenu.html")
					);
					$this->createTemplate($data);
				}
				else{
					$this->goHome();
				}
			}
		}
	  
		/**
		 * Create a new Inventory register. Do data validations
		 */
		private function create(){
			if(empty($_POST)){
				require('models/vehicleMdl.php');
				$vehicleMdl=new VehicleMdl();
				$result=$vehicleMdl->selectAll();
				$filas = '';
				$vehicles=array();
				while($linea = $result->fetch_assoc()) {
					$vehicles[] = $linea;
				}
				foreach ($vehicles as $row) {
					$new_fila = '<option value="'.$row['idVehicle'].'">'.$row['vin'].'</option>';
					$filas .= $new_fila;
				}
				
				$result=$this->selectPieces();
				$pieces = '';
				while($linea = $result->fetch_assoc()) {
					$Piece[] = $linea;
				}
				foreach ($Piece as $row) {
					$new_piece = '<option value="'.$row['idPiece'].'">'.$row['PieceName'].'</option>';
					$pieces .= $new_piece;
				}

				$vista = file_get_contents("./views/inventoryCreate.html");
				$fecha=new DateTime();
				$diccionario=array(
					'{option}' => $filas,
					'{date}' => $fecha->format('Y-m-d H:m:s'),
					'{piece}' => $pieces
				);
				$vista = strtr($vista, $diccionario);
				$data['page_title']='Registrar Inventario';
				$data['general_content']=$vista;
				$this->createTemplate($data);
			}
			else{
				$Correct=TRUE;//Flag to determine if it can create a new Inventory
				$NoSet=FALSE; //Flag to determine if the variables are set
				//Validate variables and if variables is set 
				$Mileage=isset($_POST['mileage'])?$this->validateNumber($_POST['mileage']):$NoSet=TRUE;
				$Gasoline=isset($_POST['gasoline'])?$this->validateNumber($_POST['gasoline']):$NoSet=TRUE;
				$IDPiece=isset($_POST['piece'])?$this->validateID($_POST['piece']):$NoSet=TRUE;
				$Severity=isset($_POST['severity'])?$this->validateText($_POST['severity']):$NoSet=TRUE;
				$IDVehicle=isset($_POST['vehicle'])?$this->validateNumber($_POST['vehicle']):$NoSet=TRUE;
				$Observations=isset($_POST['observations'])?$this->validateText($_POST['observations']):$NoSet=TRUE;
				
				if($NoSet==FALSE){
					
					if($Correct==TRUE){
						//Insert a new Inventory
						$Result=$this->model->create($Mileage,$Gasoline,$IDPiece,$Severity,$IDVehicle,$Observations);
						
						if($Result!=FALSE){
							
							/*require('controllers/mail.php');
							$subject = 'Correo de registro de inventario';
							$body = 'El inventario se registro exitosamente.';
							$mail = new Email($Email, $subject, $body);
							$mail->send();*/
							
							$result=$this->listInventories();
							$vista = file_get_contents("./views/inventoryList.html");
							$inicio_fila = strrpos($vista,'<tr>');
							$final_fila = strrpos($vista,'</tr>') + 5;
							$fila = substr($vista,$inicio_fila,$final_fila-$inicio_fila);
							$filas="";
							$Inventories=array();
							while($linea = $result->fetch_assoc()) {
								$Inventories[] = $linea;
							}
							foreach ($Inventories as $row) {
								$new_fila = $fila;
								$fecha = new DateTime($row['date']);
								$diccionario = array(
									'{id}' => $row['idInventory'], 
									'{status}' => $row['status'],
									'{mileage}' => $row['mileage'],
									'{gasoline}' => $row['gasoline'],
									'{vehicle}' => $row['idVehicle'],
									'{date}' => $fecha->format('Y-m-d H:m:s'),
									);
								$new_fila = strtr($new_fila,$diccionario);
								$filas .= $new_fila;
							}
			
							$alert = file_get_contents("./views/alert.html");
							$diccionario = array(
									'{type}' => 'alert-success',
									'{title}' => '¡Insertado!',
									'{text}' => 'El Inventario se inserto exitosamente.');
							$alert = strtr($alert, $diccionario);
			
							$diccionario = array(
									'{alert}' => $alert);
							$vista = strtr($vista,$diccionario);
			
							$vista = str_replace($fila, $filas, $vista);
							$data['page_title']='Listado de Inventarios';
							$data['general_content']=$vista;
							$this->createTemplate($data);
						}
						else{
							$this->msgError();
						}
					}
					else{
						$this->msgError();
					}
				}
				else{
					$this->msgError();
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
					//require('views/inventorySelected.php');
				}
				else{
					$this->msgError();
					$data['general_content']=file_get_contents('views/Error.html');
					$this->createTemplate($data);
				}
			}
			else{
				$this->msgError();
			}
		}

		/**
		 * Show a list with all active Inventories. Consult database and get the list 
		 */
		private function listInventories(){
			//Select all Inventories
			$result=$this->model->listInventories();
			if(isset($_GET['act']) and $_GET['act']=='list' and $result!=FALSE){
				$vista = file_get_contents("./views/inventoryList.html");
				$inicio_fila = strrpos($vista,'<tr>');
				$final_fila = strrpos($vista,'</tr>') + 5;
				$fila = substr($vista,$inicio_fila,$final_fila-$inicio_fila);
				$filas="";
				$Inventories=array();
				while($linea = $result->fetch_assoc()) {
					$Inventories[] = $linea;
				}
				
				foreach ($Inventories as $row) {
					$new_fila = $fila;
					$fecha = new DateTime($row['date']);
					$diccionario = array(
						'{id}' => $row['idInventory'], 
						'{status}' => $row['status'],
						'{mileage}' => $row['mileage'],
						'{gasoline}' => $row['gasoline'],
						'{vehicle}' => $row['idVehicle'],
						'{date}' => $fecha->format('Y-m-d H:m:s'),
						);
					$new_fila = strtr($new_fila,$diccionario);
					$filas .= $new_fila;
				}
				$alert = file_get_contents("./views/alert.html");
				$diccionario = array(
						'{type}' => 'alert-info',
						'{title}' => '',
						'{text}' => 'Lista de Inventarios.');
				$alert = strtr($alert, $diccionario);
				$diccionario = array(
						'{alert}' => $alert);
				$vista = strtr($vista,$diccionario);

				$vista = str_replace($fila, $filas, $vista);
				$data['page_title']='Listado de Inventarios';
				$data['general_content']=$vista;
				$this->createTemplate($data);
			}
			return $result;
		}

		/**
		 * Create a new piece into system. First validate information
		 */
		private function piece(){
			if(empty($_POST)){
				$data['page_title']='Agregar Pieza';
				$data['general_content']=file_get_contents('./views/pieceCreate.html');
				$this->createTemplate($data);
			}	
			else{
				$NoSet=FALSE; //Flag to determine if the variables are set
				//Validate variables and if variables is set 
				$piece=isset($_POST['piece'])?$this->validateName($_POST['piece']):$NoSet=TRUE;
				if($NoSet==FALSE and $piece!=FALSE){
					$exito=$this->model->piece($piece);
					if($exito){
						$result=$this->listInventories();
						$vista = file_get_contents("./views/inventoryList.html");
						$inicio_fila = strrpos($vista,'<tr>');
						$final_fila = strrpos($vista,'</tr>') + 5;
						$fila = substr($vista,$inicio_fila,$final_fila-$inicio_fila);
						$filas="";
						$Inventories=array();
						while($linea = $result->fetch_assoc()) {
							$Inventories[] = $linea;
						}
						foreach ($Inventories as $row) {
							$new_fila = $fila;
							$fecha = new DateTime($row['date']);
							$diccionario = array(
								'{id}' => $row['idInventory'], 
								'{status}' => $row['status'],
								'{mileage}' => $row['mileage'],
								'{gasoline}' => $row['gasoline'],
								'{vehicle}' => $row['idVehicle'],
								'{date}' => $fecha->format('Y-m-d H:m:s'),
								);
							$new_fila = strtr($new_fila,$diccionario);
							$filas .= $new_fila;
						}
		
						$alert = file_get_contents("./views/alert.html");
						$diccionario = array(
								'{type}' => 'alert-success',
								'{title}' => '¡Insertado!',
								'{text}' => 'El Inventario se inserto exitosamente.');
						$alert = strtr($alert, $diccionario);
		
						$diccionario = array(
								'{alert}' => $alert);
						$vista = strtr($vista,$diccionario);
		
						$vista = str_replace($fila, $filas, $vista);
						$data['page_title']='Listado de Inventarios';
						$data['general_content']=$vista;
						$this->createTemplate($data);
					}
					else {
						$this->msgError();
					}
				}
				else{
					$this->msgError();
				}
			}
		}
		
		
		/**
		 * Modificate the information about a inventory, fist show a list and after select a opcion
		 * show a form where can modificate the information 
		 */
		private function update(){
			if(!isset($_GET['id'])){
				$result=$this->listInventories();
				$vista = file_get_contents("./views/inventoryList.html");
				$inicio_fila = strrpos($vista,'<tr>');
				$final_fila = strrpos($vista,'</tr>') + 5;
				$fila = substr($vista,$inicio_fila,$final_fila-$inicio_fila);
				$filas="";
				$Inventories=array();
				while($linea = $result->fetch_assoc()) {
					$Inventories[] = $linea;
				}
				foreach ($Inventories as $row) {
					$new_fila = $fila;
					$fecha = new DateTime($row['date']);
					$diccionario = array(
						'{id}' => $row['idInventory'], 
						'{status}' => $row['status'],
						'{mileage}' => $row['mileage'],
						'{gasoline}' => $row['gasoline'],
						'{vehicle}' => $row['idVehicle'],
						'{date}' => $fecha->format('Y-m-d H:m:s'),
						);
					$new_fila = strtr($new_fila,$diccionario);
					$filas .= $new_fila;
				}

				$alert = file_get_contents("./views/alert.html");
				$diccionario = array(
						'{type}' => 'alert-info',
						'{title}' => '',
						'{text}' => 'Da click en Modificar para poder cambiar la informacion');
				$alert = strtr($alert, $diccionario);

				$diccionario = array(
						'{alert}' => $alert);
				$vista = strtr($vista,$diccionario);

				$vista = str_replace($fila, $filas, $vista);
				$data['page_title']='Listado de Inventarios';
				$data['general_content']=$vista;
				$this->createTemplate($data);
			}
			else{
				if(empty($_POST)){
					$result=$this->model->select($_GET['id']);
					
					if($result!=FALSE){
						require('models/vehicleMdl.php');
						$vehicleMdl=new VehicleMdl();
						$row=$result->fetch_assoc();
						
						$Result=$vehicleMdl->selectAll();
						$vehicle = '';
						$vehicles=array();
						while($linea = $Result->fetch_assoc()) {
							$vehicles[] = $linea;
						}
						foreach ($vehicles as $linea) {
							$new_fila = '<option value="'.$linea['idVehicle'].'">'.$linea['vin'].'</option>';
							if(strcasecmp($linea['idVehicle'], $row['idVehicle'])==0){
								$new_fila.=$vehicle;
								$vehicle=$new_fila;
							}
							else{
								$vehicle .= $new_fila;
							}
						}
						
						$Hit=$this->model->selectHit($row['idInventory']);
						$Hit=$Hit->fetch_assoc();
						
						$Result=$this->selectPieces();
						$pieces = '';
						while($linea = $Result->fetch_assoc()) {
							$Piece[] = $linea;
						}
						foreach ($Piece as $linea) {
							$new_piece = '<option value="'.$linea['idPiece'].'">'.$linea['PieceName'].'</option>';
							if(strcasecmp($linea['idPiece'], $Hit['idPiece'])==0){
								$new_piece.=$pieces;
								$pieces=$new_piece;
							}
							else{
								$pieces .= $new_piece;
							}
						}
						
						$Severity=array('Perdida Total','Grave','Raspon','Sin Daño');
						$severity='';
						$tam=count($Severity);
						$pos=0;
						while ($pos<$tam) {
							$new_severity = '<option value="'.$Severity[$pos].'">'.$Severity[$pos].'</option>';
							if(strcasecmp($Severity[$pos], $Hit['Severity'])==0){
								$new_severity.=$severity;
								$severity=$new_severity;
							}
							else{
								$severity .= $new_severity;
							}
							$pos+=1;
						}
						$vista = file_get_contents("./views/inventoryUpdate.html");
						
						$fecha = new DateTime($row['date']);
						$diccionario = array(
							'{id}' => $row['idInventory'], 
							'{mileage}' => $row['mileage'],
							'{gasoline}' => $row['gasoline'],
							'{vehicle}' => $vehicle,
							'{observation}' => $row['observations'],
							'{date}' => $fecha->format('Y-m-d H:m:s'),
							'{piece}' => $pieces,
							'{severity}' => $severity
							);
						
						$vista = strtr($vista,$diccionario);
						$data['page_title']='Modificar Inventario';
						$data['general_content']=$vista;
						$this->createTemplate($data);
					}
				}
				else{
					$Correct=TRUE;//Flag to determine if it can create a new Inventory
					$NoSet=FALSE; //Flag to determine if the variables are set
					//Validate variables and if variables is set 
					$Mileage=isset($_POST['mileage'])?$this->validateNumber($_POST['mileage']):$NoSet=TRUE;
					$Gasoline=isset($_POST['gasoline'])?$this->validateNumber($_POST['gasoline']):$NoSet=TRUE;
					$IDPiece=isset($_POST['piece'])?$this->validateID($_POST['piece']):$NoSet=TRUE;
					$Severity=isset($_POST['severity'])?$this->validateText($_POST['severity']):$NoSet=TRUE;
					$IDVehicle=isset($_POST['vehicle'])?$this->validateID($_POST['vehicle']):$NoSet=TRUE;
					
					if(isset($_POST['observations'])){
						$Observations=$this->validateText($_POST['observations']);
						if($Observations==FALSE){
							$Observations='';
						}
					}
					else{
						$Observations='';
					}
					
					if($NoSet==FALSE){
						
						if($Correct==TRUE){
							//Insert a new Inventory
							$Result=$this->model->update($_GET['id'],$Mileage,$Gasoline,$IDPiece,$Severity,$IDVehicle,$Observations);
							
							if($Result!=FALSE){
								
								$result=$this->listInventories();
								$vista = file_get_contents("./views/inventoryList.html");
								$inicio_fila = strrpos($vista,'<tr>');
								$final_fila = strrpos($vista,'</tr>') + 5;
								$fila = substr($vista,$inicio_fila,$final_fila-$inicio_fila);
								$filas="";
								$Inventories=array();
								while($linea = $result->fetch_assoc()) {
									$Inventories[] = $linea;
								}
								foreach ($Inventories as $row) {
									$new_fila = $fila;
									$fecha = new DateTime($row['date']);
									$diccionario = array(
										'{id}' => $row['idInventory'], 
										'{status}' => $row['status'],
										'{mileage}' => $row['mileage'],
										'{gasoline}' => $row['gasoline'],
										'{vehicle}' => $row['idVehicle'],
										'{date}' => $fecha->format('Y-m-d H:m:s'),
										);
									$new_fila = strtr($new_fila,$diccionario);
									$filas .= $new_fila;
								}
				
								$alert = file_get_contents("./views/alert.html");
								$diccionario = array(
										'{type}' => 'alert-success',
										'{title}' => '¡Modificado!',
										'{text}' => 'El Inventario se modifico exitosamente.');
								$alert = strtr($alert, $diccionario);
				
								$diccionario = array(
										'{alert}' => $alert);
								$vista = strtr($vista,$diccionario);
				
								$vista = str_replace($fila, $filas, $vista);
								$data['page_title']='Listado de Inventarios';
								$data['general_content']=$vista;
								$this->createTemplate($data);
							}
							else{
								$this->msgError();
							}
						}
						else{
							$this->msgError();
						}
					}
					else{
						$this->msgError();
					}
				}
			}
		}
		
		/**
		 * Delete a inventory from the system.
		 */
		private function delete(){
			if(!isset($_GET['id'])){
				$result=$this->listInventories();
				$vista = file_get_contents("./views/inventoryList.html");
				$inicio_fila = strrpos($vista,'<tr>');
				$final_fila = strrpos($vista,'</tr>') + 5;
				$fila = substr($vista,$inicio_fila,$final_fila-$inicio_fila);
				$filas="";
				$Inventories=array();
				while($linea = $result->fetch_assoc()) {
					$Inventories[] = $linea;
				}
				foreach ($Inventories as $row) {
					$new_fila = $fila;
					$fecha = new DateTime($row['date']);
					$diccionario = array(
						'{id}' => $row['idInventory'], 
						'{status}' => $row['status'],
						'{mileage}' => $row['mileage'],
						'{gasoline}' => $row['gasoline'],
						'{vehicle}' => $row['idVehicle'],
						'{date}' => $fecha->format('Y-m-d H:m:s'),
						);
					$new_fila = strtr($new_fila,$diccionario);
					$filas .= $new_fila;
				}

				$alert = file_get_contents("./views/alert.html");
				$diccionario = array(
						'{type}' => 'alert-info',
						'{title}' => '',
						'{text}' => 'Da click en Eliminar para quitar del sistema.');
				$alert = strtr($alert, $diccionario);

				$diccionario = array(
						'{alert}' => $alert);
				$vista = strtr($vista,$diccionario);

				$vista = str_replace($fila, $filas, $vista);
				$data['page_title']='Listado de Inventarios';
				$data['general_content']=$vista;
				$this->createTemplate($data);
			}
			else{
				$NoSet=FALSE;
				$ID=isset($_GET['id'])?$this->validateID($_GET['id']):$NoSet=TRUE;
				if(!$NoSet and $ID!=FALSE){
					$Result=$this->model->delete($ID);
					if($Result){
						$result=$this->listInventories();
						$vista = file_get_contents("./views/inventoryList.html");
						$inicio_fila = strrpos($vista,'<tr>');
						$final_fila = strrpos($vista,'</tr>') + 5;
						$fila = substr($vista,$inicio_fila,$final_fila-$inicio_fila);
						$filas="";
						$Inventories=array();
						while($linea = $result->fetch_assoc()) {
							$Inventories[] = $linea;
						}
						foreach ($Inventories as $row) {
							$new_fila = $fila;
							$fecha = new DateTime($row['date']);
							$diccionario = array(
								'{id}' => $row['idInventory'], 
								'{status}' => $row['status'],
								'{mileage}' => $row['mileage'],
								'{gasoline}' => $row['gasoline'],
								'{vehicle}' => $row['idVehicle'],
								'{date}' => $fecha->format('Y-m-d H:m:s'),
								);
							$new_fila = strtr($new_fila,$diccionario);
							$filas .= $new_fila;
						}
		
						$alert = file_get_contents("./views/alert.html");
						$diccionario = array(
								'{type}' => 'alert-success',
								'{title}' => '¡Modificado!',
								'{text}' => 'El inventario fue exitosamente eliminado.');
						$alert = strtr($alert, $diccionario);
		
						$diccionario = array(
								'{alert}' => $alert);
						$vista = strtr($vista,$diccionario);
		
						$vista = str_replace($fila, $filas, $vista);
						$data['page_title']='Listado de Inventarios';
						$data['general_content']=$vista;
						$this->createTemplate($data);
					}
					else{
						$this->msgError();
					}
				}
				else{
					$this->msgError();
				}
			}
		}

		/**
		 * Get all register pieces 
		 */
		private function selectPieces(){
			$Pieces=$this->model->selectPieces();
			return $Pieces;
		}
	}
?>