<?php

	// directory class
	class dir {

		// subfiles
		public $files = [];

		// current path
		public $path = null;

		// name of directory
		public $name = '';

		// wikified name
		public $wikiname = '';


		// build a dir object from a path
		function __construct($path){

			// allows us to accept a string or a path as an argument
			$this->path = path($path);

			$this->name = $this->path->last();
			$this->wikiname = wikiname($this->name);

			$files = dir::read($this->path->toString());

			foreach($files as $f){
				// "file" is a directory
				if(dir::check($this->path->toString() . DS . $f)){
					$dir = new dir($this->path->toString() . DS . $f);
					$this->files[] = $dir;
				} else {
					$file = new file($this->path->toString() . DS . $f);
					$this->files[] = $file;
				}
			}
		}

		// private method to find a file or folder recursively or not
		private function _find($path, $dir = false, $recursive = false){
			$currentDir = $this;

			path($path);

			for($i = 0; $i < count($path->path) - 1; $i++){
				$currentDir = $currentDir->_contains($path->path[$i], true);

				if(!$currentDir){ return false; }
			}

			$file = $currentDir->_contains($path->last(), $dir, $recursive);

			return $file;
		}

		// find page at path
		function find($path){
			return $this->_find($path);
		}

		// find dir at path
		function findDir($path){
			return $this->_find($path, true);
		}

		// find page, search all subdirectories
		function findPage($path){
			return $this->_find($path, false, true);
		}

		// find dir, search all subdirectories
		function findNestedDir($path){
			return $this->_find($path, true, true);
		}

		private function _contains($name, $dir = false, $recursive = false, $wikiname = false){
			foreach($this->files as $f){
				if(is_a($f, 'dir')){
					if($dir && strtolower($f->wikiname) === strtolower($name)){ return $f; }

					if($recursive){
						$nested = $f->_contains($name, $dir, $recursive);
						if($nested){ return $nested; }
					}
				} else if(is_a($f, 'file') && !$dir){
					if(strtolower($f->wikiname) === strtolower($name)){ return $f; }
				}
			}

			return false;
		}

		// returns a link to this page in the wiki
		function link(){
			$ex = explode(DS, g::get('root.wiki'));
			array_shift($ex);

			$path = $this->path->path;

			foreach($ex as $e){
				if($path[0] === $e){
					array_shift($path);
				}
			}

			if(count($path) > 0){
				return g::get('root.url') . '/' . wikiname(implode('/', $path)) . '/';
			} else {
				return g::get('root.url') . '/';
			}
		}

		// array of breadcrumbs
		function breadcrumbs(){
			$ex = explode(DS, g::get('root.wiki'));
			array_shift($ex);

			$path = $this->path->path;

			foreach($ex as $e){
				if($path[0] === $e){
					array_shift($path);
				}
			}

			if(count($path) === 0){
				return [['name' => g::get('wiki.title'), 'url' => null]];
			}

			$url = g::get('root.url') . '/';
			$bc = [['name' => g::get('wiki.title'), 'url' => $url]];
			foreach($path as $p){
				$url .= wikiname($p) . '/';
				$bc[] = ['name' => $p, 'url' => $url];
			}

			return $bc;
		}

		function size(){
			$size = 0;
			foreach($this->files as $f){
				$size += $f->size();
			}

			return $size;
		}

		function prettySize(){
			return file::cleanSize($this->size());
		}

		// is this a valid directory?
		static function check($dir){
			return is_dir($dir);
		}

		// list files in directory
		static function read($dir){
			if(!dir::check($dir)){ return false; }

			// files to ignore
			$ignore = ['.', '..', '.DS_Store'];

			return array_diff(scandir($dir), $ignore);
		}

	}
?>