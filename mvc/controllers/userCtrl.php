<?php

	
	class UserCtrl extends ValidationCtrl{
		private $model;
	  
		function __construct(){
	    	/*require('models/userMdl.php');
	    	$this->model=new UserMdl();*/
		}
	  
		function run(){
			switch($_GET['act']){
				case 'create':
		  			//User is valid and have permissions
		  			$this->create();
		  			break;
				case 'delete':
					//User is valid and have permissions
					$this->delete();
					break;
				case 'update':
					$this->update();
					//User is valid and have permissions
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
		 * Create a new user
		 */
		private function create(){
	  		//Validate variables and if variables is set 
			$Name=isset($_POST['Name'])?$this->validateName($_POST['Name']):'';
			$MaternalLastname=isset($_POST['MaternalLastname'])?$this->validateText($_POST['MaternalLastname']):'';
			$PaternalLastname=isset($_POST['PaternalLastname'])?$this->validateText($_POST['PaternalLastname']):'';
			$Email=isset($_POST['Email'])?$this->validateEmail($_POST['Email']):'';
			$Job=isset($_POST['Job'])?$this->validateText($_POST['Job']):'';
			$Pass=isset($_POST['Password'])?$this->validatePassword($_POST['Password']):'';
			$Telephone=isset($_POST['Telephone'])?$this->validateTelephone($_POST['Telephone']):0;
			 
			if($Name==false){
				echo 'Error con ', $_POST['Name'],'<br/>';
			}
	  		if($Email==false){
	  	 		echo 'Error con ', $_POST['Email'],'<br/>';
			}
			if($Telephone==false){
				echo 'Error con ', $_POST['Telephone'],'<br/>';
			}
			//Insert the new User
			$Result=$this->model->create($Name,$MaternalLastname,$PaternalLastname,$Email,$Job,$Telephone);
	     
			if($Result){
				//Shown a view
				require('views/userInserted.php');
			}
			else{
				require('views/Error.php');
			}
		}
	  
		/**
		 * Delete a user  
		 */
		private function delete(){
			$ID=isset($_POST['ID'])?$this->validateID($_POST['ID']):0;
			$Result=$this->model->delete($ID);
			 
			if($Result){
				require('views/userDeleted.php');
			}
			else{
			 	require('views/Error.php');
			}
		}
	   
		/**
		 * Update different attributes of User
		 */
		private function update(){
			//Validate variables and if variables is set 
			$Name=isset($_POST['Name'])?$this->validateName($_POST['Name']):'';
			$MaternalLastname=isset($_POST['MaternalLastname'])?$this->validateText($_POST['MaternalLastname']):'';
			$PaternalLastname=isset($_POST['PaternalLastname'])?$this->validateText($_POST['PaternalLastname']):'';
			$Email=isset($_POST['Email'])?$this->validateEmail($_POST['Email']):'';
			$Job=isset($_POST['Job'])?$this->validateTex($_POST['Job']):'';
			$Pass=isset($_POST['Password'])?$this->validatePassword($_POST['Password']):'';
		  	
		  	//Update information of an user
			$Result=$this->model->update($Name,$MaternalLastname,$PaternalLastname,$Email,$Job,$Pass);
		 
		 	if($Result){
		  		require('views/userModified.php');
		  	}
			else{
		 		require('views/Error.php');
		  	}
		}
	
		/*
		 * Show information about an user 
		 */ 
		private function select(){
	    	//Validate variables and if variables is set 
			$ID=isset($_POST['ID'])?$this->validateID($_POST['ID']):0;
	    	//Show information of an user
			$User=$this->model->select($ID);
		 
			if(is_array($User)){
		  		require('views/userSelected.php');
			}
		  	else{
		 		require('views/Error.php');
			}
		}
	   
	}
?>
