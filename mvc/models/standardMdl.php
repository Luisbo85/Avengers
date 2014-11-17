<?php
	class StandardMdl{
		protected $DbDriver;
		
		function __construct() {
			require("models/databaseConfig.inc");
			$this->DbDriver=new mysqli($host,$user,$pass,$bd);
			if($this->DbDriver->connect_error){
				die("No me pude conectar");
			}
		}
		
		/**
		 * Go to database an search user, password, ID and job. Return an arary if found user and passwords
		 * is the same 
		 * @param string $User
		 * @param string $Pass
		 * @return mixed $Logged 
		 */
		function login($User,$Pass){
			$Logged=null;
			$User=$this->DbDriver->real_escape_string($User);
			$Result=$this->DbDriver->query("SELECT idUser,password,job,status FROM User WHERE user='$User'");
			if($Result!=FALSE){
				$Logged=$Result->fetch_assoc();
				if(strcmp($Pass, $Logged['password'])!=0 or $Logged['status']==0){
					$Logged=FALSE;
				}
			}
			else{
				$Logged=FALSE;
			}
			return $Logged;
		}
	}
	
?>