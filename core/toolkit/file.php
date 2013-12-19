<?php
	
	// file class - for files in the filesystem	
	class file {

		// filename
		public $name = '';

		// wikified name
		public $wikiname = '';

		// extension (as string, multiple extensions allowed)
		public $ext = '';

		// path to file
		public $path = null;

		// page parser
		private $_parser = null;

		// lazy loaded file contents
		private $_contents = null;


		function __construct($path){
			$this->path = path($path);

			$filename = $this->path->last();

			$ex = explode('.', $filename);

			if(count($ex) == 1){
				// no extension
				$this->name = $ex[0];
			} else {
				$this->name = $ex[0];
				$this->ext  = implode('.', array_slice($ex, 1));
			}

			$this->wikiname = wikiname($this->name);

		}

		// returns a link to this page in the wiki
		function link(){
			$ex = explode(DS, g::get('root.wiki'));
			array_shift($ex);

			$path = $this->path->path;
			$path[count($path) - 1] = $this->wikiname; // remove extension, use wikiname

			foreach($ex as $e){
				if($path[0] === $e){
					array_shift($path);
				}
			}

			return g::get('root.url') . '/' . wikiname(implode('/', $path));
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

			$url = g::get('root.url') . '/';
			$bc = [['name' => g::get('wiki.title'), 'url' => $url]];

			if(count($path) === 0){
				return $bc;
			}

			for($i = 0; $i < count($path) - 1; $i++){
				$url .= wikiname($path[$i]) . '/';
				$bc[] = ['name' => $path[$i], 'url' => $url];
			}
			$bc[] = ['name' => $this->name, 'url' => ''];
			

			return $bc;
		}

		// preps the parser (allowing for lazy loading)
		function render(){
			if(is_null($this->_parser)){
				$this->_parser = new parser($this);
			}
		}

		// lazy loaded file content
		function contents(){
			if(is_null($this->_contents)){
				$this->_contents = file_get_contents($this->path->toString());
			}

			return $this->_contents;
		}
		
		function body(){
			$this->render();

			return $this->_parser->body;
		}

		function title(){
			$this->render();

			if(!$title = $this->_parser->title){
				return ucwords($this->name);
			}

			return $title;
		}

		function summary(){
			$this->render();
			return $this->_parser->summary;
		}

		function metadata(){
			$this->render();
			return $this->_parser->metadata;
		}

		function size(){
			return filesize($this->path->toString());
		}

		function prettySize(){
			return file::cleanSize($this->size());
		}

		// prettifies file size
		// blatantly stolen from kirby toolkit's file::niceSize()
		static function cleanSize($size){			
			if($size <= 0){ return '0 kb'; }
			
			$units = ['b', 'kb', 'mb', 'gb', 'tb', 'pb', 'eb', 'zb', 'yb'];
			
			return round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . $units[$i];
		}
	}

?>