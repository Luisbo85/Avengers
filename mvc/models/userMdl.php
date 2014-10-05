<?php

	class UserMdl{
		private $ID;
		private $Name;
		private $MaternalLastname;
		private $PaternalLastname;
		private $Email;
		private $Job;
		private $Pass;
		private $Telephone;
		private $DbDriver;
	   
		function __construct(){
			require("models/databaseConfig.inc");
			$this->DbDriver=new mysqli($host,$user,$pass,$bd);
			if($this->DbDriver->connect_error){
				die("No me pude conectar");
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
			$Name=$this->DbDriver->real_escape_string($Name);
			$PaternalLastname=$this->DbDriver->real_escape_string($PaternalLastname);
			$MaternalLastname=$this->DbDriver->real_escape_string($MaternalLastname);
			$Email=$this->DbDriver->real_escape_string($Email);
			$Job=$this->DbDriver->real_escape_string($Job);
			$Telephone=$this->DbDriver->real_escape_string($Telephone);
			if($stmt=$this->DbDriver->prepare("INSERT INTO User (name,maternalLastname,paternalLastname,email,job,telephone) 
		 								  	 			  VALUES (?,?,?,?,?,?)")){
				$stmt->bind_param('ssssss',$Name,$MaternalLastname,$PaternalLastname,$Email,$Job,$Telephone);
				if($stmt->execute()==TRUE){
					$UserInserted=TRUE;
				}
				else{
					$UserInserted=FALSE;
				}
				$stmt->close();
		 	}
			$this->Name=$Name;
			$this->MaternalLastname=$MaternalLastname;
			$this->PaternalLastname=$PaternalLastname;
			$this->Email=$Email;
			$this->Job=$Job;
			$this->Telephone=$Telephone;
			return $UserInserted;
		}
	   
		/**
		 * Delete a user in the Database
		 * @param int $ID
		 * @return boolean $Deleted  
		 */
		function delete($ID){
			$Deleted=FALSE;
			$ID=$this->DbDriver->real_escape_string($ID);
			//Search in the Database and delete if it found it
			if($stmt=$this->DbDriver->prepare("UPDATE user SET status=0 WHERE idUser=?")){
				$stmt->bind_param('i',$ID);
				if($stmt->execute()==TRUE and $stmt->affected_rows>0){
					$Deleted=TRUE;
				}
				$stmt->close();
		 	}
			return $Deleted;
		}
	   
		/**
		 * Modify a property user in the Database
		 * @param int $ID
		 * @param string $Property
		 * @return boolean $Modified  
		 */
		function update($ID,$Name,$MaternalLastname,$PaternalLastname,$Email,$Job,$Telephone){
			$Modified=TRUE;
			$Name=$this->DbDriver->real_escape_string($Name);
			$PaternalLastname=$this->DbDriver->real_escape_string($PaternalLastname);
			$MaternalLastname=$this->DbDriver->real_escape_string($MaternalLastname);
			$Email=$this->DbDriver->real_escape_string($Email);
			$Job=$this->DbDriver->real_escape_string($Job);
			$Telephone=$this->DbDriver->real_escape_string($Telephone);
			//Update in the Database
			if($stmt=$this->DbDriver->prepare("UPDATE user SET name=?,
																paternalLastname=?,
																maternalLastname=?,
																email=?,
																job=?,
																telephone=? WHERE idUser=?")){
				$stmt->bind_param('ssssssi',$Name,$PaternalLastname,$MaternalLastname,$Email,$Job,$Telephone,$ID);
				if($stmt->execute()==TRUE and $stmt->affected_rows>0){
					$Modified=TRUE;
				}
				$stmt->close();
		 	}
			$this->Name=$Name;
			$this->MaternalLastname=$MaternalLastname;
			$this->PaternalLastname=$PaternalLastname;
			$this->Email=$Email;
			$this->Job=$Job;
			$this->Telephone=$Telephone;
			return $Modified;
		}
	   
		/**
		 * Show all users
		 * @param int $ID
		 * @return mixed $User 
		 */
		function select($ID){
			$User=FALSE;
			$ID=$this->DbDriver->real_escape_string($ID);
			$Result=$this->DbDriver->query("SELECT * FROM user WHERE idUser=$ID");
			if($Result!=FALSE){
				$User=$Result->fetch_assoc();
			}
			return $User;   
		}
		
		/**
		 * Get all users and their information from the database
		 * @return array $Users 
		 */
		function listUsers(){
			$Users=FALSE;
			$Result=$this->DbDriver->query("SELECT * FROM user ");
			if($Result!=FALSE){
				$row=$Result->fetch_assoc();
				while($row!=null){
					$Users[]=$row;
					$row=$Result->fetch_assoc();	
				}
			}
			return $Users;
		}
	}
?>
