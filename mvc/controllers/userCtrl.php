<?php

	
	class UserCtrl extends StandardCtrl{
		private $model;
	  
		function __construct(){
			parent::__construct();
	    	require('models/userMdl.php');
	    	$this->model=new UserMdl();
		}
	  
		function run(){
			switch($_GET['act']){
				case 'login':
					if($this->isLogged()==FALSE){
						if($this->login()){
							require('views/successLogin.php');
						}
					}
					break;
				case 'logout':
					if($this->isLogged()==TRUE){
						$this->logout();
						require('views/logout.php');
					}
					break;
				case 'create':
		  			//User is valid and have permissions
		  			if($this->isLogged()){
		  				if($this->isManager()){
		  					$this->create();
		  				}
						else{
							require('views/NoAccess.php');
						}
		  			}
					else{
						require('views/needLogin.php');
					}
		  			break;
				case 'delete':
					//User is valid and have permissions
					if($this->isLogged()){
		  				if($this->isManager()){
		  					$this->delete();
		  				}
						else{
							require('views/NoAccess.php');
						}
		  			}
					else{
						require('views/needLogin.php');
					}
					break;
				case 'update':
					//User is valid and have permissions
					if($this->isLogged()){
		  				if($this->isManager()){
		  					$this->update();
		  				}
						else{
							require('views/NoAccess.php');
						}
		  			}
					else{
						require('views/needLogin.php');
					}
					break;
				case 'select':
					//User is valid and have permissions
					if($this->isLogged()){
		  				if($this->isManager() or $this->isUser()){
		  					$this->select();
		  				}
						else{
							require('views/NoAccess.php');
						}
		  			}
					else{
						require('views/needLogin.php');
					}
					break;
				case 'list':
					//User is valid and have permissions
					if($this->isLogged()){
						
		  				if($this->isManager() or $this->isUser()){
		  					$this->listUsers();
		  				}
						else{
							require('views/NoAccess.php');
						}
		  			}
					else{
						require('views/needLogin.php');
					}
					break;
				default:
				  break;
			}
		}
	
		
		/**
		 * Create a new user. Doing validation over input data
		 */
		private function create(){
			$Correct=TRUE;//Flag to determine if it can create a new user
			$NoSet=FALSE; //Flag to determine if the variables are set
	  		//Validate variables and if variables is set 
			$User=isset($_POST['user'])?$this->validateUserName($_POST['user']):$NoSet=TRUE;
			$Name=isset($_POST['Name'])?$this->validateName($_POST['Name']):$NoSet=TRUE;
			$MaternalLastname=isset($_POST['MaternalLastname'])?$this->validateName($_POST['MaternalLastname']):$NoSet=TRUE;
			$PaternalLastname=isset($_POST['PaternalLastname'])?$this->validateName($_POST['PaternalLastname']):$NoSet=TRUE;
			$Email=isset($_POST['Email'])?$this->validateEmail($_POST['Email']):$NoSet=TRUE;
			$Job=isset($_POST['Job'])?$this->validateText($_POST['Job']):$NoSet=TRUE;
			$Pass=isset($_POST['Password'])?$this->validatePassword($_POST['Password']):$NoSet=TRUE;
			$Telephone=isset($_POST['Telephone'])?$this->validateTelephone($_POST['Telephone']):$NoSet=TRUE;

			if($NoSet==FALSE){
				if($Name==FALSE){
					$Correct=FALSE;
				}
				elseif($Pass==FALSE){
					$Correct=FALSE;
				}
				elseif($MaternalLastname==FALSE){
					if($_POST['MaternalLastname']==''){
						$MaternalLastname='';
					}
					else{
						$Correct=FALSE;
					}
				}
				if($PaternalLastname==FALSE){
					if($_POST['PaternalLastname']==''){
						$PaternalLastname='';
					}
					else{
						$Correct=FALSE;
					}
				}
				if($Email==FALSE){
					if($_POST['Email']==''){
						$Email='';
					}
					else{
						$Correct=FALSE;
					}
				}
				if($Job==FALSE){
					if($_POST['Job']==''){
						$Job='';
					}
					else{
						$Correct=FALSE;
					}
				}
				if($Telephone==FALSE){
					if($_POST['Telephone']==''){
						$Telephone='';
					}
					else{
						$Correct=FALSE;
					}
				}
				
				//Insert the new User
				if($Correct==TRUE){
					
					$Result=$this->model->create($User,$Name,$MaternalLastname,$PaternalLastname,$Email,$Job,$Telephone,$Pass);     
					if($Result==TRUE){
						//Shown a view
						require('views/userInserted.php');
						require('controllers/mail.php');
						$subject = 'Correo de registro de usuario';
						$body = 'El usuario ' . $Name . ' ' . $MaternalLastname . ' ' . $PaternalLastname . ' se ha registrado correctamente';
						$mail = new Email($Email, $subject, $body);
						$mail->send();
					}
					else{
						require('views/Error.php');
					}
				}
				else{
					require('views/Error.php');
				}
			}
			else{
				require('views/Error.php');
			}
		}

	  
		/**
		 * Delete a user using his ID.
		 */
		private function delete(){
			$NoSet=FALSE; //Flag to determine if the variables are set
			$ID=isset($_POST['ID'])?$this->validateID($_POST['ID']):$NoSet=TRUE;
			if($NoSet==FALSE){
				if($ID!=FALSE){
					$Result=$this->model->delete($ID); 
					if($Result){
						require('views/userDeleted.php');
					}
					else{
					 	require('views/Error.php');
					}
				}
				else{
					require('views/Error.php');
				}
			}
			else{
				require('views/Error.php');
			}
		}
	   
		/**
		 * Update different attributes of User
		 */
		private function update(){
			$Correct=TRUE;//Flag to determine if it can update an user
			$NoSet=FALSE; //Flag to determine if the variables are set
	  		//Validate variables and if variables is set
	  		$ID=isset($_POST['ID'])?$this->validateID($_POST['ID']):$NoSet=TRUE; 
			$Name=isset($_POST['Name'])?$this->validateName($_POST['Name']):$NoSet=TRUE;
			$MaternalLastname=isset($_POST['MaternalLastname'])?$this->validateName($_POST['MaternalLastname']):$NoSet=TRUE;
			$PaternalLastname=isset($_POST['PaternalLastname'])?$this->validateName($_POST['PaternalLastname']):$NoSet=TRUE;
			$Email=isset($_POST['Email'])?$this->validateEmail($_POST['Email']):$NoSet=TRUE;
			$Job=isset($_POST['Job'])?$this->validateText($_POST['Job']):$NoSet=TRUE;
			$Pass=isset($_POST['Password'])?$this->validatePassword($_POST['Password']):$NoSet=TRUE;
			$Telephone=isset($_POST['Telephone'])?$this->validateTelephone($_POST['Telephone']):$NoSet=TRUE;
		  	
			if($NoSet==FALSE){
				if($ID==FALSE){
					$Correct=FALSE;
				}
				elseif($Name==FALSE){
					$Correct=FALSE;
				}
				elseif($Pass==FALSE){
					$Correct=FALSE;
				}
				elseif($MaternalLastname==FALSE){
					$Correct=FALSE;
				}
				elseif($PaternalLastname==FALSE){
					$Correct=FALSE;
				}
				elseif($Email==FALSE){
					$Correct=FALSE;
				}
				elseif($Job==FALSE){
					$Correct=FALSE;
				}
				elseif($Telephone==FALSE){
					$Correct=FALSE;
				}
				//Insert the new User
				if($Correct==TRUE){
					
					//Update information of an user
					$Result=$this->model->update($ID,$Name,$MaternalLastname,$PaternalLastname,$Email,$Job,$Telephone);
		 			if($Result){
		  				require('views/userModified.php');
		  				require('controllers/mail.php');
						$subject = 'Correo de actualizacion de datos usuario';
						$body = 'Los datos del usuario ' . $Name . ' ' . $MaternalLastname . ' ' . $PaternalLastname . ' se han actualizado.';
						$body = $body . ' Email: ' . $Email;
						$body = $body . ' Job: ' . $Job;
						$body = $body . ' Telefono: ' . $Telephone;
						$mail = new Email($Email, $subject, $body);
						$mail->send();
					}
					else{
						require('views/Error.php');
					}
				}
				else{
					require('views/Error.php');
				}
			}
			else{
				require('views/Error.php');
			}
		}
		
		
		/**
		 * Show information about an user
		 */ 
		private function select(){
	    	//Validate variables and if variables is set 
			$ID=isset($_POST['ID'])?$this->validateID($_POST['ID']):FALSE;
	    	//Show information of an user
	    	if($ID==FALSE){
				$User=$this->model->select($ID);
			 
				if(is_array($User)){
			  		require('views/userSelected.php');
				}
			  	else{
			 		require('views/Error.php');
				}
			}
			else{
				require('views/Error.php');
			}
		}

		/**
		 * List all system´s users with information about user
		 */
		private function listUsers(){
			$Users=$this->model->listUsers();
			if(is_array($Users)){
		  		require('views/userList.php');
			}
		  	else{
		 		require('views/Error.php');
			}	
		}
		
		
	   
	}
?>
