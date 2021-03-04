<?php
/**************************************
     File Name: ITreeManager.php
     Begin:  Sunday, April, 12, 2009, 11:09 AM
     Author: Ozan Koroglu
 			 Ahmet Oguz Mermerkaya 	
     Email:  koroglu.ozan@gmail.com
     		 ahmetmermerkaya@hotmail.com
 ***************************************/ 
 
 interface ITreeManager 
 { 	
 	/**
 	*  Inserts an element 
 	*  @params
 	*  		$name : name of the element to be inserted.
 	*  		$real_parent(owner element) : new element will be inserted under the owner element
 	*  		$slave : determine whether element will be folder or file. 
 	*			If this param is equal to 1, it means this element will be a file
 	*
 	*  @returns
 	*		String, if string equal to FAILED macro, it means operation failed.
 	*/
 	public function insertElement($name, $real_parent, $slave);
 	
 	
 	
 	/**
 	*  Gets element list under the owner element
 	*  @params
 	*  		$real_parent(owner element) : get the element list under this element	
 	*       $pageName: url of the page used to load elements under a folder 
 	*  @returns
 	*  		String, if string equal to FAILED macro, it means operation failed.
 	*/
 	public function getElementList($real_parent, $pageName);
 	
 
 	
 	/**
 	* Updates an element name
 	* @params
 	*		$name: new name of the element
 	*		$elementId: unique Id of the element
 	*
 	* @returns
 	*		String, if string equal to FAILED macro, it means operation failed.				
 	*/
 	public function updateElementName($name, $elementId, $real_parent);
 	
 	
 	
 	/**
 	* deletes an element, if element is a folder then it deletes all sub-elements 
 	*	under that folder recursively
 	* 
 	* @params		
 	*		$elementId: unique Id of the element
 	*		$index: index of sub-element recursively
 	*
 	* @returns
 	*		String, if string equal to FAILED macro, it means operation failed.	
 	*		if equal to SUCCESS macro, operation is completed succesfully			
 	*/
 	public function deleteElement($elementId, &$index = 0, $real_parent);
 	
 	
 	
 	/**
 	* changes the position and owner element of an element
 	* 
 	* @params
 	*    	$elementId:  unique Id of the element
 	*       $destreal_parent(destination owner element): unique Id of the new owner(parent) of the element
 	*		$destPosition(destination position): destination position order of the element
 	* 
 	* @returns
 	*		String, if string equal to FAILED macro, it means operation failed.	
 	*		if equal to SUCCESS macro, operation is completed succesfully
 	*	
 	*/
 	public function changeOrder($elementId, $oldreal_parent, $destreal_parent, $destPosition);
	
	
	/**
	 * returns the root id
	 * @return 
	 */
	public function getRootId();
 } 
 ?>

