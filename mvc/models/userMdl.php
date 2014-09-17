<?php

class UserMdl{
	private $ID;
	private $Name;
	private $MaternalLastname;
	private $PaternalLastname;
	private $Email;
	private $Job;
	private $Pass;
   
   /**
    *Create a new user in the Database
    * @param string $Name
    * @param string $MaternalLastname
    * @param string $PaternalLastname
    * @param string $Email
    * @param string $Job
    * @param string $Pass
    * @return boolean $UserInserted
   **/
   function create($Name,$MaternalLastname,$PaternalLastname,$Email,$Job,$Pass){
   	 $UserInserted=true;
     //Conection with database
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
    **/
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
    **/
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
    **/
   function select($ID)
   {
   	  $Users=array();
	  //Select from the Database
	  $this->Name=$Name;
	  $this->MaternalLastname=$MaternalLastname;
	  $this->PaternalLastname=$PaternalLastname;
	  $this->Email=$Email;
	  $this->Job=$Job;
	  $this->Pass=$Pass;
      return $Users;   
   }
   
   
}
?>
