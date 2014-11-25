<?php
	class VehicleCtrl extends StandardCtrl{
		private $model;
		
		/**
		 *Execute actions based on the selected action
		 *from user in query args
		 */
		function VehicleCtrl() {
			parent::__construct();
		 	require('models/vehicleMdl.php');
		 	//require('controllers/validationCtrl.php');
		 	$this->model = new VehicleMdl();
		}
	
		function run() {
			if(isset($_GET['act'])) {
				switch($_GET['act']) {
					case 'fileLoad':
						break;
					case 'create':
						//user is valid and have permission
						if($this->isLogged()){
		  					if($this->isManager() or $this->isUser()){
		  						if(empty($_POST)){
		  							require_once('models/locationMdl.php');
		  							$locationMdl = new LocationMdl();
		  							$resultQuery  = $locationMdl->selectAll();

		  							$ubicaciones = array();
									while($fila = $resultQuery->fetch_assoc()) {
										$ubicaciones[] = $fila;
									}

									$filas = '';
									foreach ($ubicaciones as $row) {
										$labelText = $row['locationName'] . '-' . $row['extraLocation'];
										$valueId = $row['idLocation'];
										$new_fila = '<option value="'.$valueId.'">'.$labelText.'</option>';
										$filas .= $new_fila;
									}

									$now = new DateTime();
									$general_content = file_get_contents("./views/vehicleForm.html");
									$general_content = str_replace('{options}', $filas, $general_content);
									$general_content = str_replace('{todays_date}', $now->format('Y-m-d'), $general_content);

		  							$data = array(
										'page_title' => "Insertar vehiculo",
										'general_content' => $general_content
									);
									$this->createTemplate($data);
								} else {
		  							$this->create();
		  						}
							} else {
								$this->noAccess();
							}
		  				}
						else{
							$this->goHome();
						}
						break;
					case 'select':
						//user is valid and have permission
						if($this->isLogged()){
		  					if($this->isManager() or $this->isUser()){
		  						if(!isset($_GET['id'])) {
		  							$vista = file_get_contents("./views/vehicleList.html");
		  							$resultQuery = $this->model->selectAll();

									$vehiculos = array();
									while($fila = $resultQuery->fetch_assoc()) {
										$vehiculos[] = $fila;
									}

									$inicio_fila = strrpos($vista,'<tr>');
									$final_fila = strrpos($vista,'</tr>') + 5;
									$fila = substr($vista,$inicio_fila,$final_fila-$inicio_fila);

									$filas = '';
									foreach ($vehiculos as $row) {
										$new_fila = $fila;
										$diccionario = array(
											'{id}' => $row['idVehicle'],
											'{vin}' => $row['vin'], 
											'{brand}' => $row['brand'],
											'{type}' => $row['type'], 
											'{model}' => $row['model']);
										$new_fila = strtr($new_fila,$diccionario);
										$filas .= $new_fila;
									}

									$alert = file_get_contents("./views/alert.html");
									$diccionario = array(
											'{type}' => 'alert-info',
											'{title}' => '',
											'{text}' => 'Da click al VIN para mas informacion del vehiculo.');
									$alert = strtr($alert, $diccionario);

									$diccionario = array(
											'{alert}' => $alert);
									$vista = strtr($vista,$diccionario);

									$vista = str_replace($fila, $filas, $vista);

									$data = array(
										'page_title' => "Lista de vehiculos",
										'general_content' => $vista
									);
		  							$this->createTemplate($data);
		  						} else {
		  							$this->select();
		  						}
		  					}
							else{
								$this->noAccess();
							}
		  				}
						else{
							$this->goHome();
						}
						break;
					case 'selectAll':
						//user is valid and have permission
						if($this->isLogged()){
		  					if($this->isManager() or $this->isUser()){
		  						$this->selectAll();
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
						//user is valid and have permission
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
						//user is valid and have permission
						if($this->isLogged()){
		  					if($this->isManager()){
		  						if(!isset($_GET['id'])) {
		  							$vista = file_get_contents("./views/vehicleList.html");
		  							$resultQuery = $this->model->selectAll();

									$vehiculos = array();
									while($fila = $resultQuery->fetch_assoc()) {
										$vehiculos[] = $fila;
									}

									$inicio_fila = strrpos($vista,'<tr>');
									$final_fila = strrpos($vista,'</tr>') + 5;
									$fila = substr($vista,$inicio_fila,$final_fila-$inicio_fila);

									$filas = '';
									foreach ($vehiculos as $row) {
										$new_fila = $fila;
										$diccionario = array(
											'{id}' => $row['idVehicle'],
											'{vin}' => $row['vin'], 
											'{brand}' => $row['brand'],
											'{type}' => $row['type'], 
											'{model}' => $row['model']);
										$new_fila = strtr($new_fila,$diccionario);
										$filas .= $new_fila;
									}

									$alert = file_get_contents("./views/alert.html");
									$diccionario = array(
											'{type}' => 'alert-info',
											'{title}' => '',
											'{text}' => 'Da click en Editar para actualizar la informacion del vehiculo.');
									$alert = strtr($alert, $diccionario);

									$diccionario = array(
											'{alert}' => $alert);
									$vista = strtr($vista,$diccionario);

									$vista = str_replace($fila, $filas, $vista);

									$data = array(
										'page_title' => "Lista de vehiculos",
										'general_content' => $vista
									);
		  							$this->createTemplate($data);
		  						} else if(empty($_POST)) {
		  							$vista = file_get_contents("./views/vehicleUpdate.html");
		  							$resultQuery = $this->model->select($_GET['id']);

		  							$vehiculo = array();
									while($fila = $resultQuery->fetch_assoc()) {
										$vehiculo[] = $fila;
									}

									$diccionario = array(
										'{id}' => $_GET['id'],
										'{vin}' => $vehiculo[0]['vin'], 
										'{brand}' => $vehiculo[0]['brand'],
										'{type}' => $vehiculo[0]['type'],
										'{model}' => $vehiculo[0]['model']);

									$vista = strtr($vista, $diccionario);

		  							$data = array(
										'page_title' => "Actualizar vehiculo",
										'general_content' => $vista
									);
		  							$this->createTemplate($data);
		  						} else {
		  							$this->update();
		  						}
		  					}
							else{
								$this->noAccess();
							}
		  				}
						else{
							$this->goHome();
						}
						break;
					case 'change':
						//User is valid and have permissions
						if($this->isLogged()){
		  					if($this->isManager() or $this->isUser()){
		  						$this->changeLocation();
		  					}
							else{
								$this->noAccess();
							}
		  				}
						else{
							$this->goHome();
						}
						break;
					case 'exit':
						if($this->isLogged()){
		  					if($this->isManager() or $this->isUser()){
		  						$this->exitVehicle();
		  					}
							else{
								$this->noAccess();
							}
		  				}
						else{
							$this->goHome();
						}
						break;
					case 'vehicles':
						if($this->isLogged()){
		  					if($this->isManager() or $this->isUser() or $this->isClient()){
		  						$this->vehicles();
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
			} else {
				if($this->isLogged()){
					$data = array(
						'page_title' => "Vehiculo",
						'general_content' => file_get_contents("views/vehicleMenu.html")
					);
					$this->createTemplate($data);
				}
				else{
					$this->goHome();
				}
			}
		}
	
		/**
		 *Delete a vehicle
		 *gets the ID and deletes it from DB
		 */
		private function delete() {
			//validate variable
			$idVehicle = isset($_GET['id']) ? $this->validateNumber($_GET['id']) : '';
			//use model to delete
			$result = $this->model->delete($idVehicle);
	
			//delete successful
			if($result) {
				$vista = file_get_contents("./views/vehicleList.html");
				$resultQuery = $this->model->selectAll();

				$vehiculos = array();
				while($fila = $resultQuery->fetch_assoc()) {
					$vehiculos[] = $fila;
				}

				$inicio_fila = strrpos($vista,'<tr>');
				$final_fila = strrpos($vista,'</tr>') + 5;
				$fila = substr($vista,$inicio_fila,$final_fila-$inicio_fila);

				$filas = '';
				foreach ($vehiculos as $row) {
					$new_fila = $fila;
					$diccionario = array(
						'{id}' => $row['idVehicle'],
						'{vin}' => $row['vin'], 
						'{brand}' => $row['brand'],
						'{type}' => $row['type'], 
						'{model}' => $row['model']);
					$new_fila = strtr($new_fila,$diccionario);
					$filas .= $new_fila;
				}

				$alert = file_get_contents("./views/alert.html");
				$diccionario = array(
						'{type}' => 'alert-success',
						'{title}' => '¡Eliminado!',
						'{text}' => 'El vehiculo se elimino exitosamente.');
				$alert = strtr($alert, $diccionario);

				$diccionario = array(
						'{alert}' => $alert);
				$vista = strtr($vista,$diccionario);

				$vista = str_replace($fila, $filas, $vista);

				$data = array(
					'page_title' => "Lista de vehiculos",
					'general_content' => $vista
				);
				$this->createTemplate($data);
				//require('views/vehicleDeleted.php');
			} else {
				$this->msgError();
				$data['general_content']=file_get_contents('views/Error.html');
				$this->createTemplate($data);
			}
		}
	
		/**
		 *Inserts a new vehicle
		 */
		private function create() {
			//validate variables, validar si esta seteado y que sea lo que queremos
			$vin = isset($_POST['vin']) ? $this->validateTextNumber($_POST['vin']) : '';
			$brand = isset($_POST['brand']) ? $this->validateTextNumber($_POST['brand']) : '';
			$type = isset($_POST['type']) ? $this->validateTextNumber($_POST['type']) : '';
			$model = isset($_POST['model']) ? $this->validateNumber($_POST['model']) : '';
			$idLocation = isset($_POST['idLocation']) ? $this->validateNumber($_POST['idLocation']) : '';
			$idUser = isset($_SESSION['IDuser']) ? $this->validateNumber($_SESSION['IDuser']) : '';
			$date = isset($_POST['date']) ? $this->validateDateTime($_POST['date']) : '';
			$reason = isset($_POST['reason']) ? $this->validateTextNumber($_POST['reason']) : '';
			$idOwner = isset($_POST['idUser']) ? $this->validateTextNumber($_POST['idUser']) : '';
	
			//use model to insert
			$result = $this->model->create($vin, $brand, $type, $model, $idLocation, $idUser, $date, $reason, $idOwner);
			//insert successful
			if($result) {
				
				$filas = '<option value="'.$result.'">'.$vin.'</option>';
				
				require('models/inventoryMdl.php');
				$inventoryMdl=new InventoryMdl();
				$result=$inventoryMdl->selectPieces();
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
			} else {
				$this->msgError();
			}
		}
	
		/**
		 *Gets a vehicle info
		 */
		private function select() {
			//validate variables
			$idVehicle = isset($_GET['id']) ? $this->validateNumber($_GET['id']) : '';
	
			//use model to select
			$result = $this->model->select($idVehicle);
	
			//select successful
			if($result) {
				$vista = file_get_contents("./views/vehicleInfo.html");

				$vehiculos = array();
				while($fila = $result->fetch_assoc()) {
					$vehiculos[] = $fila;
				}

				$result = $this->model->selectVL($_GET['id']);
				$ubucacion = array();
				while($fila = $result->fetch_assoc()) {
					$ubucacion[] = $fila;
				}

				$diccionario = array(
					'{id}' => $_GET['id'],
					'{vin}' => $vehiculos[0]['vin'], 
					'{brand}' => $vehiculos[0]['brand'],
					'{type}' => $vehiculos[0]['type'], 
					'{model}' => $vehiculos[0]['model'],
					'{usuario}' => $ubucacion[0]['user'],
					'{ubicacion}' => $ubucacion[0]['locationName'] . $ubucacion[0]['extraLocation'],
					'{fecha}' => substr($ubucacion[0]['date'], 0, 10),
					'{razon}' => $ubucacion[0]['reason']);

				$vista = strtr($vista, $diccionario);
				$data = array(
					'page_title' => "Informacion del vehiculo",
					'general_content' => $vista
				);
				$this->createTemplate($data);
			} else {
				$this->msgError();
			}
		}

		/**
		 *Gets a vehicle info
		 */
		private function selectAll() {
			//use model to select
			$result = $this->model->selectAll();
	
			//select successful
			if($result) {
				//load the view
				//require('views/vehicleSelected.php');
			} else {
				$this->msgError();
			}
		}
	
		/**
		 *Updates a vehicle
		 */
		private function update() {
			$idVehicle = isset($_GET['id']) ? $this->validateNumber($_GET['id']) : '';
			$brand = isset($_POST['brand']) ? $this->validateTextNumber($_POST['brand']) : '';
			$type = isset($_POST['type']) ? $this->validateTextNumber($_POST['type']) : '';
			$model = isset($_POST['model']) ? $this->validateNumber($_POST['model']) : '';
	
			//use model to insert
			$result = $this->model->update($idVehicle, $brand, $type, $model);
	
			//insert successful
			if($result) {
				$vista = file_get_contents("./views/vehicleList.html");
				$resultQuery = $this->model->selectAll();

				$vehiculos = array();
				while($fila = $resultQuery->fetch_assoc()) {
					$vehiculos[] = $fila;
				}

				$inicio_fila = strrpos($vista,'<tr>');
				$final_fila = strrpos($vista,'</tr>') + 5;
				$fila = substr($vista,$inicio_fila,$final_fila-$inicio_fila);

				$filas = '';
				foreach ($vehiculos as $row) {
					$new_fila = $fila;
					$diccionario = array(
						'{id}' => $row['idVehicle'],
						'{vin}' => $row['vin'], 
						'{brand}' => $row['brand'],
						'{type}' => $row['type'], 
						'{model}' => $row['model']);
					$new_fila = strtr($new_fila,$diccionario);
					$filas .= $new_fila;
				}
				$vista = str_replace($fila, $filas, $vista);

				$alert = file_get_contents("./views/alert.html");
				$diccionario = array(
						'{type}' => 'alert-success',
						'{title}' => '¡Actualizado!',
						'{text}' => 'Vehiculo actualizado exitosamente.');
				$alert = strtr($alert, $diccionario);

				$diccionario = array(
						'{alert}' => $alert);
				$vista = strtr($vista,$diccionario);

				$data = array(
					'page_title' => "Lista de vehiculos",
					'general_content' => $vista
				);
				$this->createTemplate($data);
			} else {
				$this->msgError();
			}
		}
		
		/**
		 * Change vehicle´s location 
		 */
		private function changeLocation(){
			if(empty($_POST)){
				require_once('models/locationMdl.php');
				$locationMdl = new LocationMdl();
				$resultQuery  = $locationMdl->selectAll();
				$vehicle = $this->model->select($_GET['id']);
				$vehicle = $vehicle->fetch_assoc();
				$location = $this->model->selectVL($_GET['id']);
				$location = $location->fetch_assoc();
				$ubicaciones = array();
				while($fila = $resultQuery->fetch_assoc()) {
					$ubicaciones[] = $fila;
				}

				$filas = '';
				foreach ($ubicaciones as $row) {
					$labelText = $row['locationName'] . '-' . $row['extraLocation'];
					$valueId = $row['idLocation'];
					$new_fila = '<option value="'.$valueId.'">'.$labelText.'</option>';
					if(strcasecmp($location['locationName'], $row['locationName'])==0 and strcasecmp($location['extraLocation'], $row['extraLocation'])==0){
						$new_fila.=$filas;
						$filas=$new_fila;
					}
					else{
						$filas .= $new_fila;
					}
				}

				$now = new DateTime();
				$general_content = file_get_contents("./views/vehicleChange.html");
				
				$diccionario=array(
					'{id}' => $_GET['id'],
					'{vin}' => $vehicle['vin'],
					'{option}' => $filas,
					'{date}' => $now->format('Y-m-d H:m:S')
				);
				$general_content=strtr($general_content, $diccionario);

				$data = array(
					'page_title' => "Cambiar Ubicación",
					'general_content' => $general_content
				);
				$this->createTemplate($data);
			}
			else{
				$Correct=TRUE;//Flag to determine if it can create a new Inventory
				$NoSet=FALSE; //Flag to determine if the variables are set
				//Validate variables and if variables is set 
				$IDLocation=isset($_POST['location'])?$this->validateID($_POST['location']):$NoSet=TRUE;
				$IDVehicle=isset($_POST['id'])?$this->validateID($_POST['id']):$NoSet=TRUE;
				$IDUser=isset($_SESSION['IDuser'])?$this->validateID($_SESSION['IDuser']):$NoSet=TRUE;
				$Reason=isset($_POST['reason'])?$this->validateText($_POST['reason']):$NoSet=TRUE;
				
				if($NoSet==FALSE){
					if($IDLocation==FALSE){
						$Correct=FALSE;
					}
					elseif($IDVehicle==FALSE){
						$Correct=FALSE;
					}
					elseif($IDUser==FALSE){
						$Correct=FALSE;
					}
					elseif ($Reason==FALSE) {
						$Correct=FALSE;
					}
					if($Correct==TRUE){
						//Change vehicle´s location
						$Result=$this->model->changeLocation($IDLocation,$IDUser,$IDVehicle,$Reason);
						
						if($Result==TRUE){
							$vista = file_get_contents("./views/vehicleInfoNew.html");

							$result = $this->model->select($IDVehicle);
							$vehiculos = array();
							while($fila = $result->fetch_assoc()) {
								$vehiculos[] = $fila;
							}
			
							$result = $this->model->selectVL($IDVehicle);
							$ubicacion = array();
							while($fila = $result->fetch_assoc()) {
								$ubicacion[] = $fila;
							}
							$last=count($ubicacion)-1;
							$diccionario = array(
								'{id}' => $IDVehicle,
								'{vin}' => $vehiculos[0]['vin'], 
								'{brand}' => $vehiculos[0]['brand'],
								'{type}' => $vehiculos[0]['type'], 
								'{model}' => $vehiculos[0]['model'],
								'{usuario}' => $ubicacion[$last]['user'],
								'{ubicacion}' => $ubicacion[$last]['locationName'] . $ubicacion[$last]['extraLocation'],
								'{fecha}' => substr($ubicacion[$last]['date'], 0, 10),
								'{razon}' => $ubicacion[$last]['reason']);
			
							$vista = strtr($vista, $diccionario);
							$data = array(
								'page_title' => "Informacion del vehiculo",
								'general_content' => $vista
							);
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
		 * Create a new inventory but registering actual state and compare 
		 */
		private function exitVehicle(){
			if(!isset($_GET['id'])){
				$result=$this->model->admissionInventory();
				$vista = file_get_contents("./views/vehicleExitList.html");
				$inicio_fila = strrpos($vista,'<tr>');
				$final_fila = strrpos($vista,'</tr>') + 5;
				$fila = substr($vista,$inicio_fila,$final_fila-$inicio_fila);
				$filas="";
				$action="";
				$Inventories=array();
				while($linea = $result->fetch_assoc()) {
					$Inventories[] = $linea;
				}
				foreach ($Inventories as $row) {
					if(strcasecmp($row['status'], 'ADMISSION')==0){
						$new_fila = $fila;
						$fecha = new DateTime($row['date']);
						$id=$row['idVehicle'];
						$diccionario = array(
							'{id}' => $id, 
							'{status}' => $row['status'],
							'{mileage}' => $row['mileage'],
							'{gasoline}' => $row['gasoline'],
							'{vehicle}' => $row['idVehicle'],
							'{date}' => $fecha->format('Y-m-d H:m:s'),
							'{action}' => "<a href='?ctrl=vehicle&act=exit&id=$id'>Dar Salida</a>"
							);
						$new_fila = strtr($new_fila,$diccionario);
						$filas .= $new_fila;
					}
				}
				$alert = file_get_contents("./views/alert.html");
				$diccionario = array(
						'{type}' => 'alert-info',
						'{title}' => '',
						'{text}' => 'Da click en Dar Salida para hacer la salida de un Vehiculo.');
				$alert = strtr($alert, $diccionario);
				$diccionario = array(
						'{alert}' => $alert);
				$vista = strtr($vista,$diccionario);

				$vista = str_replace($fila, $filas, $vista);
				$data['page_title']='Salida de Vehiculo';
				$data['general_content']=$vista;
				$this->createTemplate($data);
			}
			else{
				if(empty($_POST)){
					require('./models/inventoryMdl.php');
					$InventoryMdl=new InventoryMdl();
					$Piece=$InventoryMdl->selectPieces();
					$pieces = '';
					foreach ($Piece as $row) {
						$new_piece = '<option value="'.$row['idPiece'].'">'.$row['PieceName'].'</option>';
						$pieces .= $new_piece;
					}
	
					$vista = file_get_contents("./views/vehicleExit.html");
					$fecha=new DateTime();
					$diccionario=array(
						'{vehicle}' => $_GET['id'],
						'{date}' => $fecha->format('Y-m-d H:m:s'),
						'{piece}' => $pieces
					);
					$vista = strtr($vista, $diccionario);
					$data['page_title']='Salida de Vehiculo';
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
							$Result=$this->model->exitVehicle($Mileage,$Gasoline,$IDPiece,$Severity,$IDVehicle,$Observations);
							
							if($Result!=FALSE){
								require('models/inventoryMdl.php');
								$InventoryMdl=new InventoryMdl();
								$Inventories=$InventoryMdl->listInventories();
								$vista = file_get_contents("./views/vehicleExitList.html");
								$inicio_fila = strrpos($vista,'<tr>');
								$final_fila = strrpos($vista,'</tr>') + 5;
								$fila = substr($vista,$inicio_fila,$final_fila-$inicio_fila);
								$filas="";
								$action="";
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
										'{action}' => ''
										);
									$new_fila = strtr($new_fila,$diccionario);
									$filas .= $new_fila;
								}
				
								$alert = file_get_contents("./views/alert.html");
								$diccionario = array(
										'{type}' => 'alert-success',
										'{title}' => '¡Exito!',
										'{text}' => 'El Salida exitosa del vehiculo.');
								$alert = strtr($alert, $diccionario);
				
								$diccionario = array(
										'{alert}' => $alert);
								$vista = strtr($vista,$diccionario);
				
								$vista = str_replace($fila, $filas, $vista);
								$data['page_title']='Salida de Vehiculos';
								$data['general_content']=$vista;
								$this->createTemplate($data);
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
					else{
						$this->msgError();
					}
				}
			}
		}

		/**
		 * Shown a list with user's vehicles
		 */
		private function vehicles(){
			$NoSet=FALSE;
			$ID=isset($_SESSION['IDuser'])?$this->validateID($_SESSION['IDuser']):$NoSet=TRUE;
			if(!$NoSet and $ID){
				$result=$this->model->vehicles($ID);
				if($result){
					$vista = file_get_contents("./views/vehicleMyList.html");

					$vehiculos = array();
					while($fila = $result->fetch_assoc()) {
						$vehiculos[] = $fila;
					}

					$inicio_fila = strrpos($vista,'<tr>');
					$final_fila = strrpos($vista,'</tr>') + 5;
					$fila = substr($vista,$inicio_fila,$final_fila-$inicio_fila);

					$filas = '';
					foreach ($vehiculos as $row) {
						$new_fila = $fila;
						$diccionario = array(
							'{vin}' => $row['vin'], 
							'{brand}' => $row['brand'],
							'{type}' => $row['type'], 
							'{model}' => $row['model']);
						$new_fila = strtr($new_fila,$diccionario);
						$filas .= $new_fila;
					}

					$alert = file_get_contents("./views/alert.html");
					$diccionario = array(
							'{type}' => 'alert-info',
							'{title}' => '',
							'{text}' => 'Esta es la lista de tus vehiculos.');
					$alert = strtr($alert, $diccionario);

					$diccionario = array(
							'{alert}' => $alert);
					$vista = strtr($vista,$diccionario);

					$vista = str_replace($fila, $filas, $vista);

					$data = array(
						'page_title' => "Mis Vehiculos",
						'general_content' => $vista
					);
					$this->createTemplate($data);
				}
			}
			else{
				$this->msgError();
			}
		}
	}
?>