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
		 		$Hoy=date('Y-m-d H:i:s');
				$stmt->bind_param('idiss',$Mileage,$Gasoline,$IDVehicle,$Observations,$Hoy);
				if($stmt->execute()==TRUE){
					if($stmt2=$this->DbDriver->prepare("INSERT INTO hit (idInventory,idPiece,Severity) 
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
			
			$this->Mileage=$Mileage;
			$this->Gasoline=$Gasoline;
			$this->IDVehicle=$IDVehicle;
			$this->Observations=$Observations;
			
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
			$Result=$this->DbDriver->query("SELECT * FROM inventory WHERE IDInventory=$IDInventory");
			if($Result!=FALSE){
				$Inventory=$Result->fetch_assoc();
			}
			return $Inventory;
		 }
		
		/**
		 * 
		 * Get an array of all inventories with their information
		 * @return mixed $Inventories
		 */
		function listInventories(){
			$Inventories=FALSE;
		 	
			//Select all inventories from database
			$Result=$this->DbDriver->query("SELECT * FROM inventory");
			if($Result!=FALSE){
				$row=$Result->fetch_assoc();
				while($row!=null){
					$Inventories[]=$row;
					$row=$Result->fetch_assoc();	
				}
			}
			return $Inventories;
		}
		
	}
?>