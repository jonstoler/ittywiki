<?php
	
	// parses pages
	class parser {
		public $title = false;
		public $body  = '';
		public $summary = false;
		public $metadata = [];

		private $file;

		function __construct($file){
			$this->file = $file;
			$str = $file->contents();
			$lines = $this->splitLines($str);

			$this->title($lines);
			$this->summary($str);
			$this->body($lines);
		}

		// splits a string into its constituent lines
		private function splitLines($str){
			$lines = explode(PHP_EOL, $str);
			return $lines;
		}

		private function title(&$lines){
			$firstLine = false;
			foreach($lines as $l){
				if($l !== ''){
					$firstLine = $l;
					break;
				}
			}

			if(!$firstLine){ $this->title = false; }
			else if(strpos(trim($firstLine), '#') === 0){
				$this->title = trim(array_shift($lines), '# ');
			} else {
				$this->title = false;
			}
		}

		private function body(&$lines){
			$text = implode(PHP_EOL, $lines);

			$text = preg_replace_callback("/(\\S*?)\\[\\[(.*)\\]\\](\\S*?)(\\s|$)/uU", function($match){
					$m = explode('|', $match[2]);

					if(count($m) === 1){ // no new title
						$title = $match[1] . $m[0] . $match[3];
						$name = $m[0];
					} else {
						$title = $match[1] . $m[0] . $match[3];
						$name = $m[1];
					}

					global $ittywiki;

					if(!$link = $ittywiki->fs->findPage(wikiname($name))){
						// can't find file, try directory
						if($link = $ittywiki->fs->findNestedDir(wikiname($name))){
							if(!$index = $link->find('index')){
								$this->metadata['links'][] = $index;
								return '<a href="' . $link->link() . '">' . $title . '</a>' . $match[4];
							} else {
								// todo: allow index files to have summaries
								$this->metadata['links'][] = $link;
								return '<a href="' . $link->link() . '">' . $title . '</a>' . $match[4];
							}
						} else { // can't find directory either, deadlink
							$this->metadata['deadlinks'][] = $name;
							return '<a class="dead">' . $title . '</a>' . $match[4];
						}
					} else { // file
						if($link === $this->file){
							// link to self, make it bold instead
							return '**' . $title . '**' . $match[4];
						}

						$this->metadata['links'][] = $link;

						if($summary = $link->summary()){
							$summary = trim($summary);
							return '<a href="' . $link->link() . '"><span class="tooltip" title="' . $summary . '">' . $title . '</span></a>' . $match[4];
						} else {
							return '<a href="' . $link->link() . '">' . $title . '</a>' . $match[4];
						}
					}

					return '<a href="">' . $title . '</a>' . $match[4];
				}
			, $text);

			$text = preg_replace("/\\[(.*)\\]\{(.*)\}/uU", "<span class=\"tooltip\" title=\"$2\">$1</span>", $text);
			$text = preg_replace("/->/uU", "&rarr;", $text);

			$this->body = markdown($text);

			if(g::get('smartypants', false)){
				$this->body = smartypants($text);
			}
		}

		private function summary($str){
			$footnotes = markdownwithfootnotes($str)['footnotes'];

			if(array_key_exists('summary', $footnotes)){
				// todo: allow bold and italic via markdown (but nothing else)
				$summary = $footnotes['summary'];

				$this->summary = $summary;
			} else {
				$this->summary = false;
			}
		}
	}

?>