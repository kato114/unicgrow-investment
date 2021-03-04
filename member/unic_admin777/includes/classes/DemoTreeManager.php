<?php
/**************************************
     File Name: DemoTreeManager.php
     Begin:  Sunday, April, 12, 2009, 13:39 AM
     Author: Ozan Koroglu
 			 Ahmet Oguz Mermerkaya 	
     Email:  koroglu.ozan@gmail.com
     		 ahmetmermerkaya@hotmail.com
 ***************************************/ 

require_once('ITreeManager.php');

class DemoTreeManager implements ITreeManager
{	
 	public function insertElement($name, $real_parent, $slave)
	{
 		$insertId = rand(0, 10000);				
		$out =  '({ "elementId":"'.$insertId.'", "elementName":"'.$name.'"})';
		return $out;
 	}
 	 	
 	
	
 	public function getElementList($real_parent, $pageName) {
 		if ($real_parent == null) 
		{
 			$out = "<li class='text' id='4'>"
						."<span>Folder-1</span>"
						."<ul class='ajax'>"
							."<li id='4'>{url:manageStructure.php?action=getElementList&real_parent=4}</li>"
						."</ul>"
					."</li>"
					."<li class='text' id='12'>"
						."<span>Folder-2</span>"
						."<ul class='ajax'>"
							."<li id='12'>{url:manageStructure.php?action=getElementList&real_parent=12}</li>"
						."</ul>"
					."</li>"
					."<li class='text' id='13'>"
						."<span>Folder-3</span>"
							."<ul class='ajax'>
								<li id='13'>{url:manageStructure.php?action=getElementList&real_parent=13}</li>"
							."</ul>"
						."</li>";
 		}
		else {
			if ($real_parent == 4) {
				$index = 20;
			}
			else if($real_parent = 12) {
				$index = 30;
			}
			else if($real_parent = 13) {
				$index = 40;
			}
	 		$out = "<li class='text' id='". $index++ ."'><span>file-1</span></li><li class='text' id='". $index++ ."'>".
					"<span>file-2</span></li><li class='text' id='". $index++ ."'><span>file-3</span></li>";
		}
		return $out; 		
 	}
 	
 
 	
 	public function updateElementName($name, $elementId, $real_parent) {
 		$out = '({"elementName":"'.$name.'", "elementId":"'.$elementId.'"})';		
		return $out;
 	} 	
 	
 
 
 	public function deleteElement($elementId, &$index = 0, $real_parent){
 		$out = SUCCESS;			
		return $out; 		
 	} 	
 
 
 
 	public function changeOrder($elementId, $oldreal_parent, $destreal_parent, $destPosition){
 		$out = '({"oldElementId":"'.$elementId.'", "elementId":"'. $elementId .'"})';	
		return $out;  		
 	}	
	
	public function getRootId(){
		return 0;
	}
}

?>