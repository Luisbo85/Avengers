<?php
class ValidationCtrl{
	
	/**
	 * Validate a input a telephone number
	 * @param int $telephone
	 * @return mixed $telephone
	 */
	protected function validateTelephone($telephone){
   	 
		$regex="/^\d{8,10}$/";
	   	if(!preg_match($regex,$telephone)){
	   		$telephone=false;
	   	}
		return $telephone;
	}
   
   
	/**
	 * Validate a input a number
	 * @param int $data
	 * @return mixed $data 
	 */
	protected function validateNumber($data){
   	 	$regex = "/([0-9]+)$/";
		if (!preg_match($regex, $data, $matches)) {
			$data = false;
		} 

		return $data;
	}

	/**
	 * Validate an input to be text-number
	 * @param int $data
	 * @return mixed $data 
	 */
	protected function validateTextNumber($data){
   	 	$regex = "/([a-zA-Z0-9]+)$/";
		if (!preg_match($regex, $data, $matches)) {
			$data = false;
		} 

		return $data;
	}
   
   
	/**
	 * Validate a input text
	 * @param string $text
	 * @return mixed $text
	 **/
	protected function validateText($text){
   	 	
		$regex="/^([a-zA-ZñÑ]+((\s[a-zA-ZñÑ])+)?(\. ?)?\n?)+$/";
		if(!preg_match($regex, $text)){
	   		$text=false;
	   	}
	 	return $text;
	}
   
	/**
	 * Validate a input name
	 * @param string $name
	 * @return mixed $name
	 */
	protected function validateName($name){
   	
		$regex="/^[a-zA-ZñÑ]+(( [a-zA-ZñÑ]+)+)?$/";
		if(!preg_match($regex,$name)){
   	 		$name=false;
   	 	}
 	 	return $name;
	}
   
	/**
	 * Validate a input password
	 * @param string $password
	 * @return mixed $password
	 */
	protected function validatePassword($password){
		$regex="/^(\w|\d){8,10}$/";
		if(!preg_match($regex, $password)){
	   		$password=false;
	   	}
		return $password;
	}
   
	/**
	 * Validate a input email
	 * @param string $email
	 * @return mixed $email
	 */
	protected function validateEmail($email){
   		
		$regex="/^\w+@[a-z]+\.[a-z]{2,3}$/";
		if(!preg_match($regex,$email)){
			$email=false;
		}
		return $email;
	}
   
	/**
	 * Validate a input ID
	 * @param int $ID
	 * @return mixed $ID
	 */
	protected function validateID($ID){
		$regex="/^\d+$/";
	   	if(!preg_match($regex,$ID)){
	   		$ID=false;
	   	}
		return $ID;
	}

	/**
	 * Validate a date have format year(4 numbers)-month(2 numbers)-day(2 numbers) hour(2 numbers):minutes(2 numbers):seconds(2 numbers)
	 * @param string $data
	 * @return mixed $data
	 */
	protected function validateDateTime($data){
   	 	
		$regex="/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/";
		if(!preg_match($regex, $data)){
	   		$data = false;
	   	}
	 	return $data;
	}
	
	/**
	 * Validate if a number is an integer or real number but without sign
	 * @param $string $real
	 * @return mixed $real
	 */
	protected function validateRealNumber($real){
		$regex="/^\d+(\.\d+)?$/";
	   	if(!preg_match($regex,$real)){
	   		$real=false;
	   	}
		return $real;
	}
	
	/**
	 * Validate if a user name is correct
	 * @param string $user
	 * @param mixed $user
	 */
	protected function validateUserName($user)
	{
		$regex="/^(\w\d?)+$/";
	   	if(!preg_match($regex,$user)){
	   		$user=false;
	   	}
		return $user;
	}
}
?>