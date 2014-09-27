<?php
class VehicleCtrl {
	private $model;
	/**
	 *Execute actions based on the selected action
	 *from user in query args
	 */

	 function VehicleCtrl() {
	 	require('models/vehicleMdl.php');
	 	require('controllers/validationCtrl.php');
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
			case 'delete':
				//user is valid and have permission
				$this->delete();
				break;
			case 'update':
				//user is valid and have permission
				$this->update();
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
		$idVehicle = isset($_POST['idVehicle']) ? validateNumber($_POST['idVehicle']) : '';
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
		$vin = isset($_POST['vin']) ? $this->validateNumber($_POST['vin']) : '';
		$brand = isset($_POST['brand']) ? $this->validateNumber($_POST['brand']) : '';
		$type = isset($_POST['type']) ? $this->validateNumber($_POST['type']) : '';
		$model = isset($_POST['model']) ? $this->validateNumber($_POST['model']) : '';

		//use model to insert
		$result = $this->model->create($vin, $brand, $type, $model);

		//insert successful
		if($result) {
			//load the view
			require('views/vehicleInserted.php');
		} else {
			require('views/Error.php');
		}
	}

	/**
	 *Gets a vahicle info
	 */
	private function select() {
		//validate variables, validar si esta seteado y que sea lo que queremos
		$vin = isset($_POST['vin']) ? $this->validateNumber($_POST['vin']) : '';
		$brand = isset($_POST['brand']) ? $this->validateNumber($_POST['brand']) : '';
		$type = isset($_POST['type']) ? $this->validateNumber($_POST['type']) : '';
		$model = isset($_POST['model']) ? $this->validateNumber($_POST['model']) : '';

		//use model to insert
		$result = $this->model->select($vin, $brand, $type, $model);

		//insert successful
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
		//validate variables, validar si esta seteado y que sea lo que queremos
		$vin = isset($_POST['vin']) ? $this->validateNumber($_POST['vin']) : '';
		$brand = isset($_POST['brand']) ? $this->validateNumber($_POST['brand']) : '';
		$type = isset($_POST['type']) ? $this->validateNumber($_POST['type']) : '';
		$model = isset($_POST['model']) ? $this->validateNumber($_POST['model']) : '';

		//use model to insert
		$result = $this->model->update($vin, $brand, $type, $model);

		//insert successful
		if($result) {
			//load the view
			require('views/vehicleUpdated.php');
		} else {
			require('views/Error.php');
		}
	}
}
?>