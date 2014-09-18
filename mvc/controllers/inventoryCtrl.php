<?php

class InventoryCtrl{
  private $model;
  
  function __construct(){
    require('models/inventoryMdl.php');
    $this->model=new InventoryMdl();
  }
  
  function run(){
     switch($_GET['act']){
	case 'create':
	  	//User is valid and have permissions
	  	$this->create();
	  	break;
	case 'change':
		//User is valid and have permissions
		$this->changeLocation();
		break;
	case 'exit':
		$this->exitVehicle();
		break;
	case 'select':
		//User is valid and have permissions
		$this->select();
		break;
	default:
	  break;
     }
   }
  
   /**
   * Create a new Inventory register
   **/
  private function create(){
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
	
	//Insert a new Inventory
	$Result=$this->model->create($Mileage,$AmountGasoline,$Hit,$Severity,$IDUser,$vin,$Observations,$name,$extraLoca);
	
	if($Result){
		require('views/inventoryInserted.php');
	}
	else{
		require('views/Error.php');
	}
  	
  }
  
  
  /**
   * Change vehicle´s location 
   **/
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
   **/
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
  
  /**
   * Show information of an inventory 
   **/
  private function select(){
  	//Validate variables and if variables is set 
  	$IDInventory=isset($_POST['IDInventory'])?$this->validateID($_POST['IDInventory']):0;
	
	//Select Inventory
	$Result=$this->model->select($IDInventory);
	
	if(is_array($Result)){
		require('views/inventorySelected.php');
	}
	else{
		require('views/Error.php');
	}
  }
  
  /**
    * Validate a input number
    * @param int $data
    * @return int $data 
    **/
   private function validateNumber($data){
	 return $data;
   }
   
   /**
    * Validate a input text
    * @param string $text
    * @return string $text
    **/
   private function  validateText($text){
 	 return $text;
   }
   
   /**
    * Validate a input email
    * @param int $ID
    * @return int $ID
    **/
   private function validateID($ID){
   	 return $ID;
   }
}
?>