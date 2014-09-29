<?php
	class ValidationCtrl{
		
		/**
		 * Validate a input a telephone number
		 * @param int $telephone
		 * @return int $telephone
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
		 * @return int $data 
		 */
		protected function validateNumber($data){
	   	 	
			return $data;
		}
	   
	   
		/**
		 * Validate a input text
		 * @param string $text
		 * @return string $text
		 **/
		protected function  validateText($text){
	   	 	
			$regex="/^([a-zA-ZñÑ]+((\s[a-zA-ZñÑ])+)?(\. ?)?\n?)+$/";
			if(!preg_match($regex, $text)){
		   		$text=false;
		   	}
		 	return $text;
		}
	   
		/**
		 * Validate a input name
		 * @param string $name
		 * @return string $name
		 */
		protected function  validateName($name){
	   	
			$regex="/^[a-zA-ZñÑ]+(( [a-zA-ZñÑ]+)+)?$/";
			if(!preg_match($regex,$name)){
	   	 		$name=false;
	   	 	}
	 	 	return $name;
		}
	   
		/**
		 * Validate a input password
		 * @param string $password
		 * @return string $password
		 */
		protected function validatePassword($password){
			return $password;
		}
	   
		/**
		 * Validate a input email
		 * @param string $email
		 * @return string $email
		 */
		protected function validateEmail($email){
	   		
			$regex="/^\w+@[a-z]+\.[a-z]{2,3}$/";
			if(!preg_match($regex,$email)){
				$email=false;
			}
			return $email;
		}
	   
		/**
		 * Validate a input email
		 * @param int $ID
		 * @return int $ID
		 */
		protected function validateID($ID){
			return $ID;
		}
	}
?>