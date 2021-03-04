<?php
/********************************************
*
*	Filename:	manageStructure.php
*	Author:		Ahmet Oguz Mermerkaya
*	E-mail:		ahmetmermerkaya@hotmail.com
*	Begin:		Sunday, July 6, 2008  20:21
*
*********************************************/

if (isset($_REQUEST['action']) && !empty($_REQUEST['action'])) 
{
	$action = $_REQUEST['action'];	
}
else 
{
	die(FAILED);
}
define("IN_PHP", true);

require_once("common.php");

$out = NULL;

switch($action)
{
	case "insertElement":
	{	
		/**
		 * insert new element
		 */	
		if ( ( isset($_POST['name']) === true && $_POST['name'] != NULL )  &&
		     ( isset($_POST['real_parent']) === true && $_POST['real_parent'] != NULL )	   
		   )
		{				
			$real_parent = checkVariable($_POST['real_parent']);
			
			$name = checkVariable($_POST['name']);	 		
		  
			$out = $treeManager->insertElement($name, $real_parent, $slave);						
		}
		else {
			$out = FAILED; 
		}
	}
	break;	
	case  "getElementList":  
	{
		/**
		 * getting element list
		 */
        if( isset($_REQUEST['real_parent']) == true && $_REQUEST['real_parent'] != NULL ) {  	
			$real_parent = checkVariable($_REQUEST['real_parent']); 
		}
		else {
			$real_parent = 0;
		}
  		$out = $treeManager->getElementList($real_parent, $_SERVER['PHP_SELF']);
    }
	break;		
    case "updateElementName":
    {
    	/**
    	 * Changing element name
    	 */
		if (isset($_POST['name']) && !empty($_POST['name']) &&
		    isset($_POST['id_user']) && !empty($_POST['id_user']) &&
		    isset($_POST['real_parent']) && $_POST['real_parent'] != NULL)
		{			
			$name = checkVariable($_POST['name']);
			$id_user = checkVariable($_POST['id_user']); 			
			$real_parent = checkVariable($_POST['real_parent']);
			$out = $treeManager->updateElementName($name, $id_user, $real_parent);
		}                         
		else {
			$out = FAILED;	
		}
    }    
    break;

	case "deleteElement":
	{
		/**
		 * deleting an element and elements under it if exists
		 */
		if (isset($_POST['id_user']) && !empty($_POST['id_user']) &&
		    isset($_POST['real_parent']) && $_POST['real_parent'] != NULL)
		{
        	$id_user =  checkVariable($_POST['id_user']);	 
        	$real_parent = checkVariable($_POST['real_parent']); 			 
        	$index = 0;
			$out = $treeManager->deleteElement($id_user, $index, $real_parent);             
	    }
        else {
			$out = FAILED;	
		}
	}
	break;
	case "changeOrder":
	{		
		/**
		 * Change the order of an element
		 */
		if ((isset($_POST['id_user']) && $_POST['id_user'] != NULL) &&
			(isset($_POST['destreal_parent']) && $_POST['destreal_parent'] != NULL) &&
			(isset($_POST['position']) && $_POST['position'] != NULL) &&
			(isset($_POST['oldreal_parent']) && $_POST['oldreal_parent'] != NULL) 
			)			
		{			
			$oldreal_parent = checkVariable($_POST['oldreal_parent']);
			$id_user = checkVariable($_POST['id_user']);
			$destreal_parent = checkVariable($_POST['destreal_parent']);
			$position = (int) checkVariable($_POST['position']);
		
			$out = $treeManager->changeOrder($id_user, $oldreal_parent, $destreal_parent, $position);
		}			
		else{		
			$out = FAILED;
		}			
	}
	break;		
    default:
    	/**
    	 * if an unsupported action is requested, reply it with FAILED
    	 */
      	$out = FAILED;
	break;
}
echo $out;
