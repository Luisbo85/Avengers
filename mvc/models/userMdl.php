<?php

	class UserMdl{
		private $ID;
		private $Name;
		private $MaternalLastname;
		private $PaternalLastname;
		private $Email;
		private $Job;
		private $Pass;
		public $bd_driver;
	   
		function __construct(){
			require("database_config.inc");
			$this->db_driver=new mysqli($host,$user,$pass,$bd);
			if($this->db_driver->connect_error){
				die("No me pude conectar");
			}
			else{
				echo 'Exito!',"<br/>";	
			}
			
		}
	   
		/**
		 * Create a new user in the Database
		 * @param string $Name
		 * @param string $MaternalLastname
		 * @param string $PaternalLastname
		 * @param string $Email
		 * @param string $Job
		 * @param string $Telephone
		 * @return boolean $UserInserted
		 */
		function create($Name,$MaternalLastname,$PaternalLastname,$Email,$Job,$Telephone){
			$UserInserted;
			$Name=$this->db_driver->real_escape_string($Name);
			$PaternalLastname=$this->db_driver->real_escape_string($PaternalLastname);
			$MaternalLastname=$this->db_driver->real_escape_string($MaternalLastname);
			$Email=$this->db_driver->real_escape_string($Email);
			$Job=$this->db_driver->real_escape_string($Job);
			$Telephone=$this->db_driver->real_escape_string($Telephone);
			$result=$this->db_driver->query("INSERT INTO User (name,maternalLastname,paternalLastname,email,job,telephone) 
		 								  	 VALUES ('$Name','$MaternalLastname','$PaternalLastname','$Email','$Job','$Telephone')");
			if($result==FALSE){
				$UserInserted=FALSE;
			}
			else{
				$UserInserted=TRUE;
			}
			$this->Name=$Name;
			$this->MaternalLastname=$MaternalLastname;
			$this->PaternalLastname=$PaternalLastname;
			$this->Email=$Email;
			$this->Job=$Job;
			$this->Pass=$Pass;
			return $UserInserted;
		}
	   
		/**
		 * Delete a user in the Database
		 * @param int $ID
		 * @return boolean $Deleted  
		 */
		function delete($ID){
			$Deleted=true;
			//Search in the Database
			return $Deleted;
		}
	   
		/**
		 * Modify a property user in the Database
		 * @param int $ID
		 * @param string $Property
		 * @return boolean $Modified  
		 */
		function update($Name,$MaternalLastname,$PaternalLastname,$Email,$Job,$Pass){
			$Modified=true;
			//Update in the Database
			$this->Name=$Name;
			$this->MaternalLastname=$MaternalLastname;
			$this->PaternalLastname=$PaternalLastname;
			$this->Email=$Email;
			$this->Job=$Job;
			$this->Pass=$Pass;
			return $Modified;
		}
	   
		/**
		 * Show all users
		 * @param int $ID
		 * @return array $Users  
		 */
		function select($ID){
			$Users=array('Arreglo','Con','Datos','De','Usuario');
			//Select from the Database
			return $Users;   
		}
		
		/**
		 * Get all users and their information from the database
		 * @return array $Users 
		 */
		function listUsers(){
			$Users=array('Usuarios','DEL', 'Sistem');
			
			return $Users;
		}
	}
?>
