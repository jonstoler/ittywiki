<?php

class wikiparser extends ParsedownExtra {
	function __construct(){
		parent::__construct();
		$this->headers = [];
		$this->summary = false;
	}

	function identifyAtx($line){
		$atx = parent::identifyAtx($line);
		$this->headers[] = ['text' => $atx['element']['text'], 'level' => $atx['element']['name'], 'hash' => $atx['element']['text']];
		$atx['element']['text'] .= ' <a name="' . $atx['element']['text'] . '"></a>';

		return $atx;
	}

	function identifyFootnote($line){
		$footnote = parent::identifyFootnote($line);
		
		if($footnote['id'] === 'summary'){
			$this->summary = $footnote['data']['text'];
			$footnote['summary'] = true;
		}

		return $footnote;
	}

	function buildFootnoteElement(){
		$element = parent::buildFootnoteElement();

		$nonSummary = false;
		foreach($this->Definitions['Footnote'] as $name => $data){
			if(!isset($data['summary'])){
				$nonSummary = true;
			}
		}

		// lame hack
		if($nonSummary){ return ['name' => 'span']; }

		return $element;
	}

}

?>
