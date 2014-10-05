<?php
	class VehicleCtrl extends ValidationCtrl{
		private $model;
		
		/**
		 *Execute actions based on the selected action
		 *from user in query args
		 */
		function VehicleCtrl() {
		 	require('models/vehicleMdl.php');
		 	//require('controllers/validationCtrl.php');
		 	$this->model = new VehicleMdl();
		}
	
		function run() {
			switch($_GET['act']) {
				case 'create':
					//user is valid and have permission
					$this->create();
					break;
				case 'select':
					//user is valid and have permission
					$this->select();
					break;
				case 'selectAll':
					//user is valid and have permission
					$this->selectAll();
					break;
				case 'delete':
					//user is valid and have permission
					$this->delete();
					break;
				case 'update':
					//user is valid and have permission
					$this->update();
					break;
				case 'change':
					//User is valid and have permissions
					$this->changeLocation();
					break;
				case 'exit':
					$this->exitVehicle();
					break;
				default:
					break;
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
			$idUser = isset($_POST['idUser']) ? $this->validateNumber($_POST['idUser']) : '';
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
			$idVehicle = isset($_POST['idVehicle']) ? $this->validateNumber($_POST['idVehicle']) : '';
	
			//use model to select
			$result = $this->model->select($idVehicle);
	
			//select successful
			if($result) {
				//load the view
				require('views/vehicleSelected.php');
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
			//Validate variables and if variables is set 
			$IDInventory=isset($_POST['IDInventory'])?$this->validateID($_POST['IDInventory']):0;
			$IDUser=isset($_POST['IDUser'])?$this->validateID($_POST['IDUser']):0;
			$Reason=isset($_POST['Reason'])?$this->validateText($_POST['Reason']):'';
			//Information of new Location
			$name = isset($_POST['name'])?$this->validateText($_POST['name']):'';
			$extraLoca = isset($_POST['extraLocations'])?$this->validateText($_POST['extraLocations']):'';
			
			//Change vehicle´s location
			$Result=$this->model->changeLocation($IDInventory,$IDUser,$Reason,$name,$extraLoca);
			
			if($Result){
				require('views/inventoryChanged.php');
			}
			else{
				require('views/Error.php');
			}
		}
	  
		/**
		 * Create a new inventory but registering actual state and compare 
		 */
		private function exitVehicle(){
			//Validate variables and if variables is set 
			$Mileage=isset($_POST['Mileage'])?$this->validateNumber($_POST['Mileage']):0;
			$AmountGasoline=isset($_POST['AmountGasoline'])?$this->validateNumber($_POST['AmountGasoline']):0;
			$Hit=isset($_POST['Hit'])?$this->validateText($_POST['Hit']):'';
			$Severity=isset($_POST['Severity'])?$this->validateText($_POST['Severity']):'';
			$IDUser=isset($_POST['IDUser'])?$this->validateID($_POST['IDUser']):0;
			$vin=isset($_POST['vin'])?$this->validateNumber($_POST['vin']):0;
			$Observations=isset($_POST['Observations'])?$this->validateText($_POST['Observations']):0;
			//Information of Location
			$name = isset($_POST['name'])?$this->validateText($_POST['name']):'';
			$extraLoca = isset($_POST['extraLocations'])?$this->validateText($_POST['extraLocations']):'';
			
			//Insert a new Inventory but information of exit
			$Result=$this->model->exitVehicle($Mileage,$AmountGasoline,$Hit,$Severity,$IDUser,$vin,$Observations,$name,$extraLoca);
			
			if($Result){
				require('views/inventoryExit.php');
			}
			else{
				require('views/Error.php');
			}
		}
	}
?>