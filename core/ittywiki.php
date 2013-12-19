<?php
	
	if(!defined('ITTYWIKI')){ die('direct access is not allowed'); }

	class ittywiki {

		// filesystem (wiki files)
		public $fs = null;

		function load(){
			$this->fs = new dir(g::get('root.wiki'));

			$this->render();
		}

		function render(){
			// get requested path
			$path  = $_SERVER['DOCUMENT_ROOT'];
			$path .= $_SERVER['REQUEST_URI'];

			$path = explode(g::get('root'), $path);
			array_shift($path);
			$path = implode('', $path);

			// do we have to render a file (or directory)?
			$page = false;

			// the template to load
			$template = 'file';
			$title = '';

			// are we looking for a directory (or a file?)
			$isDir = (substr($path, -1) == '/');

			path($path);

			if($isDir){
				$template = 'dir';
				$page = $this->fs->findDir($path);

				if(count($path->path) == 0){ // wiki root
					$page = $this->fs;
				}

				if($page && $index = $page->find('index')){
					// if a directory has an index file, show that instead

					// load contents before altering path
					$index->render();
					
					// set the name & path to match the directory
					// (so it doesn't say "index")
					$index->name = $page->name;
					$index->wikiname = $page->wikiname;
					$index->path->removeLast();

					$page = $index;

					$template = 'file';
				}
			} else {
				// template already set as file
				$page = $this->fs->find($path);
			}

			if($path->command && $page){ // special commands
				$page->path->command = $path->command;
				switch($path->command){
					case 'info': $template = 'info'; break;
					case 'help': $template = 'help'; break;
					case 'version': $template = 'version'; break;
					default: break;
				}
			}

			if(!$page){ // no page exists at path
				$page = $this->fs;
				$template = 'notfound';
			}

			include_once(g::get('root.core') . DS . 'templates' . DS . $template . '.php');
		}

	}

?>