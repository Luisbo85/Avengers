<?php

	class InventoryMdl{
		private $Mileage;
		private $AmountGasoline;
		private $IDUser;
		private $IDVehicle;
		private $Observations;
		
		function __construct(){
			require("models/databaseConfig.inc");
			$this->DbDriver=new mysqli($host,$user,$pass,$bd);
			if($this->DbDriver->connect_error){
				die("No me pude conectar");
			}
		}
	
		/**
		 * Create a new inventory
		 * @param int $Mileage
		 * @param float $Gasoline
		 * @param string $Piece
		 * @param string $Severity
		 * @param int $IDVehicle
		 * @param string $Observations
		 * @return boolean $InventoryInserted
		 */
		function create($Mileage,$Gasoline,$IDPiece,$Severity,$IDVehicle,$Observations){
			$InventoryInserted=FALSE;
			//Conection with Database
			$Mileage=$this->DbDriver->real_escape_string($Mileage);
			$Gasoline=$this->DbDriver->real_escape_string($Gasoline);
			$IDPiece=$this->DbDriver->real_escape_string($IDPiece);
			$Severity=$this->DbDriver->real_escape_string($Severity);
			$IDVehicle=$this->DbDriver->real_escape_string($IDVehicle);
			$Observations=$this->DbDriver->real_escape_string($Observations);
			if($stmt=$this->DbDriver->prepare("INSERT INTO Inventory (mileage,gasoline,idVehicle,observations,date) 
		 								  	 			  VALUES (?,?,?,?,?)")){
		 		$Hoy=date('Y-m-d H:m:s');
				$stmt->bind_param('idiss',$Mileage,$Gasoline,$IDVehicle,$Observations,$Hoy);
				if($stmt->execute()==TRUE){
					if($stmt2=$this->DbDriver->prepare("INSERT INTO Hit (idInventory,idPiece,Severity) 
		 								  	 			  VALUES (?,?,?)")){
		 				$LastID=$stmt->insert_id;
		 				$stmt->close();
		 				$stmt2->bind_param('iis',$LastID,$IDPiece,$Severity);
						if($stmt2->execute()==TRUE){
							$InventoryInserted=TRUE;
						}
						else{
							$InventoryInserted=FALSE;
						}
						
					}
					else{
						$stmt->close();
						$InventoryInserted=FALSE;
					}
					$stmt2->close();
				}
				else{
					$stmt->close();
					$InventoryInserted=FALSE;
				}
				
		 	}
			
			return $InventoryInserted;
		}
		 
		/**
		 *Getting information of an inventory 
		 * @param int $IDInventory
		 * @return mixed $Inventory
		 */
		function select($IDInventory){
			$Inventory=FALSE;
		 	
			//Select an inventory from database
			$Result=$this->DbDriver->query("SELECT * FROM Inventory WHERE IDInventory=$IDInventory");
			if($Result!=FALSE){
				$Inventory=$Result;
			}
			return $Inventory;
		 }
		
		/**
		 * 
		 * Get an Result Object of all inventories with their information
		 * @return mixed $Inventories
		 */
		function listInventories(){
			//Select all inventories from database
			$Result=$this->DbDriver->query("SELECT * FROM Inventory WHERE NOT(status='DELETED')");
			return $Result;
		}
		
		/**
		 * Create a new piece in the database and receive piece name.
		 * Answer if it inserted or not
		 * @param string $piece
		 * @return boolean $Inserted
		 */
		function piece($piece){
			$Inserted=FALSE;
		 	
			//Select an inventory from database
			$Result=$this->DbDriver->query("INSERT INTO Piece (PieceName) values ('$piece')");
			if($Result!=FALSE){
				$Inserted=TRUE;
			}
			return $Inserted;
		}
		
		/**
		 * Select all hits information into database with the id param and return a Result Object with them but
		 * if it don´t have anything return a False 
		 * @return mixed $Result 
		 */
		function selectHit($IdInventory){
			$IdInventory=$this->DbDriver->real_escape_string($IdInventory);
			$Result=$this->DbDriver->query("SELECT H.*,P.PieceName FROM Hit H
											INNER JOIN Piece P
											ON H.idPiece=P.idPiece
											AND H.idInventory=$IdInventory");
			return $Result;
		} 
		
		/**
		 * Select all pieces information into database and return a Result Object with them but
		 * if it don´t have anything return a False 
		 * @return mixed $Result 
		 */
		function selectPieces(){
			//Select all pieces from database
			$Result=$this->DbDriver->query("SELECT * FROM Piece");
			return $Result;
		}
		
		/**
		 * Modificate an inventory information in the data and hit relevant with it.
		 * Return a Result Object if it's positive and other case false
		 * @param int $IdInventory
		 * @param int $Mileage
		 * @param float $Gasoline
		 * @param string $Piece
		 * @param string $Severity
		 * @param int $IDVehicle
		 * @param string $Observations
		 * @return boolean $InventoryUpdated 
		 */
		function update($IdInventory,$Mileage,$Gasoline,$IDPiece,$Severity,$IDVehicle,$Observations){
			$InventoryUpdated=FALSE;
			//Conection with Database
			$Mileage=$this->DbDriver->real_escape_string($Mileage);
			$Gasoline=$this->DbDriver->real_escape_string($Gasoline);
			$IDPiece=$this->DbDriver->real_escape_string($IDPiece);
			$Severity=$this->DbDriver->real_escape_string($Severity);
			$IDVehicle=$this->DbDriver->real_escape_string($IDVehicle);
			$Observations=$this->DbDriver->real_escape_string($Observations);
			if($stmt=$this->DbDriver->prepare("UPDATE Inventory SET mileage=?,gasoline=?,idVehicle=?,observations=?
		 								  	 			  WHERE idInventory=?")){
				$stmt->bind_param('idisi',$Mileage,$Gasoline,$IDVehicle,$Observations,$IdInventory);
				if($stmt->execute()==TRUE){
					if($stmt2=$this->DbDriver->prepare("UPDATE Hit SET idPiece=?,Severity=?
		 								  	 			  WHERE idInventory=?")){
		 				$stmt->close();
		 				$stmt2->bind_param('isi',$IDPiece,$Severity,$IdInventory);
						if($stmt2->execute()==TRUE){
							$InventoryUpdated=TRUE;
						}
						else{
							$InventoryUpdated=FALSE;
						}
						
					}
					else{
						$stmt->close();
						$InventoryUpdated=FALSE;
					}
					$stmt2->close();
				}
				else{
					$stmt->close();
					$InventoryUpdated=FALSE;
				}
				
		 	}
			
			return $InventoryUpdated;
		}
		
		/**
		 * Delete an inventory. 
		 * Recibe the id inventory to delete it. Only is a logic delete
		 * @param int $IdInventory
		 * @return boolean $Deleted
		 */
		function delete($IdInventory){
			$Deleted=FALSE;
			$IdInventory=$this->DbDriver->real_escape_string($IdInventory);
			$Result=$this->DbDriver->query("UPDATE Inventory SET status='DELETED' 
											WHERE idInventory=$IdInventory");
			if($Result!=FALSE){
				$Deleted=TRUE;
			}
			return $Deleted;
		}
	}
?>