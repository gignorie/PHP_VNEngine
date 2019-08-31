<?
/******************************/
/*           Author:          */
/*          Gignorie          */
/*         Description:       */
/*      Движок для создания   */
/*       визуальных новелл    */
/*        через XML-файлы     */
/*         используемые       */
/*         как сценарии.      */
/*             2019           */
/*****************************/


class VNEngine {
	
	private $_is_open;
	public $XMLArray;
	private $__index;
	public $__xml;
	
	public function __construct($xmlpath = false){
		$__index = -1;
		if($xmlpath)
			$this->openDialog($xmlpath);
	}
	
	function openDialog($xmlpath){
		$xml = new SimpleXMLElement($xmlpath);
		$this->_is_open = true;
		$this->__xml = $xml;
		return $this->__xml;
	}
	
	function openNode($index){
		if(!$this->_is_open) return 'err1';
		
		return $this->__xml->node[$index];
	}
	
	function set_index($v){
		$this->__index = $v;
	}
	
	function get_index(){
		return $this->__index;
	}
	
	function next($answer = -1){
		if(!$this->_is_open) return 'err1';
		
		$node = $this->openNode($this->__index+1);
		if((bool)$node->endnode && $answer<=-1){
			++$this->__index;
			return $node;
		} elseif($answer>-1) {
			$this->__index = $node->answers->answer[$answer]['tonode']-1;
			return $this->next();
		} else {
			$XMLArray['answers'] = $node;
			$this->XMLArray = $XMLArray;
			return false;
		}
	}
	
	function back($answer = -1){
		if(!$this->_is_open) return 'err1';
		
		$node = $this->openNode($this->__index-1);
		if((bool)$node->endnode && $answer<=-1){
			++$this->__index;
			return $node;
		} elseif($answer>-1) {
			$this->__index = $node->answers->answer[$answer]['tonode']-1;
			return $this->next();
		} else {
			$XMLArray['answers'] = $node;
			$this->XMLArray = $XMLArray;
			return false;
		}
	}
	
	function count(){
		if(!$this->_is_open) return 'err1';
		
		return count($this->__xml->node);
	}
	
}
