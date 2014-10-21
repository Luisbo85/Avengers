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
			$Pass=isset($_POST['Password'])?$this->validatePassword($_POST['Password']):$NoSet=TRUE;
			if($NoSet==FALSE){
				if($User!=FALSE and $Pass!=FALSE){
					$result=$this->standardMdl->login($User,$Pass);
					if(is_array($result)){
						$_SESSION['IDuser']=$result['idUser'];
						$_SESSION['job']=$result['job'];
						$_SESSION['user']=$User;
						$_SESSION['status']=$result['status']; 
						//$_SESSION['user']='pedro';
						$Logged=TRUE;
					}
					else{
					//	require('views/Error.php');
					}
				}
				else{
					//require('views/Error.php');
					echo 'Contraseña:',"$Pass",' o Usuarion:',"$User";
				}
			}
			else {
				//require('views/Error.php');
				echo 'No set';
			}
			return $Logged;
		}
		
		/** 
		 * This functions returns true if the user has logged in as Manager
		 * Otherwise returns false
		 * @return boolean
		 */
		function isManager(){
			if(isset($_SESSION['job']) and $_SESSION['job']=='Manager'){
				return TRUE;
			}
			return FALSE;
		}
		
		function isUser(){
			if(isset($_SESSION['job']) and $_SESSION['job']=='User'){
				return TRUE;
			}
			return FALSE;
		}
		
	}
?>