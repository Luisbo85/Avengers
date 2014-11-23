<?php
	class LocationCtrl extends StandardCtrl{
		private $model;
		
		/**
		 *Execute actions based on the selected action
		 *from user in query args
		 */
		function LocationCtrl() {
			parent::__construct();
		 	require('models/locationMdl.php');
		 	$this->model = new LocationMdl();
		}

		function run() {
			if(isset($_GET['act'])) {
				switch($_GET['act']) {
					case 'create':
						//user is valid and have permission
						if($this->isLogged()){
			  				if($this->isManager() or $this->isUser()){
			  					if(empty($_POST)){
		  							$general_content = file_get_contents("./views/locationForm.html");
									$data = array(
										'page_title' => "Insertar ubicacion",
										'general_content' => $general_content
									);
									$this->createTemplate($data);
								} else {
		  							$this->create();
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
					case 'select':
						//user is valid and have permission
						if($this->isLogged()){
			  				if($this->isManager() or $this->isUser()){
			  					if(!isset($_GET['id'])) {
		  							$vista = file_get_contents("./views/locationList.html");
		  							$resultQuery = $this->model->selectAll();

									$ubicaciones = array();
									while($fila = $resultQuery->fetch_assoc()) {
										$ubicaciones[] = $fila;
									}

									$inicio_fila = strrpos($vista,'<tr>');
									$final_fila = strrpos($vista,'</tr>') + 5;
									$fila = substr($vista,$inicio_fila,$final_fila-$inicio_fila);

									$filas = '';
									foreach ($ubicaciones as $row) {
										$new_fila = $fila;
										$diccionario = array(
											'{id}' => $row['idLocation'],
											'{name}' => $row['locationName'], 
											'{seccion}' => $row['extraLocation']);
										$new_fila = strtr($new_fila,$diccionario);
										$filas .= $new_fila;
									}

									$alert = file_get_contents("./views/alert.html");
									$diccionario = array(
											'{type}' => 'alert-info',
											'{title}' => '',
											'{text}' => 'Da click al nombre para mas informacion de la ubicacion.');
									$alert = strtr($alert, $diccionario);

									$diccionario = array(
											'{alert}' => $alert);
									$vista = strtr($vista,$diccionario);

									$vista = str_replace($fila, $filas, $vista);

									$data = array(
										'page_title' => "Lista de ubicaciones",
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
			  				if($this->isManager() or $this->isUser()){
			  					if(!isset($_GET['id'])) {
		  							$vista = file_get_contents("./views/locationList.html");
		  							$resultQuery = $this->model->selectAll();

									$ubicaciones = array();
									while($fila = $resultQuery->fetch_assoc()) {
										$ubicaciones[] = $fila;
									}

									$inicio_fila = strrpos($vista,'<tr>');
									$final_fila = strrpos($vista,'</tr>') + 5;
									$fila = substr($vista,$inicio_fila,$final_fila-$inicio_fila);

									$filas = '';
									foreach ($ubicaciones as $row) {
										$new_fila = $fila;
										$diccionario = array(
											'{id}' => $row['idLocation'],
											'{name}' => $row['locationName'], 
											'{seccion}' => $row['extraLocation']);
										$new_fila = strtr($new_fila,$diccionario);
										$filas .= $new_fila;
									}

									$alert = file_get_contents("./views/alert.html");
									$diccionario = array(
											'{type}' => 'alert-info',
											'{title}' => '',
											'{text}' => 'Da click a Editar para actuzalizar los datos de ubicacion.');
									$alert = strtr($alert, $diccionario);

									$diccionario = array(
											'{alert}' => $alert);
									$vista = strtr($vista,$diccionario);

									$vista = str_replace($fila, $filas, $vista);

									$data = array(
										'page_title' => "Lista de ubicaciones",
										'general_content' => $vista
									);
		  							$this->createTemplate($data);
		  						} else if(empty($_POST)) {
		  							$vista = file_get_contents("./views/locationUpdate.html");
		  							$resultQuery = $this->model->select($_GET['id']);

		  							$vehiculo = array();
									while($fila = $resultQuery->fetch_assoc()) {
										$vehiculo[] = $fila;
									}

									$diccionario = array(
										'{id}' => $_GET['id'],
										'{name}' => $vehiculo[0]['locationName'], 
										'{seccion}' => $vehiculo[0]['extraLocation']);

									$vista = strtr($vista, $diccionario);

		  							$data = array(
										'page_title' => "Actualizar ubicacion",
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
					default:
						break;
				}
			} else {
				if($this->isLogged()){
					$data = array(
						'page_title' => "Vehiculo",
						'general_content' => file_get_contents("views/locationMenu.html")
					);
					$this->createTemplate($data);
				}
				else{
					$this->goHome();
				}
			}
		}

		/**
		 *Inserts a new location
		 */
		private function create() {
			$name = isset($_POST['name']) ? $this->validateTextNumber($_POST['name']) : '';
			$extraLoca = isset($_POST['extraLocations']) ? $this->validateTextNumber($_POST['extraLocations']) : '';
			
			//use model to insert
			$result = $this->model->create($name, $extraLoca);

			//insert successful
			if($result) {
				$vista = file_get_contents("./views/locationList.html");
				$resultQuery = $this->model->selectAll();

				$ubicaciones = array();
				while($fila = $resultQuery->fetch_assoc()) {
					$ubicaciones[] = $fila;
				}

				$inicio_fila = strrpos($vista,'<tr>');
				$final_fila = strrpos($vista,'</tr>') + 5;
				$fila = substr($vista,$inicio_fila,$final_fila-$inicio_fila);

				$filas = '';
				foreach ($ubicaciones as $row) {
					$new_fila = $fila;
					$diccionario = array(
						'{id}' => $row['idLocation'],
						'{name}' => $row['locationName'], 
						'{seccion}' => $row['extraLocation']);
					$new_fila = strtr($new_fila,$diccionario);
					$filas .= $new_fila;
				}

				$alert = file_get_contents("./views/alert.html");
				$diccionario = array(
						'{type}' => 'alert-success',
						'{title}' => '¡Insetado!',
						'{text}' => 'La ubicacion se inserto exitosamente.');
				$alert = strtr($alert, $diccionario);

				$diccionario = array(
						'{alert}' => $alert);
				$vista = strtr($vista,$diccionario);

				$vista = str_replace($fila, $filas, $vista);

				$data = array(
					'page_title' => "Lista de ubicaciones",
					'general_content' => $vista
				);
				$this->createTemplate($data);
			} else {
				$this->msgError();
			}
		}

		/**
		 *Gets a location info
		 */
		private function select() {
			//validate variables, validar si esta seteado y que sea lo que queremos
			$idLocation = isset($_GET['id']) ? $this->validateNumber($_GET['id']) : '';
			
			//use model to delete
			$result = $this->model->select($idLocation);

			//select successful
			if($result) {
				$vista = file_get_contents("./views/locationInfo.html");

				$ubicacion = array();
				while($fila = $result->fetch_assoc()) {
					$ubicacion[] = $fila;
				}

				$diccionario = array(
					'{name}' => $ubicacion[0]['locationName'], 
					'{seccion}' => $ubicacion[0]['extraLocation']);
				$vista = strtr($vista, $diccionario);

				$resultQuery = $this->model->selectAllVehicles($_GET['id']);
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
						'{idVehicle}' => $row['idVehicle'],
						'{vin}' => $row['vin'], 
						'{brand}' => $row['brand'],
						'{type}' => $row['type']);
					$new_fila = strtr($new_fila,$diccionario);
					$filas .= $new_fila;
				}
				$vista = str_replace($fila, $filas, $vista);

				$data = array(
					'page_title' => "Lista de ubicaciones",
					'general_content' => $vista
				);
				$this->createTemplate($data);
			} else {
				$this->msgError();
				$data['general_content']=file_get_contents('views/Error.html');
				$this->createTemplate($data);
			}
		}

		/**
		 *Gets all locations
		 */
		private function selectAll() {
			//use model to delete
			$result = $this->model->selectAll();

			//select successful
			if($result) {
				//load the view
				require('views/locationSelected.php');
			} else {
				$this->msgError();
			}
		}

		/**
		 *Deletes a location
		 */
		private function delete() {
			//validate variables, validar si esta seteado y que sea lo que queremos
			$idLocation = isset($_GET['id']) ? $this->validateNumber($_GET['id']) : '';
			
			//use model to delete
			$result = $this->model->delete($idLocation);

			//delete successful
			if($result) {
				$vista = file_get_contents("./views/locationList.html");
				$resultQuery = $this->model->selectAll();

				$ubicaciones = array();
				while($fila = $resultQuery->fetch_assoc()) {
					$ubicaciones[] = $fila;
				}

				$inicio_fila = strrpos($vista,'<tr>');
				$final_fila = strrpos($vista,'</tr>') + 5;
				$fila = substr($vista,$inicio_fila,$final_fila-$inicio_fila);

				$filas = '';
				foreach ($ubicaciones as $row) {
					$new_fila = $fila;
					$diccionario = array(
						'{id}' => $row['idLocation'],
						'{name}' => $row['locationName'], 
						'{seccion}' => $row['extraLocation']);
					$new_fila = strtr($new_fila,$diccionario);
					$filas .= $new_fila;
				}

				$alert = file_get_contents("./views/alert.html");
				$diccionario = array(
						'{type}' => 'alert-success',
						'{title}' => 'Eliminado!',
						'{text}' => 'La ubicacion se elimino exitosamente.');
				$alert = strtr($alert, $diccionario);

				$diccionario = array(
						'{alert}' => $alert);
				$vista = strtr($vista,$diccionario);

				$vista = str_replace($fila, $filas, $vista);

				$data = array(
					'page_title' => "Lista de ubicaciones",
					'general_content' => $vista
				);
				$this->createTemplate($data);
			} else {
				$this->msgError();
			}
		}

		/**
		 *Updates a location
		 */
		private function update() {
			//validate variables, validar si esta seteado y que sea lo que queremos
			$idLocation = isset($_GET['id']) ? $this->validateNumber($_GET['id']) : '';
			$name = isset($_POST['name']) ? $this->validateTextNumber($_POST['name']) : '';
			$extraLoca = isset($_POST['extraLocations']) ? $this->validateTextNumber($_POST['extraLocations']) : '';
			
			//use model to updte
			$result = $this->model->update($idLocation, $name, $extraLoca);

			//update successful
			if($result) {
				$vista = file_get_contents("./views/locationList.html");
				$resultQuery = $this->model->selectAll();

				$ubicaciones = array();
				while($fila = $resultQuery->fetch_assoc()) {
					$ubicaciones[] = $fila;
				}

				$inicio_fila = strrpos($vista,'<tr>');
				$final_fila = strrpos($vista,'</tr>') + 5;
				$fila = substr($vista,$inicio_fila,$final_fila-$inicio_fila);

				$filas = '';
				foreach ($ubicaciones as $row) {
					$new_fila = $fila;
					$diccionario = array(
						'{id}' => $row['idLocation'],
						'{name}' => $row['locationName'], 
						'{seccion}' => $row['extraLocation']);
					$new_fila = strtr($new_fila,$diccionario);
					$filas .= $new_fila;
				}

				$alert = file_get_contents("./views/alert.html");
				$diccionario = array(
						'{type}' => 'alert-success',
						'{title}' => 'Actualizado!',
						'{text}' => 'La ubicacion se actualizo exitosamente.');
				$alert = strtr($alert, $diccionario);

				$diccionario = array(
						'{alert}' => $alert);
				$vista = strtr($vista,$diccionario);

				$vista = str_replace($fila, $filas, $vista);

				$data = array(
					'page_title' => "Lista de ubicaciones",
					'general_content' => $vista
				);
				$this->createTemplate($data);
			} else {
				$this->msgError();
			}
		}
	}
?>