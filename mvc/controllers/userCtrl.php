<?php
	class UserCtrl extends StandardCtrl{
		private $model;
	  
		function __construct(){
			parent::__construct();
	    	require('models/userMdl.php');
	    	$this->model=new UserMdl();
		}
	  
	    /**
		 * This select the correct method in the class
		 */
		function run(){
			if(isset($_GET['act'])){
				switch($_GET['act']){
					case 'login':
						if($this->isLogged()==FALSE){
							if($this->login()){
								$data['page_title']='Login';
								$data['general_content']=file_get_contents('views/successLogin.html');
								$this->createTemplate($data);
							}
						}
						else{
							$this->goHome();
						}
						break;
					case 'logout':
						if($this->isLogged()==TRUE){
							$this->logout();
							$this->goHome();
						}
						break;
					case 'create':
			  			//User is valid and have permissions
			  			if($this->isLogged()){
			  				if($this->isManager()){
			  					$this->create();
			  				}
							else{
								$this->noAccess();
							}
			  			}
						else{
							$this->goHome();
						}
			  			break;
					case 'changepass':
						//User is valid and have permissions
			  			if($this->isLogged()){
			  				$this->changepass();
			  			}
						else{
							$this->goHome();
						}
						break;
					case 'delete':
						//User is valid and have permissions
						if($this->isLogged()){
			  				if($this->isManager()){
			  					$this->delete();
			  				}
							else{
								$this->noAccess();
							}
			  			}
						else{
							$this->goHome();
						}
						break;
					case 'update':
						//User is valid and have permissions
						if($this->isLogged()){
			  				if($this->isManager()){
			  					$this->update();
			  				}
							else{
								$this->noAccess();
							}
			  			}
						else{
							$this->goHome();
						}
						break;
					case 'select':
						//User is valid and have permissions
						if($this->isLogged()){
			  				if($this->isManager()){
			  					$this->select();
			  				}
							else{
								$this->noAccess();
							}
			  			}
						else{
							$this->goHome();
						}
						break;
					case 'list':
						//User is valid and have permissions
						if($this->isLogged()){
							
			  				if($this->isManager()){
			  					$this->listUsers();
			  				}
							else{
								$this->noAccess();
							}
			  			}
						else{
							$this->goHome();
						}
						break;
					case 'recover':
						$this->recover();
						break;
					default:
					  break;
				}
			}
			else{
				if($this->isLogged()){
					$data = array(
						'page_title' => "Usuario",
						'general_content' => file_get_contents("views/userMenu.html")
					);
					$this->createTemplate($data);
				}
				else{
					$this->goHome();
				}
			}
		}
		
		/**
		 * Create a new user. Doing validation over input data
		 */
		private function create(){
			if(empty($_POST)){
				$data['page_title']='Registro de Usuario';
				$data['general_content']=file_get_contents('./views/UserCreate.html');
				$this->createTemplate($data);
			}else{
				$Correct=TRUE;//Flag to determine if it can create a new user
				$NoSet=FALSE; //Flag to determine if the variables are set
		  		//Validate variables and if variables is set 
				$User=isset($_POST['user'])?$this->validateUserName($_POST['user']):$NoSet=TRUE;
				$Name=isset($_POST['name'])?$this->validateName($_POST['name']):$NoSet=TRUE;
				$MaternalLastname=isset($_POST['apellimaterno'])?$this->validateName($_POST['apellimaterno']):$NoSet=TRUE;
				$PaternalLastname=isset($_POST['apellipaterno'])?$this->validateName($_POST['apellipaterno']):$NoSet=TRUE;
				$Email=isset($_POST['email'])?$this->validateEmail($_POST['email']):$NoSet=TRUE;
				$Job=isset($_POST['job'])?$this->validateText($_POST['job']):$NoSet=TRUE;
				$Pass=isset($_POST['pass'])?$this->validatePassword($_POST['pass']):$NoSet=TRUE;
				$Pass2=isset($_POST['pass2'])?$this->validatePassword($_POST['pass2']):$NoSet=TRUE;
				$Telephone=isset($_POST['phone'])?$this->validateTelephone($_POST['phone']):$NoSet=TRUE;

				if($NoSet==FALSE){
					if($Name==FALSE){
						$Correct=FALSE;
						
					}
					elseif($Pass==FALSE or $Pass2==FALSE or strcmp($Pass, $Pass2)!=0){
						$Correct=FALSE;
					}
					elseif($MaternalLastname==FALSE){
						if($_POST['apellimaterno']==''){
							$MaternalLastname='';
						}
						else{
							$Correct=FALSE;
						}
					}
					if($PaternalLastname==FALSE){
						if($_POST['apellipaterno']==''){
							$PaternalLastname='';
						}
						else{
							$Correct=FALSE;
						}
					}
					if($Email==FALSE){
						if($_POST['email']==''){
							$Email='';
						}
						else{
							$Correct=FALSE;
						}
					}
					if($Job==FALSE){
						if($_POST['job']==''){
							$Job='';
						}
						else{
							$Correct=FALSE;
						}
					}
					if($Telephone==FALSE){
						if($_POST['phone']==''){
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
							require('controllers/mail.php');
							$subject = 'Correo de registro de usuario';
							$body = 'El usuario ' . $Name . ' ' . $MaternalLastname . ' ' . $PaternalLastname . ' se ha registrado correctamente';
							$mail = new Email($Email, $subject, $body);
							$mail->send();
							
							$Users=$this->listUsers();
							
							$vista = file_get_contents("./views/UserList.html");
							$inicio_fila = strrpos($vista,'<tr>');
							$final_fila = strrpos($vista,'</tr>') + 5;
							$fila = substr($vista,$inicio_fila,$final_fila-$inicio_fila);
							$filas="";
							foreach ($Users as $row) {
								$new_fila = $fila;
								$diccionario = array(
									'{id}' => $row['idUser'], 
									'{status}' => $row['status']?'Activo':'Inactivo',
									'{user}' => $row['user'],
									'{job}' => $row['job'],
									'{name}' => $row['name'],
									'{paternalLastname}' => $row['paternalLastname'],
									'{maternalLastname}' => $row['maternalLastname'],
									'{email}' => $row['email'],
									'{phone}' => $row['telephone']
									);
								$new_fila = strtr($new_fila,$diccionario);
								$filas .= $new_fila;
							}
							
							$alert = file_get_contents("./views/alert.html");
							$diccionario = array(
									'{type}' => 'alert-success',
									'{title}' => '¡Insertado!',
									'{text}' => 'El usuario se inserto exitosamente.');
							$alert = strtr($alert, $diccionario);
			
							$diccionario = array(
									'{alert}' => $alert);
							$vista = strtr($vista,$diccionario);
			
							$vista = str_replace($fila, $filas, $vista);
							$data['page_title']='Lista de Usuarios'; 
							$data['general_content']=$vista;
							$this->createTemplate($data);
						}
						else{
							$this->msgError();
						}
					}
					else{
						$this->msgError('Datos ingresados son erroneos');
					}
				}
				else{
					$this->msgError();
				}
			}
		}

	  
		/**
		 * Delete a user. Fisrt use a filter to find a user 
		 */
		private function delete(){
			if(isset($_GET['id'])){
				$ID=$this->validateID($_GET['id']);
				if($ID!=FALSE){
					$Result=$this->model->delete($ID); 
					if($Result){
						$Users=$this->listUsers();
						
						$vista = file_get_contents("./views/UserList.html");
						$inicio_fila = strrpos($vista,'<tr>');
						$final_fila = strrpos($vista,'</tr>') + 5;
						$fila = substr($vista,$inicio_fila,$final_fila-$inicio_fila);
						$filas="";
						foreach ($Users as $row) {
							$new_fila = $fila;
							$diccionario = array(
								'{id}' => $row['idUser'], 
								'{status}' => $row['status']?'Activo':'Inactivo',
								'{user}' => $row['user'],
								'{job}' => $row['job'],
								'{name}' => $row['name'],
								'{paternalLastname}' => $row['paternalLastname'],
								'{maternalLastname}' => $row['maternalLastname'],
								'{email}' => $row['email'],
								'{phone}' => $row['telephone']
								);
							$new_fila = strtr($new_fila,$diccionario);
							$filas .= $new_fila;
						}
						
						$alert = file_get_contents("./views/alert.html");
						if(strcmp($Result,'D')!=0){
							$diccionario = array(
							'{type}' => 'alert-success',
							'{title}' => '¡Eliminado!',
							'{text}' => 'El usuario se elimino exitosamente.');
						}
						else{
							$diccionario = array(
							'{type}' => 'alert-info',
							'{title}' => '',
							'{text}' => 'El usuario ya estaba eliminado');
						}
						
						$alert = strtr($alert, $diccionario);

						$diccionario = array(
								'{alert}' => $alert);
						$vista = strtr($vista,$diccionario);
						$vista = str_replace($fila, $filas, $vista);
						$data['page_title']='Eliminar de Usuario';
						$data['general_content']=$vista;
						$this->createTemplate($data);
					}
					else{
					 	$this->msgError();
						$data['general_content']=file_get_contents('views/Error.html');
						$this->createTemplate($data);
					}
				}
				else{
					$this->msgError();
				}
				
			}
			else{
				$Users=$this->listUsers();
						
				$vista = file_get_contents("./views/UserList.html");
				$inicio_fila = strrpos($vista,'<tr>');
				$final_fila = strrpos($vista,'</tr>') + 5;
				$fila = substr($vista,$inicio_fila,$final_fila-$inicio_fila);
				$filas="";
				foreach ($Users as $row) {
					$new_fila = $fila;
					$diccionario = array(
						'{id}' => $row['idUser'], 
						'{status}' => $row['status']?'Activo':'Inactivo',
						'{user}' => $row['user'],
						'{job}' => $row['job'],
						'{name}' => $row['name'],
						'{paternalLastname}' => $row['paternalLastname'],
						'{maternalLastname}' => $row['maternalLastname'],
						'{email}' => $row['email'],
						'{phone}' => $row['telephone']
						);
					$new_fila = strtr($new_fila,$diccionario);
					$filas .= $new_fila;
				}
				
				$alert = file_get_contents("./views/alert.html");
				$diccionario = array(
					'{type}' => 'alert-info',
					'{title}' => '',
					'{text}' => 'Da click en Eliminar para quitar el usuario');
				$alert = strtr($alert, $diccionario);
				$diccionario = array(
						'{alert}' => $alert);
				$vista = strtr($vista,$diccionario);
				$vista = str_replace($fila, $filas, $vista);
				$data['page_title']='Eliminar Usuario';
				$data['general_content']=$vista;
				$this->createTemplate($data);
			}		
			
		}
	   
		/**
		 * Update different attributes of User
		 */
		private function update(){
			if(!isset($_GET['id'])){
				$Users=$this->listUsers();
						
				$vista = file_get_contents("./views/UserList.html");
				$inicio_fila = strrpos($vista,'<tr>');
				$final_fila = strrpos($vista,'</tr>') + 5;
				$fila = substr($vista,$inicio_fila,$final_fila-$inicio_fila);
				$filas="";
				foreach ($Users as $row) {
					$new_fila = $fila;
					$diccionario = array(
						'{id}' => $row['idUser'], 
						'{status}' => $row['status']?'Activo':'Inactivo',
						'{user}' => $row['user'],
						'{job}' => $row['job'],
						'{name}' => $row['name'],
						'{paternalLastname}' => $row['paternalLastname'],
						'{maternalLastname}' => $row['maternalLastname'],
						'{email}' => $row['email'],
						'{phone}' => $row['telephone']
						);
					$new_fila = strtr($new_fila,$diccionario);
					$filas .= $new_fila;
				}
				
				$alert = file_get_contents("./views/alert.html");
				
				$diccionario = array(
				'{type}' => 'alert-info',
				'{title}' => '',
				'{text}' => 'Da click en Modificar para cambiar los datos del usuario');
				
				$alert = strtr($alert, $diccionario);

				$diccionario = array(
						'{alert}' => $alert);
				$vista = strtr($vista,$diccionario);
				$vista = str_replace($fila, $filas, $vista);
				$data['page_title']='Eliminar de Usuario';
				$data['general_content']=$vista;
				$this->createTemplate($data);
			}
			else{
				if(empty($_POST)){
					$row=$this->select($_GET['id']);
					$vista = file_get_contents("./views/userUpdate.html");
					
					$diccionario = array(
						'{id}' => $row['idUser'], 
						'{user}' => $row['user'],
						'{job1}' => $row['job'],
						'{job2}' => strcasecmp($row['job'],'Manager')==0?'User':'Manager',
						'{name}' => $row['name'],
						'{apellipaterno}' => $row['paternalLastname'],
						'{apellimaterno}' => $row['maternalLastname'],
						'{email}' => $row['email'],
						'{phone}' => $row['telephone']
						);
						
					$vista = strtr($vista,$diccionario);
					$data['page_title']='Modificar Usuario';
					$data['general_content']=$vista;
					$this->createTemplate($data);
				}
				else{
					$Correct=TRUE;//Flag to determine if it can update an user
					$NoSet=FALSE; //Flag to determine if the variables are set
			  		//Validate variables and if variables is set
			  		$ID=isset($_GET['id'])?$this->validateID($_GET['id']):$NoSet=TRUE;
			  		$User=isset($_POST['user'])?$this->validateUserName($_POST['user']):$NoSet=TRUE;
					$Name=isset($_POST['name'])?$this->validateName($_POST['name']):$NoSet=TRUE;
					$MaternalLastname=isset($_POST['apellimaterno'])?$this->validateName($_POST['apellimaterno']):$NoSet=TRUE;
					$PaternalLastname=isset($_POST['apellipaterno'])?$this->validateName($_POST['apellipaterno']):$NoSet=TRUE;
					$Email=isset($_POST['email'])?$this->validateEmail($_POST['email']):$NoSet=TRUE;
					$Job=isset($_POST['job'])?$this->validateText($_POST['job']):$NoSet=TRUE;
					$Telephone=isset($_POST['phone'])?$this->validateTelephone($_POST['phone']):$NoSet=TRUE;
				  	
					if(!isset($_POST['user']) or $User==FALSE){
						$Correct=FALSE;
					}
					
					if($ID==FALSE){
						$Correct=FALSE;
					}
					if($Name==FALSE){
						$Name='';
					}
					if($MaternalLastname==FALSE){
						$MaternalLastname='';
					}
					if($PaternalLastname==FALSE){
						$PaternalLastname='';
					}
					if($Email==FALSE){
						$Email='';
					}
					if($Job==FALSE){
						$Job='User';
					}
					if($Telephone==FALSE){
						$Telephone='';
					}
					if($NoSet==FALSE){
						//Insert the new User
						if($Correct==TRUE){
							
							//Update information of an user
							$Result=$this->model->update($ID,$User,$Name,$MaternalLastname,$PaternalLastname,$Email,$Job,$Telephone);
				 			if($Result){
				  				require('controllers/mail.php');
								$subject = 'Correo de actualizacion de datos usuario';
								$body = 'Los datos del usuario ' . $Name . ' ' . $MaternalLastname . ' ' . $PaternalLastname . ' se han actualizado.';
								$body = $body . ' Email: ' . $Email;
								$body = $body . ' Job: ' . $Job;
								$body = $body . ' Telefono: ' . $Telephone;
								$mail = new Email($Email, $subject, $body);
								$mail->send();
								
								$Users=$this->listUsers();
							
								$vista = file_get_contents("./views/UserList.html");
								$inicio_fila = strrpos($vista,'<tr>');
								$final_fila = strrpos($vista,'</tr>') + 5;
								$fila = substr($vista,$inicio_fila,$final_fila-$inicio_fila);
								$filas="";
								foreach ($Users as $row) {
									$new_fila = $fila;
									$diccionario = array(
										'{id}' => $row['idUser'], 
										'{status}' => $row['status']?'Activo':'Inactivo',
										'{user}' => $row['user'],
										'{job}' => $row['job'],
										'{name}' => $row['name'],
										'{paternalLastname}' => $row['paternalLastname'],
										'{maternalLastname}' => $row['maternalLastname'],
										'{email}' => $row['email'],
										'{phone}' => $row['telephone']
										);
									$new_fila = strtr($new_fila,$diccionario);
									$filas .= $new_fila;
								}
								
								$alert = file_get_contents("./views/alert.html");
								$diccionario = array(
										'{type}' => 'alert-success',
										'{title}' => '¡Modificado!',
										'{text}' => 'El usuario se modifico exitosamente.');
								$alert = strtr($alert, $diccionario);
				
								$diccionario = array(
										'{alert}' => $alert);
								$vista = strtr($vista,$diccionario);
				
								$vista = str_replace($fila, $filas, $vista);
								$data['page_title']='Lista de Usuarios'; 
								$data['general_content']=$vista;
								$this->createTemplate($data);
							}
							else{
								$this->msgError();
							}
						}
						else{
							$this->msgError();
						}
					}
					else{
						$this->msgError();
					}
				}
			}
		}
		
		
		/**
		 * Show information about an user
		 */ 
		private function select($ID=''){
			$User=FALSE;
	    	//Validate variables and if variables is set 
			$ID=$this->validateID($ID);
	    	//Show information of an user
	    	if($ID!=FALSE){
				$User=$this->model->select($ID);
			}
			return $User;
		}

		/**
		 * List all system´s users with information about user
		 */
		private function listUsers(){
			$Users=$this->model->listUsers();
			if(isset($_GET['act']) and $_GET['act']=='list'){
				$vista = file_get_contents("./views/UserList.html");
				$inicio_fila = strrpos($vista,'<tr>');
				$final_fila = strrpos($vista,'</tr>') + 5;
				$fila = substr($vista,$inicio_fila,$final_fila-$inicio_fila);
				$filas="";
				foreach ($Users as $row) {
					$new_fila = $fila;
					$diccionario = array(
						'{id}' => $row['idUser'], 
						'{status}' => $row['status']?'Activo':'Inactivo',
						'{user}' => $row['user'],
						'{job}' => $row['job'],
						'{name}' => $row['name'],
						'{paternalLastname}' => $row['paternalLastname'],
						'{maternalLastname}' => $row['maternalLastname'],
						'{email}' => $row['email'],
						'{phone}' => $row['telephone']
						);
					$new_fila = strtr($new_fila,$diccionario);
					$filas .= $new_fila;
				}

				$diccionario = array(
						'{alert}' => '');
				$vista = strtr($vista,$diccionario);

				$vista = str_replace($fila, $filas, $vista);
				$data['page_title']='Lista de Usuarios';
				$data['general_content']=$vista;
				$this->createTemplate($data);	
			}
			return $Users;	
		}
		
		
		/**
		 * If a user don´t remeber is password this function can change the password
		 */
		private function recover(){
			if(empty($_POST)){
				$data['page_title']='Recuperar Contraseña';
				$data['general_content']=file_get_contents('./views/userRecover.html');
				$this->createTemplate($data);
			}
			else{
				$NoSet=FALSE; //Flag to determine if the variables are set
				$User=isset($_POST['user'])?$this->validateUserName($_POST['user']):$NoSet=TRUE;
				$Email=isset($_POST['email'])?$this->validateEmail($_POST['email']):$NoSet=TRUE;
				if($NoSet==FALSE){
					if($User and $Email){
						$result=$this->model->recover($User,$Email);
						if($result){
							require('controllers/mail.php');
							$subject = 'Solicitud de recuperación de contraseña';
							$body = 'El usuario ' . $User . ' solicitó la reposición de la contraseña de su<br>
									 cuenta a la página prow.vv.si.<br>
									 Su contraseña nueva es '.$result;
							$mail = new Email($Email, $subject, $body);
							$mail->send();
							$vista=file_get_contents('./views/userSuccessRecover.html');
							$footer=file_get_contents('./views/pie.html');
							echo $vista;
						}
						else{
							$vista=file_get_contents('views/userErrorRecover.html');
							$msg='Usuario o Email no encontrado en el Sistema';
							$dictionary = array(
								'{msg}' => "<p>$msg</p>",
								);
							
							$vista = strtr($vista, $dictionary).file_get_contents('./views/pie.html');
							echo $vista;
						}
					}
					else{
						$vista=file_get_contents('views/userErrorRecover.html');
						$msg='Datos Erroneos';
						$dictionary = array(
							'{msg}' => "<p>$msg</p>",
							);
						
						$vista = strtr($vista, $dictionary).file_get_contents('./views/pie.html');
						echo $vista;
					}
				
				}
				else{
					$vista=file_get_contents('views/userErrorRecover.html');
							$msg='Faltaron Campos por llenar';
							$dictionary = array(
								'{msg}' => "<p>$msg</p>",
								);
							
							$vista = strtr($vista, $dictionary).file_get_contents('./views/pie.html');
							echo $vista;
				}
			}
		}

		/**
		 * Show the form to change the password and change it
		 */
		private function changepass(){
			if(empty($_POST)){
				$data['page_title']='Cambiar Contraseña';
				$data['general_content']=file_get_contents('./views/userChangePass.html');
				$this->createTemplate($data);
			}
			else{
				$NoSet=FALSE; //Flag to determine if the variables are set
				
				$Pass1=isset($_POST['pass1'])?$this->validatePassword($_POST['pass1']):$NoSet=TRUE;
				$Pass2=isset($_POST['pass2'])?$this->validatePassword($_POST['pass2']):$NoSet=TRUE;
				
				if($NoSet==FALSE){
					if($Pass1!=FALSE and $Pass2!=FALSE and strcmp($Pass1, $Pass2)==0){
						$result=$this->model->changepass($Pass1);
						if($result){
							$data['page_title']='Cambiar Contraseña';
							$data['general_content']=file_get_contents('./views/passwordChanged.html');
							$this->createTemplate($data);
						}
						else{
							$this->msgError('Problemas a l intentar cambiar la contraseña');
						}
					}
					else{
						$this->msgError('Datos Erroneos');
					}
				}
				else{
					$this->msgError('Faltan datos por registrar');
				}
			}
		}
	   
	}
?>
