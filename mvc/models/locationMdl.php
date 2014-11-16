<?php
	class LocationMdl {
		private $idLocation;
		private $name;
		private $extraLoca;
		
		function __construct() {
			require("databaseConfig.inc");
			$this->bdDriver = new mysqli($host, $user, $pass, $bd);
			if($this->bdDriver->connect_error) {
				die("no se puede conectar");
			}
		}

		/*
		 *@param string $name
		 *@param string $extraLoca
		 *@return true in success
		 *Insert in database new location
		 */
		function create($name, $extraLoca) {
			if($name && $extraLoca) {
				$this->$name = $this->bdDriver->escape_string($name);
				$this->$extraLoca = $this->bdDriver->escape_string($extraLoca);

				$result = $this->bdDriver->query("INSERT INTO Location(locationName, extraLocation, status) VALUES('" . $name ."', '" . $extraLoca ."', 1)");

				return $result;
			}

			return false;
		}

		/*
		 *@param int $idLocation
		 *@return true in success
		 *Delete a location in DB
		 */
		function delete($idLocation) {
			if($idLocation) {
				$this->$idLocation = $this->bdDriver->escape_string($idLocation);

				$result = $this->bdDriver->query("UPDATE Location SET status = 0 WHERE idLocation = " . $idLocation);

				return $result;
			}

			return false;
		}

		/*
		 *@param int $idLocation
		 *@return true in success
		 *Selects a location info
		 */
		function select($idLocation) {
			if($idLocation) {
				$this->$idLocation = $this->bdDriver->escape_string($idLocation);

				$result = $this->bdDriver->query("SELECT * FROM Location WHERE idLocation = " . $idLocation);
				return $result;
			}

			return false;
		}

		/*
		 *@return result set with all locations in DB
		 *Selects all locations
		 */
		function selectAll() {
			$result = $this->bdDriver->query("SELECT * FROM Location WHERE status = 1");
			return $result;
		}

		/*
		 *@return result set with all locations in DB
		 *Selects all locations
		 */
		function selectAllVehicles($idLocation) {
			$result = $this->bdDriver->query("SELECT VL.idVehicle AS idVehicle, locationName, extraLocation, vin, brand, type, date, reason
				FROM VehicleLocation AS VL INNER JOIN
					Vehicle as V on  VL.idVehicle = V.idVehicle INNER JOIN
					Location  AS L ON VL.idLocation = L.idLocation
				WHERE VL.idLocation  = " . $idLocation);
			return $result;
		}

		/*
		 *@patam int $idLocation
		 *@param string $name
		 *@param string $extraLoca
		 *@return true in success
		 *Updates a location info
		 */
		function update($idLocation, $name, $extraLoca) {
			if($idLocation && $name && $extraLoca) {
				$this->idLocation = $this->bdDriver->escape_string($idLocation);
				$this->$name = $this->bdDriver->escape_string($name);
				$this->$extraLoca = $this->bdDriver->escape_string($extraLoca);

				$result = $this->bdDriver->query("UPDATE Location SET locationName = '" . $name . "', extraLocation = '" . $extraLoca . "' WHERE idLocation = " . $idLocation);

				return $result;
			}

			return false;
		}
	}
?>