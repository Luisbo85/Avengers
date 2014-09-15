<?php
	class LocationMdl {
		private $name;
		private $extraLoca;
		
		function __construct() {
			
		}

		/*
		 *@param string $name
		 *@param string $extraLoca
		 *@return true in success
		 *Insert in database new location
		 */
		function create($name, $extraLoca) {
			//conection a la base de datos
			$this->$name = $name;
			$this->$extraLoca = $extraLoca;		

			//true if success
			return true;
		}

		/*
		 *@param string $name
		 *@param string $extraLoca
		 *@return true in success
		 *Delete a location in DB
		 */
		function delete($name, $extraLoca) {
			//conection a la base de datos
			$this->$name = $name;
			$this->$extraLoca = $extraLoca;		

			//true if success
			return true;
		}

		/*
		 *@param string $name
		 *@param string $extraLoca
		 *@return true in success
		 *Selects a location info
		 */
		function select($name, $extraLoca) {
			//conection a la base de datos
			$this->$name = $name;
			$this->$extraLoca = $extraLoca;		

			//true if success
			return true;
		}

		/*
		 *@param string $name
		 *@param string $extraLoca
		 *@return true in success
		 *Updates a location info
		 */
		function update($name, $extraLoca) {
			//conection a la base de datos
			$this->$name = $name;
			$this->$extraLoca = $extraLoca;		

			//true if success
			return true;
		}
	}
?>