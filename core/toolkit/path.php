<?php
	
	class path {
		public $path = [];
		public $command = false;

		// build a path from a string
		function __construct($pathStr){
			$ex = explode(DS, $pathStr);
			$split = [];

			foreach($ex as $e){
				if($e !== ''){ $split[] = $e; }
			}

			if(strpos($e, ':')){
				$ex = explode(':', $split[count($split) - 1]);
				$this->command = array_pop($ex);
				$split[count($split) - 1] = $ex[0];
			}

			$this->path = $split;
		}

		// return string representation of path
		function toString(){
			return DS . implode($this->path, DS);
		}

		// get last object from path
		function last(){
			return $this->last_array($this->path);
		}

		function removeLast(){
			return array_pop($this->path);
		}

		// get last element of array without affecting its pointer
		// (array is passed by reference)
		private function last_array($a){
			return end($a);
		}
	}

?>