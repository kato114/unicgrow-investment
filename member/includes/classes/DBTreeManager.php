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
	
 	public function insertElement($name, $real_parent, $slave)
	{
		$real_parent = (int) $real_parent;
		$sql = sprintf('INSERT INTO users(name, position, real_parent)
							SELECT 
								\'%s\', ifnull(max(el.position)+1, 0), %d, %d 
							FROM users el 
							WHERE 
								el.real_parent = %d ',
							$name , $real_parent, $slave, $real_parent);
		$out = FAILED;
		if ($this->db->query($sql) == true) {
				$out = '({ "elementId":"'.$this->db->lastInsertId().'", "elementName":"'.$name.'"})';
		}
		
		return $out; 	
 	}
 	
 	
 	
 	public function getElementList($real_parent, $pageName)
	{
		if ($real_parent == null) {
			$real_parent = $_SESSION['tree_session_id'];
		}
		else {
			$real_parent = (int) $real_parent;
		}
		$sql = sprintf("SELECT 
        					id_user, username, f_name, l_name
        				FROM users
		      			WHERE
		      				real_parent = %d  
		      			ORDER BY
		      				id_user ",
        				$real_parent);
						
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
                    ."<li id='".$row->id_user."'>{url:".$pageName."?action=getElementList&real_parent=".$row->id_user."}</li>"
                    ."</ul>";
                }
        
                $str .= "<li class='text' id='".$row->id_user."'>"
                ."<span>".$row->username."</span>"."<span>&nbsp;&nbsp;(".$row->f_name."12</span>"."<span>&nbsp;&nbsp;".$row->l_name.")</span>".$supp
                ."</li>";
            }
        }
        return $str;				
						
 	
 	}
 	
 	
 	public function updateElementName($name, $elementId, $real_parent)
	{
		$elementId = (int) $elementId;
 		$sql = sprintf('UPDATE users 
							SET 
								name = \'%s\'
					    	WHERE 
					    		id_user = %d ',
        					$name, $elementId);
		$out = FAILED;					
		if ($this->db->query($sql) == true) {
				$out = '({"elementName":"'.$name.'", "elementId":"'.$elementId.'"})';
		}
		
		return $out;
 	}
 	
 	
     public function deleteElement($elementId, &$index = 0, $real_parent)
     {
     	$elementId = (int) $elementId;
         $sql = sprintf('SELECT
     				 		id_user, slave, position, real_parent 
     					FROM users
     					WHERE 
     						real_parent = %d ',
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
                     $this->deleteElement($row->id_user, $index);
                 }
             }
         }
         $index--;
     
         // only update the elements' position on the same level of our first element
         if ($index == 0)
         {
             $sql = sprintf('SELECT 
     							position, real_parent
     						FROM '
             .TREE_TABLE_PREFIX.'_elements
     						WHERE
     							id_user = %d',
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
     									real_parent = %d
     									AND
     									position > %d',
                     					$row->real_parent, $row->position);
                     $this->db->query($sql);
                 }
             }
         }
     
         // start to delete it from bottom to top
         $sql = sprintf('DELETE FROM '
         					.TREE_TABLE_PREFIX.'_elements
     	        		WHERE 
     			        	real_parent = %d 
     			        	OR
     			        	id_user = %d ',  $elementId, $elementId);
     
	 	 $out = FAILED;
         if ($this->db->query($sql) == true)
         {
             $out = SUCCESS;
         }
         return $out;     
     }
 	
 	public function changeOrder($elementId, $oldreal_parent, $destreal_parent, $destPosition)
	{
		$sql = sprintf('SELECT
						 		real_parent, position 
							FROM users 
							WHERE 
								id_user = %d
							LIMIT 1',
							$elementId);
		$out = FAILED;					
		if ($result = $this->db->query($sql))
		{			
				if ($element = $this->db->fetchObject($result))
				{						
					$sql1 = sprintf('UPDATE users 
									 SET 
									 	position = position - 1
									 WHERE  
									 	position > %d
									    AND
									    real_parent = %d ',
									 $element->position, $element->real_parent);
							   
					$sql2 = sprintf('UPDATE users 
									 SET 
									 	position = position + 1
									 WHERE
							 			 position >= %d 
									   	 AND
									   	 real_parent = %d ',
									 $destPosition, $destreal_parent);
							   
					$sql3 = sprintf('UPDATE users 
									 SET 
									 	position = %d , real_parent = %d
									 WHERE 
									 	id_user = %d ',
										$destPosition, $destreal_parent, $elementId);
	
					
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