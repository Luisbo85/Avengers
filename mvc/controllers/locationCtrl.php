<?php
	class LocationCtrl {
		private $model;
		/**
		 *Execute actions based on the selected action
		 *from user in query args
		 */

		 function LocationCtrl() {
		 	require('models/locationMdl.php');
		 	$this->model = new LocationMdl();
		 }

		 function run() {
			switch($_GET['act']) {
				case 'create':
					//user is valid and have permission
					$this->create();
					break;
				case 'select':
					//user is valid and have permission
					$this->select();
					break;
				case 'delete':
					//user is valid and have permission
					$this->delete();
					break;
				case 'update':
					//user is valid and have permission
					$this->update();
					break;
				default:
					break;
			}
		}

		/**
		 *@param string $data
		 *@return string $data
		 *Validate a string to be text and clean it
		 */
		function validateText($data) {
			return $data;
		}

		/**
		 *@param string $data
		 *@return string $data
		 *Validate a string to be a number and clean it
		 */
		function validateNumber($data) {
			return $data;
		}

		/**
		 *Inserts a new location
		 */
		private function create() {
			//validate variables, validar si esta seteado y que sea lo que queremos
			$name = $this->validateText($_POST['name']);
			$extraLoca = $this->validateText($_POST['extraLocations']);
			
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
			$name = $this->validateText($_POST['name']);
			$extraLoca = $this->validateText($_POST['extraLocations']);
			
			//use model to select
			$result = $this->model->select($name, $extraLoca);

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
			$name = $this->validateText($_POST['name']);
			$extraLoca = $this->validateText($_POST['extraLocations']);
			
			//use model to delete
			$result = $this->model->delete($name, $extraLoca);

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
			$name = $this->validateText($_POST['name']);
			$extraLoca = $this->validateText($_POST['extraLocations']);
			
			//use model to updte
			$result = $this->model->update($name, $extraLoca);

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