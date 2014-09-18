<?php

class InventoryMdl{
	private $Mileage;
	private $AmountGasoline;
	private $Hit;
	private $Severity;
	private $IDUser;
	private $vin;
	private $Observations;
	private $name;
	private $extraLoca;
	
	function __construct(){
		
	}

	/**
	 * Create a new inventory
	 * @param int $Mileage
	 * @param float $AmountGasoline
	 * @param string $Hit
	 * @param string $Severity
	 * @param int $IDUser
	 * @param int $vin
	 * @param string $Observations
	 * @param string $name
	 * @param string $extraLoca
	 * @return boolean $InventoryInserted
	 **/
	function create($Mileage,$AmountGasoline,$Hit,$Severity,$IDUser,$vin,$Observations,$name,$extraLoca){
		$InventoryInserted=true;
		
		//Conection with Database
		$this->Mileage=$Mileage;
		$this->AmountGasoline=$AmountGasoline;
		$this->Hit=$Hit;
		$this->Severity=$Severity;
		$this->IDUser=$IDUser;
		$this->vin=$vin;
		$this->Observations=$Observations;
		$this->name=$name;
		$this->extraLoca=$extraLoca;
		return $InventoryInserted;
	}
	
	/**
	 * Change Vehicle´s Location
	 * @param int $IDInventory
	 * @param int $IDUser
	 * @param string $Reason
	 * @param string $name
	 * @param string $extraLoca
	 * @return boolean $Change
	 **/
	 function changeLocation($IDInventory,$IDUser,$Reason,$name,$extraLoca){
	 	$Change=true;
		
		//Change Location in database
		$this->IDUser=$IDUser;
		$this->name=$name;
		$this->extraLoca=$extraLoca;
		return $Change;
	 }
	 
	 /**
	 * Create a new inventory of exit
	 * @param int $Mileage
	 * @param float $AmountGasoline
	 * @param string $Hit
	 * @param string $Severity
	 * @param int $IDUser
	 * @param int $vin
	 * @param string $Observations
	 * @param string $name
	 * @param string $extraLoca
	 * @return boolean $Exit
	 **/
	 function exitVehicle($Mileage,$AmountGasoline,$Hit,$Severity,$IDUser,$vin,$Observations,$name,$extraLoca){
	 	$Exit=true;
		
		//Create a new inventory of exit in the database
		$this->Mileage=$Mileage;
		$this->AmountGasoline=$AmountGasoline;
		$this->Hit=$Hit;
		$this->Severity=$Severity;
		$this->IDUser=$IDUser;
		$this->vin=$vin;
		$this->Observations=$Observations;
		$this->name=$name;
		$this->extraLoca=$extraLoca;
		return $Exit;
	 }
	 
	 /**
	  *Getting information of an inventory 
	  * @param int $IDInventory
	  * @return array $Inventory
	  **/
	 function select($IDInventory){
	 	$Inventory=array();
	 	
		//Select an inventory from database
		
		return $Inventory;
	 }
}
?>