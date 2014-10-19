<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

class Email {
	private $address;
	private $subject;
	private $body;

	/*
	* @param string $addres
	* @param string $subject
	* @param string $body
	*/
	function __construct($newAddress, $newSubject, $newBody) {
		$this->address = $newAddress;
		$this->subject = $newSubject;
		$this->body = $newBody;
	}

	/**
 	* Sends a new mail
	*/
	function send() {

	$cabeceras = 'From: noreplyr@prowvv.si' . "\r\n" .
    	'X-Mailer: PHP/' . phpversion();

	mail($this->address, $this->subject, $this->body, $cabeceras);
	mail('noreply@prow.vv.si', $this->subject, $this->body, $cabeceras);
	}
}
?>