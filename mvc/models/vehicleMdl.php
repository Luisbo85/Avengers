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
		 *@param int $idLocation
		 *@param int $idUser
		 *@param string $date
		 *@param string $reason
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

				$result = $this->bdDriver->insert_id;
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
			$result = $this->bdDriver->query("SELECT * FROM Vehicle WHERE status = 1");

			return $result;
		}

		/**
		 *@return returns vahicle location info
		 *Gets a vehicle data
		 */
		function selectVL($idVehicle) {
			if($idVehicle) {
				$result = $this->bdDriver->query("SELECT 
						locationName, extraLocation, user, VL.date AS date, VL.reason AS reason
					    FROM
					        VehicleLocation as VL
					            INNER JOIN
					        Vehicle as V ON VL.idVehicle = V.idVehicle
					            INNER JOIN
					        Location as L ON VL.idLocation = VL.idLocation
					            INNER JOIN
					        User as U ON VL.idUser = U.idUser
					        WHERE VL.idVehicle = " . $this->$idVehicle);
				return $result;
			}
			return false;
		}
		
		/**
		 * Change Vehicle´s Location
		 * @param int $IDLocation
		 * @param int $IDUser
		 * @param int $IDVehicle
		 * @param string $Reason
		 * @return boolean $Change
		 */
		function changeLocation($IDLocation,$IDUser,$IDVehicle,$Reason){
		 	$Change=FALSE;
			$IDLocation=$this->bdDriver->real_escape_string($IDLocation);
			$IDUser=$this->bdDriver->real_escape_string($IDUser);
			$IDVehicle=$this->bdDriver->real_escape_string($IDVehicle);
			$Reason=$this->bdDriver->real_escape_string($Reason);
			if($stmt=$this->bdDriver->prepare("INSERT INTO VehicleLocation (idLocation,idUser,idVehicle,reason,date) 
		 								  	 			  VALUES (?,?,?,?,?)")){
		 		$Hoy=date('Y-m-d H:i:s');
				$stmt->bind_param('iiiss',$IDLocation,$IDUser,$IDVehicle,$Reason,$Hoy);
				if($stmt->execute()==TRUE){
					$Change=TRUE;
				}
				else{
					$Change=FALSE;
				}
				$stmt->close();
		 	}
			
			return $Change;
		 }
		 
		/**
		 * Create a new inventory of exit
		 * @param int $Mileage
		 * @param float $Gasoline
		 * @param int $IDPiece
		 * @param string $Severity
		 * @param int $IDVehice
		 * @param string $Observations
		 * @return boolean $Exit
		 */
		function exitVehicle($Mileage,$Gasoline,$IDPiece,$Severity,$IDVehicle,$Observations){
			$Exit=FALSE;
			//Conection with Database
			$Mileage=$this->bdDriver->real_escape_string($Mileage);
			$Gasoline=$this->bdDriver->real_escape_string($Gasoline);
			$IDPiece=$this->bdDriver->real_escape_string($IDPiece);
			$Severity=$this->bdDriver->real_escape_string($Severity);
			$IDVehicle=$this->bdDriver->real_escape_string($IDVehicle);
			$Observations=$this->bdDriver->real_escape_string($Observations);
			if($stmt=$this->bdDriver->prepare("INSERT INTO Inventory (mileage,gasoline,idVehicle,observations,date,status) 
		 								  	 			  VALUES (?,?,?,?,?,'EXIT')")){
		 		$Hoy=date('Y-m-d H:i:s');
				$stmt->bind_param('idiss',$Mileage,$Gasoline,$IDVehicle,$Observations,$Hoy);
				if($stmt->execute()==TRUE){
					if($stmt2=$this->bdDriver->prepare("INSERT INTO Hit (idInventory,idPiece,Severity) 
		 								  	 			  VALUES (?,?,?)")){
		 				$LastID=$stmt->insert_id;
		 				$stmt->close();
		 				$stmt2->bind_param('iis',$LastID,$IDPiece,$Severity);
						if($stmt2->execute()==TRUE){
							$Exit=TRUE;
						}
						else{
							$Exit=FALSE;
						}
					}
					else{
						$stmt->close();
						$Exit=FALSE;
					}
					$stmt2->close();
				}
				else{
					$stmt->close();
					$Exit=FALSE;
				}
		 	}
			
			return $Exit;
		}


		/**
		 * Return all inventories about vehicles
		 * @return mixed $result
		 */
		function admissionInventory(){
			$result = $this->bdDriver->query(" SELECT T1.* FROM Inventory T1
    										   INNER JOIN ( SELECT idVehicle, MAX(date) max_fecha FROM Inventory 
    										   				GROUP BY idVehicle) T2
    										   ON T1.idVehicle = T2.idVehicle 
    										   AND T1.date = T2.max_fecha
    										   ORDER BY T1.date");
			return $result;
		}
		
		
	}
?>