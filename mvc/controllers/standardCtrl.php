<?php
	class StandardCtrl extends ValidationCtrl{
		private $standardMdl;
		function __construct(){
			require('models/standardMdl.php');
			$this->standardMdl=new StandardMdl();
		}
		
		/**
		 * Check if user is logging
		 * @return boolean 
		 */
		function isLogged(){
			if(isset($_SESSION['user']))
				return TRUE;
			return FALSE;
		}
		
		/**
		 * Delete session in server and user
		 */
		function logout(){
			//session_start();
			if($this->isLogged()){
				session_unset();
				session_destroy();
				setcookie(session_name(),'',time()-3600);
			}
		}
		
		/**
		 * Allow a user to login, recibe IDUser and password
		 * @return boolean $Logged
		 */
		function login(){
			$Logged=FALSE;
			$NoSet=FALSE;
			$User=isset($_POST['user'])?$this->validateUserName($_POST['user']):$NoSet=TRUE;
			$Pass=isset($_POST['pass'])?$this->validatePassword($_POST['pass']):$NoSet=TRUE;
			if($NoSet==FALSE){
				if($User!=FALSE and $Pass!=FALSE){
					$result=$this->standardMdl->login($User,$Pass);
					if(is_array($result)){
						$_SESSION['IDuser']=$result['idUser'];
						$_SESSION['job']=$result['job'];
						$_SESSION['user']=$User;
						$_SESSION['status']=$result['status'];
						$Logged=TRUE;
					}
					else{
						$this->msgError();
					}
				}
				else{
					$this->msgError();
				}
			}
			else {
				$this->msgError();
			}
			return $Logged;
		}
		
		/** 
		 * This functions returns true if the user has logged in as Manager
		 * Otherwise returns false
		 * @return boolean
		 */
		function isManager(){
			if(isset($_SESSION['job']) and strcasecmp($_SESSION['job'],'Manager')==0){
				return TRUE;
			}
			return FALSE;
		}
		
		function isUser(){
			if(isset($_SESSION['job']) and strcasecmp($_SESSION['job'],'User')==0){
				return TRUE;
			}
			return FALSE;
		}

		function createTemplate($data){
			$header = file_get_contents('./views/cabecera.html');
			$footer = file_get_contents('./views/pie.html');			
				
			$dictionary = array(
				'{page_title}' => $data['page_title'],
				);

			$view = $header . $data['general_content'] . $footer;
			$view = strtr($view, $dictionary);
			echo $view;
		}

		function goHome(){
			header('Location: ./');
		}
		
		function msgError(){
			$data['page_title']='Error';
			$data['general_content']=file_get_contents('views/Error.html');
			$this->createTemplate($data);
		}
		
		function noAccess(){
			$data['page_title']='Sin Acceso';
			$data['general_content']=file_get_contents('views/NoAccess.html');
			$this->createTemplate($data);
		}
	}
?>