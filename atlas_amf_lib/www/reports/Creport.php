<?php
include ('../../includes/pdf/class.ezpdf.php');
class Creport extends Cezpdf {
// make a location to store the information that will be collected duringdocument formation
var $reportContents = array();
// it is neccesary to manually call the constructor of the extended class
function Creport($p,$o){
	$this->Cezpdf($p,$o);
}

	public function dots($info){
		// draw a dotted line over to the right and put on a page number
		$tmp = $info['p'];
		$lvl = $tmp[0];
		$lbl = substr($tmp,1);
		$xpos = 520;
		switch($lvl){
			case '1':
			$size=16;
			$thick=1;
			break;
			case '2':
			$size=12;
			$thick=0.5;
			break;
			}
			$this->saveState();
			$this->setLineStyle($thick,'round','',array(0,10));
			$this->line($xpos,$info['y'],$info['x']+5,$info['y']);
			$this->restoreState();
			$this->addText($xpos+5,$info['y'],$size,$lbl);
		}
}
?>