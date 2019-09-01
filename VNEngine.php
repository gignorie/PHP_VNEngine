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
	public $levels;
	private $__index;
	public $__xml;
	
	public function __construct($xmlpath = false){
		$__index = -1;
		if(file_exists($xmlpath))
			$this->openDialog($xmlpath);
	}
	
	function openDialog($xmlpath){
		if(!file_exists($xmlpath)) return false;
		
		$xml = new SimpleXMLElement(file_get_contents($xmlpath));
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
	
	function addLevel($name, $xmlpath){
		$levels = $this->levels;
		$levels[$name] = $xmlpath;
		$this->levels = $levels;
	}
	
	function delLevel($name){
		$levels = $this->levels;
		unset($levels[$name]);
		$this->levels = $levels;
	}
	
	function next($answer = -1){
		if($this->count<0) return 'err2';
		if(!$this->_is_open) return 'err1';
		
		$node = $this->openNode($this->__index+1);
		if((bool)$node->endnode && $answer<=-1){
			++$this->__index;
			return $node;
		} elseif($answer>-1 && (bool)$node->answers->answer[$answer]->end) {
			$level = (string)$node->answers->answer[$answer]->tolevel;
			if(!empty($level)){
				$this->__construct($this->levels[$level]);
				return ['toLevel'=>$level];
			}
			$toNode = $node->answers->answer[$answer]['tonode'];
			$node_index = $toNode == $this->__index+1?$toNode:$toNode-1;
			$this->__index = $node_index;
			return $this->next();
		} else {
			$XMLArray['answers'] = $node;
			$this->XMLArray = $XMLArray;
			return false;
		}
	}
	
	function back($answer = -1){
		if($this->__index<0) return 'err2';
		if(!$this->_is_open) return 'err1';
		
		$node = $this->openNode($this->__index-1);
		if((bool)$node->endnode){
			--$this->__index;
			return $node;
		} else {
			return false;
		}
	}
	
	function count(){
		if(!$this->_is_open) return 'err1';
		
		return count($this->__xml->node);
	}
	
}
