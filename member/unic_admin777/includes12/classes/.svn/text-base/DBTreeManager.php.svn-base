<?php
/**************************************
     File Name: DBTreeManager.php
     Begin:  Sunday, April, 12, 2009, 11:36 AM
     Author: Ozan Koroglu
 			 Ahmet Oguz Mermerkaya 	
     Email:  koroglu.ozan@gmail.com
     		 ahmetmermerkaya@hotmail.com
 ***************************************/ 
 
 require_once('ITreeManager.php');
 
 class DBTreeManager implements ITreeManager
 {
 	private $db;
	
    public function __construct($dbc){
    	$this->db = $dbc;
    } 
	
 	public function insertElement($name, $ownerEl, $slave)
	{
		$ownerEl = (int) $ownerEl;
		$sql = sprintf('INSERT INTO ' 
								. TREE_TABLE_PREFIX . '_elements(name, position, ownerEl, slave)
							SELECT 
								\'%s\', ifnull(max(el.position)+1, 0), %d, %d 
							FROM '
								. TREE_TABLE_PREFIX . '_elements el 
							WHERE 
								el.ownerEl = %d ',
							$name , $ownerEl, $slave, $ownerEl);
		$out = FAILED;
		if ($this->db->query($sql) == true) {
				$out = '({ "elementId":"'.$this->db->lastInsertId().'", "elementName":"'.$name.'", "slave":"'.$slave.'"})';
		}
		
		return $out; 	
 	}
 	
 	
 	
 	public function getElementList($ownerEl, $pageName)
	{
		if ($ownerEl == null) {
			$ownerEl = 0;
		}
		else {
			$ownerEl = (int) $ownerEl;
		}
		$sql = sprintf("SELECT 
        					Id, name, slave 
        				FROM " 
        					. TREE_TABLE_PREFIX . "_elements
		      			WHERE
		      				ownerEl = %d  
		      			ORDER BY
		      				position ",
        				$ownerEl);
						
		$str = FAILED;
        $result = $this->db->query($sql);
        if ($result !== false)
        {
        	$str = NULL;
        	/*
            if ($this->db->numRows($result) > 0)
            {
                $str = NULL;
            }
            else
            {
                $str = NULL;
                //$str = "<li></li>";
            }
            */
            while ($row = $this->db->fetchObject($result))
            {
                $supp = NULL;
                if ($row->slave == 0)
                {
                    $supp = "<ul class='ajax'>"
                    ."<li id='".$row->Id."'>{url:".$pageName."?action=getElementList&ownerEl=".$row->Id."}</li>"
                    ."</ul>";
                }
        
                $str .= "<li class='text' id='".$row->Id."'>"
                ."<span>".$row->name."</span>"
                .$supp
                ."</li>";
            }
        }
        return $str;				
						
 	
 	}
 	
 	
 	public function updateElementName($name, $elementId, $ownerEl)
	{
		$elementId = (int) $elementId;
 		$sql = sprintf('UPDATE ' 
        						. TREE_TABLE_PREFIX.'_elements 
							SET 
								name = \'%s\'
					    	WHERE 
					    		Id = %d ',
        					$name, $elementId);
		$out = FAILED;					
		if ($this->db->query($sql) == true) {
				$out = '({"elementName":"'.$name.'", "elementId":"'.$elementId.'"})';
		}
		
		return $out;
 	}
 	
 	
     public function deleteElement($elementId, &$index = 0, $ownerEl)
     {
     	$elementId = (int) $elementId;
         $sql = sprintf('SELECT
     				 		Id, slave, position, ownerEl 
     					FROM '. TREE_TABLE_PREFIX .'_elements
     					WHERE 
     						ownerEl = %d ',
         				$elementId);
         $row = NULL;
         $index++;
         if ($result = $this->db->query($sql))
         {
             while ($row = $this->db->fetchObject($result))
             {
                 // if element type is not slave,
                 // there can be childs belonging to that master
                 if ($row->slave == "0")
                 {
                     // recursive operation, to reach the deepest element
                     $this->deleteElement($row->Id, $index);
                 }
             }
         }
         $index--;
     
         // only update the elements' position on the same level of our first element
         if ($index == 0)
         {
             $sql = sprintf('SELECT 
     							position, ownerEl
     						FROM '
             .TREE_TABLE_PREFIX.'_elements
     						WHERE
     							Id = %d',
            				 $elementId);
     
     
             if ($result = $this->db->query($sql))
             {
                 if ($row = $this->db->fetchObject($result))
                 {
                     $sql = sprintf('UPDATE '
                    				 .TREE_TABLE_PREFIX.'_elements
     								SET 
     									position = position - 1
     								WHERE 
     									ownerEl = %d
     									AND
     									position > %d',
                     					$row->ownerEl, $row->position);
                     $this->db->query($sql);
                 }
             }
         }
     
         // start to delete it from bottom to top
         $sql = sprintf('DELETE FROM '
         					.TREE_TABLE_PREFIX.'_elements
     	        		WHERE 
     			        	ownerEl = %d 
     			        	OR
     			        	Id = %d ',  $elementId, $elementId);
     
	 	 $out = FAILED;
         if ($this->db->query($sql) == true)
         {
             $out = SUCCESS;
         }
         return $out;     
     }
 	
 	public function changeOrder($elementId, $oldOwnerEl, $destOwnerEl, $destPosition)
	{
		$sql = sprintf('SELECT
						 		ownerEl, position 
							FROM '
								. TREE_TABLE_PREFIX . '_elements 
							WHERE 
								Id = %d
							LIMIT 1',
							$elementId);
		$out = FAILED;					
		if ($result = $this->db->query($sql))
		{			
				if ($element = $this->db->fetchObject($result))
				{						
					$sql1 = sprintf('UPDATE '
										 . TREE_TABLE_PREFIX . '_elements 
									 SET 
									 	position = position - 1
									 WHERE  
									 	position > %d
									    AND
									    ownerEl = %d ',
									 $element->position, $element->ownerEl);
							   
					$sql2 = sprintf('UPDATE '
										. TREE_TABLE_PREFIX . '_elements 
									 SET 
									 	position = position + 1
									 WHERE
							 			 position >= %d 
									   	 AND
									   	 ownerEl = %d ',
									 $destPosition, $destOwnerEl);
							   
					$sql3 = sprintf('UPDATE '
										. TREE_TABLE_PREFIX . '_elements 
									 SET 
									 	position = %d , ownerEl = %d
									 WHERE 
									 	Id = %d ',
										$destPosition, $destOwnerEl, $elementId);
	
					
					if ($this->db->query($sql1) && $this->db->query($sql2) && $this->db->query($sql3)) {					
						$out = '({"oldElementId":"'.$elementId.'", "elementId":"'. $elementId .'"})';
					}					
				}
				
		}
		return $out;				
 	}
	
	
	public function getRootId(){
		return 0;
	}
 	
 }
 ?>