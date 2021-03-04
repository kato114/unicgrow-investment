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
 	public function insertElement($name, $ownerEl, $slave)
	{
 		$insertId = rand(0, 10000);				
		$out =  '({ "elementId":"'.$insertId.'", "elementName":"'.$name.'", "slave":"'.$slave.'"})';
		return $out;
 	}
 	 	
 	
	
 	public function getElementList($ownerEl, $pageName) {
 		if ($ownerEl == null) 
		{
 			$out = "<li class='text' id='4'>"
						."<span>Folder-1</span>"
						."<ul class='ajax'>"
							."<li id='4'>{url:manageStructure.php?action=getElementList&ownerEl=4}</li>"
						."</ul>"
					."</li>"
					."<li class='text' id='12'>"
						."<span>Folder-2</span>"
						."<ul class='ajax'>"
							."<li id='12'>{url:manageStructure.php?action=getElementList&ownerEl=12}</li>"
						."</ul>"
					."</li>"
					."<li class='text' id='13'>"
						."<span>Folder-3</span>"
							."<ul class='ajax'>
								<li id='13'>{url:manageStructure.php?action=getElementList&ownerEl=13}</li>"
							."</ul>"
						."</li>";
 		}
		else {
			if ($ownerEl == 4) {
				$index = 20;
			}
			else if($ownerEl = 12) {
				$index = 30;
			}
			else if($ownerEl = 13) {
				$index = 40;
			}
	 		$out = "<li class='text' id='". $index++ ."'><span>file-1</span></li><li class='text' id='". $index++ ."'>".
					"<span>file-2</span></li><li class='text' id='". $index++ ."'><span>file-3</span></li>";
		}
		return $out; 		
 	}
 	
 
 	
 	public function updateElementName($name, $elementId, $ownerEl) {
 		$out = '({"elementName":"'.$name.'", "elementId":"'.$elementId.'"})';		
		return $out;
 	} 	
 	
 
 
 	public function deleteElement($elementId, &$index = 0, $ownerEl){
 		$out = SUCCESS;			
		return $out; 		
 	} 	
 
 
 
 	public function changeOrder($elementId, $oldOwnerEl, $destOwnerEl, $destPosition){
 		$out = '({"oldElementId":"'.$elementId.'", "elementId":"'. $elementId .'"})';	
		return $out;  		
 	}	
	
	public function getRootId(){
		return 0;
	}
}

?>