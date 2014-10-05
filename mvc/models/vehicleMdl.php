<?php
	class VehicleMdl {
		private $vin;
		private $brand;
		private $type;
		private $model;
		private $idVehicle;
		
		function __construct() {
			require("databaseConfig.inc");
			$this->bdDriver = new mysqli($host, $user, $pass, $bd);
			if($this->bdDriver->connect_error) {
				die("no se puede conectar");
			}
		}

		/**
		 *@param int $vin
		 *@param string $brand
		 *@param string $type
		 *@param int $model
		 *@return true in success
		 *Insert in database new vehicle
		 */
		function create($vin, $brand, $type, $model, $idLocation, $idUser, $date, $reason) {
			if($vin && $brand && $type && $model && $idLocation && $idUser && $date && $reason) {
				$this->$vin = $this->bdDriver->escape_string($vin);
				$this->$brand = $this->bdDriver->escape_string($brand);
				$this->$type = $this->bdDriver->escape_string($type);
				$this->$model = $this->bdDriver->escape_string($model);

				$result = $this->bdDriver->query("INSERT INTO Vehicle(vin, brand, type, model) VALUES('" . $vin ."', '" . $brand ."', '" .  $type ."', " .  $model . ")");
				
				if($this->bdDriver->errno == 0) {
					$idVehicle = $this->bdDriver->insert_id;

					$result = $this->bdDriver->query("INSERT INTO VehicleLocation(idVehicle, idLocation, idUser, date, reason) VALUES(" . $idVehicle .", " . $idLocation .", " .  $idUser .", '" .  $date . "', '" . $reason . "')");
				}
				return $result;
			}

			return false;
		}

		/**
		 *@param int $idVehicle
		 *@return true in success
		 *Delete a vehicle
		 */
		function delete($idVehicle) {
			if($idVehicle) {
				$this->$idVehicle = $this->bdDriver->escape_string($idVehicle);
				$result = $this->bdDriver->query("UPDATE Vehicle SET status = 0 WHERE idVehicle = " . $this->$idVehicle);

                return $result;
            }

			return false;
		}

		/**
		 *@param int $idVehicle
		 *@param string $brand
		 *@param string $type
		 *@param int $model
		 *@return true in success
		 *Update a vehicle info
		 */
		function update($idVehicle, $brand, $type, $model) {
			if($idVehicle && $brand && $type && $model) {
				$this->$idVehicle = $this->bdDriver->escape_string($idVehicle);
				$this->$brand = $this->bdDriver->escape_string($brand);
				$this->$type = $this->bdDriver->escape_string($type);
				$this->$model = $this->bdDriver->escape_string($model);
				$result = $this->bdDriver->query("UPDATE Vehicle SET brand = '" . $brand ."', type = '" . $type . "', model = " . $model ." WHERE idVehicle = " . $this->$idVehicle);

				//if success
				return $result;
			}

			return false;
		}

		/**
		 *@param int $idVehicle
		 *@return true in success
		 *Gets a vehicle data
		 */
		function select($idVehicle) {
			if($idVehicle) {
				$this->$idVehicle = $this->bdDriver->escape_string($idVehicle);
				$result = $this->bdDriver->query("SELECT * FROM Vehicle WHERE idVehicle = " . $this->$idVehicle);

				return $result;
			}

			return false;
		}

		/**
		 *@return reslt set with all the vehicles in DB
		 *Gets a vehicle data
		 */
		function selectAll() {
			$result = $this->bdDriver->query("SELECT * FROM Vehicle");

			return $result;
		}
	}
?>