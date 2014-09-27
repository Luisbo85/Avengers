<?php
	class VehicleMdl {
		private $vin;
		private $brand;
		private $type;
		private $model;
		private $idVehicle;
		
		function __construct() {
			
		}

		/*
		 *@param int $vin
		 *@param string $brand
		 *@param string $type
		 *@param int $model
		 *@return true in success
		 *Insert in database new vehicle
		 */
		function create($vin, $brand, $type, $model) {
			//conection a la base de datos
			$this->$vin = $vin;
			$this->$brand = $brand;
			$this->$type = $type;
			$this->$model = $model;			

			//if success
			return true;
		}

		/*
		 *@param int $idVehicle
		 *@return true in success
		 *Delete a vehicle
		 */
		function delete($idVehicle) {
			//conection a la base de datos
			$this->$idVehicle = $idVehicle;

			if(empty($this->$idVehicle)) {
                return false;
            }

			//if success
			return true;
		}

		/*
		 *@param int $vin
		 *@param string $brand
		 *@param string $type
		 *@param int $model
		 *@return true in success
		 *Update a vehicle info
		 */
		function update($vin, $brand, $type, $model) {
			//conection a la base de datos
			$this->$vin = $vin;
			$this->$brand = $brand;
			$this->$type = $type;
			$this->$model = $model;			

			//if success
			return true;
		}

		/*
		 *@param int $vin
		 *@param string $brand
		 *@param string $type
		 *@param int $model
		 *@return true in success
		 *Gets a vehicle data
		 */
		function select($vin, $brand, $type, $model) {
			//conection a la base de datos
			$this->$vin = $vin;
			$this->$brand = $brand;
			$this->$type = $type;
			$this->$model = $model;			

			//if success
			return true;
		}
	}
?>