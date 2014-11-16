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
								require('views/NoAccess.php');
							}
		  				}
						else{
							require('views/needLogin.php');
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
										//$new_fila = str_replace('{codigo}', $row['id'], $new_fila);
										//$new_fila = str_replace('{nombre}', $row['nombre'], $new_fila);
										//Reemplazo con un diccionario
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
								require('views/NoAccess.php');
							}
		  				}
						else{
							require('views/needLogin.php');
						}
						break;
					case 'selectAll':
						//user is valid and have permission
						if($this->isLogged()){
		  					if($this->isManager() or $this->isUser()){
		  						$this->selectAll();
		  					}
							else{
								require('views/NoAccess.php');
							}
		  				}
						else{
							require('views/needLogin.php');
						}
						break;
					case 'delete':
						//user is valid and have permission
						if($this->isLogged()){
		  					if($this->isManager()){
		  						$this->delete();
		  					}
							else{
								require('views/NoAccess.php');
							}
		  				}
						else{
							require('views/needLogin.php');
						}
						break;
					case 'update':
						//user is valid and have permission
						if($this->isLogged()){
		  					if($this->isManager() or $this->isUser()){
		  						$this->update();
		  					}
							else{
								require('views/NoAccess.php');
							}
		  				}
						else{
							require('views/needLogin.php');
						}
						break;
					case 'change':
						//User is valid and have permissions
						if($this->isLogged()){
		  					if($this->isManager() or $this->isUser()){
		  						$this->changeLocation();
		  					}
							else{
								require('views/NoAccess.php');
							}
		  				}
						else{
							require('views/needLogin.php');
						}
						break;
					case 'exit':
						if($this->isLogged()){
		  					if($this->isManager() or $this->isUser()){
		  						$this->exitVehicle();
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
			} else {
				$data = array(
					'page_title' => "Vehiculo",
					'general_content' => file_get_contents("views/vehicleMenu.html")
				);
				$this->createTemplate($data);
			}
		}
	
		/**
		 *Delete a vehicle
		 *gets the ID and deletes it from DB
		 */
		private function delete() {
			//validate variable
			$idVehicle = isset($_POST['idVehicle']) ? $this->validateNumber($_POST['idVehicle']) : '';
			//use model to delete
			$result = $this->model->delete($idVehicle);
	
			//delete successful
			if($result) {
				//load the view
				require('views/vehicleDeleted.php');
			} else {
				require('views/Error.php');
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
	
			//use model to insert
			$result = $this->model->create($vin, $brand, $type, $model, $idLocation, $idUser, $date, $reason);
			//insert successful
			if($result) {
				//load the view
				require('views/vehicleInserted.php');
			} else {
				require('views/Error.php');
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

				$diccionario = array(
					'{vin}' => $vehiculos[0]['vin'], 
					'{brand}' => $vehiculos[0]['brand'],
					'{type}' => $vehiculos[0]['type'], 
					'{model}' => $vehiculos[0]['model']);

				$vista = strtr($vista, $diccionario);
				$data = array(
					'page_title' => "Informacion del vehiculo",
					'general_content' => $vista
				);
				$this->createTemplate($data);
			} else {
				require('views/Error.php');
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
				require('views/vehicleSelected.php');
			} else {
				require('views/Error.php');
			}
		}
	
		/**
		 *Updates a vehicle
		 */
		private function update() {
			$idVehicle = isset($_POST['idVehicle']) ? $this->validateNumber($_POST['idVehicle']) : '';
			$brand = isset($_POST['brand']) ? $this->validateTextNumber($_POST['brand']) : '';
			$type = isset($_POST['type']) ? $this->validateTextNumber($_POST['type']) : '';
			$model = isset($_POST['model']) ? $this->validateNumber($_POST['model']) : '';
	
			//use model to insert
			$result = $this->model->update($idVehicle, $brand, $type, $model);
	
			//insert successful
			if($result) {
				//load the view
				require('views/vehicleUpdated.php');
			} else {
				require('views/Error.php');
			}
		}
		
		/**
		 * Change vehicle´s location 
		 */
		private function changeLocation(){
			$Correct=TRUE;//Flag to determine if it can create a new Inventory
			$NoSet=FALSE; //Flag to determine if the variables are set
			//Validate variables and if variables is set 
			$IDLocation=isset($_POST['IDLocation'])?$this->validateID($_POST['IDLocation']):$NoSet=TRUE;
			$IDVehicle=isset($_POST['IDVehicle'])?$this->validateID($_POST['IDVehicle']):$NoSet=TRUE;
			$IDUser=isset($_POST['IDUser'])?$this->validateID($_POST['IDUser']):$NoSet=TRUE;
			$Reason=isset($_POST['Reason'])?$this->validateText($_POST['Reason']):$NoSet=TRUE;
			
			echo '*',$NoSet,'*','<br>';
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
				echo 'Correct=',$Correct,'*','<br>';
				if($Correct==TRUE){
					//Change vehicle´s location
					$Result=$this->model->changeLocation($IDLocation,$IDUser,$IDVehicle,$Reason);
					
					if($Result==TRUE){
						require('views/inventoryChanged.php');
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
		 * Create a new inventory but registering actual state and compare 
		 */
		private function exitVehicle(){
			$Correct=TRUE;//Flag to determine if it can create a new Inventory
			$NoSet=FALSE; //Flag to determine if the variables are set
			//Validate variables and if variables is set 
			$Mileage=isset($_POST['Mileage'])?$this->validateNumber($_POST['Mileage']):$NoSet=TRUE;
			$Gasoline=isset($_POST['Gasoline'])?$this->validateRealNumber($_POST['Gasoline']):$NoSet=TRUE;
			$IDPiece=isset($_POST['IDPiece'])?$this->validateID($_POST['IDPiece']):$NoSet=TRUE;
			$Severity=isset($_POST['Severity'])?$this->validateText($_POST['Severity']):$NoSet=TRUE;
			$IDVehicle=isset($_POST['IDVehicle'])?$this->validateNumber($_POST['IDVehicle']):$NoSet=TRUE;
			$Observations=isset($_POST['Observations'])?$this->validateText($_POST['Observations']):$NoSet=TRUE;
			
			if($NoSet==FALSE){
				
				if($Correct==TRUE){
					//Insert a new Inventory
					$Result=$this->model->exitVehicle($Mileage,$Gasoline,$IDPiece,$Severity,$IDVehicle,$Observations);
					
					if($Result!=FALSE){
						require('views/inventoryExit.php');
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
?>