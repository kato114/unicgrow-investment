<?php
/**************************************
     File Name: DemoTreeManager.php
     Begin:  Wednesday, April, 15, 2009, 21:49 
     Author: Ozan Koroglu
 			 Ahmet Oguz Mermerkaya 	
     Email:  koroglu.ozan@gmail.com
     		 ahmetmermerkaya@hotmail.com
 ***************************************/ 

require_once("ITreeManager.php");

class FileTreeManager implements ITreeManager 
{
	private $fileRoot;
	
	const FOLDER_SEPARATOR = "FOLDER_SEPARATOR";
	
	const DEFAULT_FOLDER_SEPARATOR = "/";
	
	public function __construct($fileRoot) {
		if (is_dir($fileRoot)) 
		{
			$this->fileRoot = $fileRoot;
			//if ($this->fileRoot[strlen($this->fileRoot) - 1] != '/' ) {
			//	$this->fileRoot .= '/';
			//}
			
		}
		else {
			die($fileRoot .' is not a directory ');
		}
		
	}
	
	public function insertElement($name, $ownerEl, $slave) 
	{		
		$realOwnerEl = str_replace(self::FOLDER_SEPARATOR, self::DEFAULT_FOLDER_SEPARATOR, $ownerEl);
		$fullPath = $this->getFullPath($realOwnerEl. self::DEFAULT_FOLDER_SEPARATOR . $name);
		
		$out = FAILED;
		if (file_exists($fullPath) === true) {
			$out = FAILED_FILE_WITH_SAME_NAME_EXIST;
		}
		else {
			if ($slave == 1) {
				if (touch($fullPath) === true) {							
					$out = '({ "elementId":"'. $ownerEl . self::FOLDER_SEPARATOR . $name . '", "elementName":"'.$name.'", "slave":"'.$slave.'"})';
				}			
			}
			else{
				if (mkdir($fullPath, 0755)) {
					$out = '({ "elementId":"'. $ownerEl . self::FOLDER_SEPARATOR . $name .'", "elementName":"'.$name.'", "slave":"'.$slave.'"})';
				}
			}
		}
		
		return $out;			
	}
	
	private function getFullPath($relativePath) 
	{
		return $this->fileRoot . self::DEFAULT_FOLDER_SEPARATOR . $relativePath;
	}
	
	public function getElementList($ownerEl, $pageName) 
	{
		$realOwnerEl = str_replace(self::FOLDER_SEPARATOR, self::DEFAULT_FOLDER_SEPARATOR, $ownerEl);
		$fullPath = $this->getFullPath($realOwnerEl);
		$str = null;
		
		if (is_dir($fullPath) && $handle = opendir($fullPath))
		{
			$fileStr = null;
	    	/* This is the correct way to loop over the directory. */
		   	while (false !== ($file = readdir($handle))) 
			{
				if ($file == '.' || $file == '..' || $file == 'Thumbs.db'){
					continue;
				}
				$supp = NULL;				 
				if (is_dir($fullPath . "/" . $file)) {
						
					$supp = "<ul class='ajax'>"
								."<li id='". $ownerEl. self::FOLDER_SEPARATOR . $file ."'>{url:".$pageName."?action=getElementList&ownerEl=". $ownerEl. self::FOLDER_SEPARATOR . $file ."}</li>"
							."</ul>";
				}
	
				$fileStr[] .= "<li class='text' id='". $ownerEl. self::FOLDER_SEPARATOR . $file."'>"
								."<span>". $file ."</span>"
									. $supp
								."</li>";		       			
			}

    		closedir($handle);
			if ($fileStr != null) {
				sort($fileStr);
				$str = implode($fileStr);
			}
		}
			
 		return $str; 		
 	}
 	
 
 	
 	public function updateElementName($name, $elementId,$ownerEl) 
	{
		$ownerEl = str_replace(self::FOLDER_SEPARATOR, self::DEFAULT_FOLDER_SEPARATOR, $ownerEl);
		
		$newElementId = $ownerEl . self::DEFAULT_FOLDER_SEPARATOR . $name;
		
		$elementId = str_replace(self::FOLDER_SEPARATOR, self::DEFAULT_FOLDER_SEPARATOR, $elementId);
		$realElementId = $ownerEl 
						. self::DEFAULT_FOLDER_SEPARATOR 
						. substr($elementId, strrpos($elementId, "/"), strlen($elementId));
		
		$fullPath = $this->getFullPath($realElementId);
		//$newElementId = substr($realElementId, 0,  strrpos($realElementId, "/")). "/" . $name;
		
		$newFullPath = $this->getFullPath($newElementId);
		
		$out = FAILED;
		if (file_exists($newFullPath) === true && dirname($fullPath) != dirname($newFullPath) ) {
			$out = FAILED_FILE_WITH_SAME_NAME_EXIST;
		}
		else if (rename($fullPath, $newFullPath) == true) {
			$newRealElementId = str_replace(self::DEFAULT_FOLDER_SEPARATOR, self::FOLDER_SEPARATOR, $newElementId);
			$out = '({"elementName":"'.$name.'", "elementId":"'. $newRealElementId .'"})';
		}
 				
		return $out;
 	} 	
 	
 
 
 	public function deleteElement($elementId, &$index = 0, $ownerEl)
	{
		
		$elementId = str_replace(self::FOLDER_SEPARATOR, self::DEFAULT_FOLDER_SEPARATOR, $elementId);
		$elementId = str_replace(self::FOLDER_SEPARATOR, self::DEFAULT_FOLDER_SEPARATOR, $ownerEl)
					. self::DEFAULT_FOLDER_SEPARATOR 
					. substr($elementId, strrpos($elementId, "/"), strlen($elementId));
 		$fullPath = $this->getFullPath($elementId);
		
		$out = FAILED;
		if (is_dir($fullPath) && $this->delete_recursive_dirs($fullPath)) {
			$out = SUCCESS;
		}
		else if (unlink($fullPath)) {
			$out = SUCCESS;
		}		
		
		return $out;
 	} 	
 
 
 
 	public function changeOrder($elementId, $oldOwnerEl, $destOwnerEl, $destPosition)
	{ 		
		$oldOwnerEl = str_replace(self::FOLDER_SEPARATOR, self::DEFAULT_FOLDER_SEPARATOR, $oldOwnerEl);
		$elementId = str_replace(self::FOLDER_SEPARATOR, self::DEFAULT_FOLDER_SEPARATOR, $elementId);
		
		$realElementId = $oldOwnerEl . self::DEFAULT_FOLDER_SEPARATOR 
						 . substr($elementId, strrpos($elementId, "/"), strlen($elementId));
		
		$fullPath = $this->getFullPath($realElementId);		
		$elementName = substr($realElementId, strrpos($realElementId, "/")+1); // plus 1 not to get / character
		$realDestOwnerEl = str_replace(self::FOLDER_SEPARATOR, self::DEFAULT_FOLDER_SEPARATOR, $destOwnerEl);
		
		$newFullPath = $this->getFullPath($realDestOwnerEl . "/". $elementName);
		
		$newElementId = $destOwnerEl . self::FOLDER_SEPARATOR . $elementName;
		$out = FAILED;

		if (file_exists($newFullPath) === true && dirname($fullPath) != dirname($newFullPath)) {
			$out = FAILED_FILE_WITH_SAME_NAME_EXIST;
		}
		else if (rename($fullPath, $newFullPath) == true) {
			$out = '({"oldElementId":"'. str_replace(self::DEFAULT_FOLDER_SEPARATOR, self::FOLDER_SEPARATOR,  $elementId)
					.'", "elementId":"'. $newElementId .'"})';;
		}		
						
		return $out;  		
 	}
	
	public function getRootId(){
		return self::FOLDER_SEPARATOR;
	}
	
	private function delete_recursive_dirs($dirname) 
	{ 
	   // recursive function to delete 
	  // all subdirectories and contents: 
		if(is_dir($dirname)) {
			$dir_handle=opendir($dirname);
		}
	  	while($file=readdir($dir_handle)) 
	  	{ 
	  	  if($file!="." && $file!="..") 
	  	  { 
	  	    if(!is_dir($dirname."/".$file)){ 
				unlink ($dirname."/".$file); 
			}
	    	else {
	    		$this->delete_recursive_dirs($dirname."/".$file); 
			}
	      } 
	  	} 
	  	closedir($dir_handle); 
	  	rmdir($dirname); 
	  	return true; 
	}
	
}

?>