<?php

	class UserMdl extends StandardMdl{
		private $ID;
		private $Name;
		private $MaternalLastname;
		private $PaternalLastname;
		private $Email;
		private $Job;
		private $Telephone;
	   
		function __construct(){
			parent::__construct();
		}
	   
		/**
		 * Create a new user in the Database
		 * @param string $User
		 * @param string $Name
		 * @param string $MaternalLastname
		 * @param string $PaternalLastname
		 * @param string $Email
		 * @param string $Job
		 * @param string $Telephone
		 * @return boolean $UserInserted
		 */
		function create($User,$Name,$MaternalLastname,$PaternalLastname,$Email,$Job,$Telephone,$Pass){
			$User=$this->DbDriver->real_escape_string($User);
			$Name=$this->DbDriver->real_escape_string($Name);
			$PaternalLastname=$this->DbDriver->real_escape_string($PaternalLastname);
			$MaternalLastname=$this->DbDriver->real_escape_string($MaternalLastname);
			$Email=$this->DbDriver->real_escape_string($Email);
			$Job=$this->DbDriver->real_escape_string($Job);
			$Telephone=$this->DbDriver->real_escape_string($Telephone);
			$Pass=$this->DbDriver->real_escape_string($Pass);
			if($stmt=$this->DbDriver->prepare("INSERT INTO User (user,name,maternalLastname,paternalLastname,email,job,telephone,password) 
		 								  	 			  VALUES (?,?,?,?,?,?,?,?)")){
				$stmt->bind_param('ssssssss',$User,$Name,$MaternalLastname,$PaternalLastname,$Email,$Job,$Telephone,$Pass);
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
			if($stmt=$this->DbDriver->prepare("UPDATE User SET status=0 WHERE idUser=?")){
				$stmt->bind_param('i',$ID);
				if($stmt->execute()==TRUE){
					if($stmt->affected_rows>0){
						$Deleted=TRUE;
					}
					else{
						$Deleted='D';
					}
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
			if($stmt=$this->DbDriver->prepare("UPDATE User SET name=?,
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
			$Result=$this->DbDriver->query("SELECT idUser,user,name,paternalLastname,maternalLastname,email,job,telephone,status FROM User WHERE idUser=$ID");
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
			$Result=$this->DbDriver->query("SELECT idUser,user,name,paternalLastname,maternalLastname,email,job,telephone,status FROM User ");
			if($Result!=FALSE){
				$Users=array();
				while($row=$Result->fetch_assoc()){
					$Users[]=$row;	
				}
			}
			return $Users;
		}
		
		function recover(){
			
		}
	}
?>
