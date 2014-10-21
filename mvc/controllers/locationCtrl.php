<?php
	class LocationCtrl extends StandardCtrl{
		private $model;
		
		/**
		 *Execute actions based on the selected action
		 *from user in query args
		 */
		function LocationCtrl() {
			parent::__construct();
		 	require('models/locationMdl.php');
		 	$this->model = new LocationMdl();
		}

		function run() {
			switch($_GET['act']) {
				case 'create':
					//user is valid and have permission
					if($this->isLogged()){
		  				if($this->isManager() or $this->isUser()){
		  					$this->create();
		  				}
						else{
							require('views/NoAccess.php');
						}
		  			}
					else{
						require('views/needLogin.php');
					}
					break;
				case 'select':
					//user is valid and have permission
					if($this->isLogged()){
		  				if($this->isManager() or $this->isUser()){
		  					$this->select();
		  				}
						else{
							require('views/NoAccess.php');
						}
		  			}
					else{
						require('views/needLogin.php');
					}
					break;
				case 'selectAll':
					//user is valid and have permission
					if($this->isLogged()){
		  				if($this->isManager() or $this->isUser()){
		  					$this->selectAll();
		  				}
						else{
							require('views/NoAccess.php');
						}
		  			}
					else{
						require('views/needLogin.php');
					}
					break;
				case 'delete':
					//user is valid and have permission
					if($this->isLogged()){
		  				if($this->isManager()){
		  					$this->delete();
		  				}
						else{
							require('views/NoAccess.php');
						}
		  			}
					else{
						require('views/needLogin.php');
					}
					break;
				case 'update':
					//user is valid and have permission
					if($this->isLogged()){
		  				if($this->isManager() or $this->isUser()){
		  					$this->update();
		  				}
						else{
							require('views/NoAccess.php');
						}
		  			}
					else{
						require('views/needLogin.php');
					}
					break;
				default:
					break;
			}
		}

		/**
		 *Inserts a new location
		 */
		private function create() {
			$name = isset($_POST['name']) ? $this->validateTextNumber($_POST['name']) : '';
			$extraLoca = isset($_POST['extraLocations']) ? $this->validateTextNumber($_POST['extraLocations']) : '';
			
			//use model to insert
			$result = $this->model->create($name, $extraLoca);

			//insert successful
			if($result) {
				//load the view
				require('views/locationInserted.php');
			} else {
				require('views/Error.php');
			}
		}

		/**
		 *Gets a location info
		 */
		private function select() {
			//validate variables, validar si esta seteado y que sea lo que queremos
			$idLocation = isset($_POST['idLocation']) ? $this->validateNumber($_POST['idLocation']) : '';
			
			//use model to delete
			$result = $this->model->select($idLocation);

			//select successful
			if($result) {
				//load the view
				require('views/locationSelected.php');
			} else {
				require('views/Error.php');
			}
		}

		/**
		 *Gets all locations
		 */
		private function selectAll() {
			//use model to delete
			$result = $this->model->selectAll();

			//select successful
			if($result) {
				//load the view
				require('views/locationSelected.php');
			} else {
				require('views/Error.php');
			}
		}

		/**
		 *Deletes a location
		 */
		private function delete() {
			//validate variables, validar si esta seteado y que sea lo que queremos
			$idLocation = isset($_POST['idLocation']) ? $this->validateNumber($_POST['idLocation']) : '';
			
			//use model to delete
			$result = $this->model->delete($idLocation);

			//delete successful
			if($result) {
				//load the view
				require('views/locationDeleted.php');
			} else {
				require('views/Error.php');
			}
		}

		/**
		 *Updates a location
		 */
		private function update() {
			//validate variables, validar si esta seteado y que sea lo que queremos
			$idLocation = isset($_POST['idLocation']) ? $this->validateNumber($_POST['idLocation']) : '';
			$name = isset($_POST['name']) ? $this->validateTextNumber($_POST['name']) : '';
			$extraLoca = isset($_POST['extraLocations']) ? $this->validateTextNumber($_POST['extraLocations']) : '';
			
			//use model to updte
			$result = $this->model->update($idLocation, $name, $extraLoca);

			//update successful
			if($result) {
				//load the view
				require('views/locationUpdated.php');
			} else {
				require('views/Error.php');
			}
		}
	}
?>