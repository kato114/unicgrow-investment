<?php

/**
 * getElementList
 * @param $db
 * @param $sql
 * @param $pageName
 * @return unknown_type
 */
//TODO: delete this function
function getElementList(&$db, $sql,$pageName)
{
	$str = false;
	$result = $db->query($sql);
	if ( $result !== false )
	{
		if ($db->numRows($result) > 0) {
			$str = NULL;
		}
		else {
			$str = NULL;
			//$str = "<li></li>";
		}
		while ($row = $db->fetchObject($result))
		{
			$supp = NULL;
			if ($row->slave == 0){
				$supp = "<ul class='ajax'>"
								."<li id='".$row->id_user."'>{url:".$pageName."?action=getElementList&real_parent=".$row->id_user."}</li>"
						."</ul>";
			}

			$str .= "<li class='text' id='".$row->id_user."'>"
						."<span>".$row->f_name."</span>"
							. $supp
					."</li>";				
		}
	}
	return $str;
}
/**
 * 
 * @param $db
 * @param $Id
 * @return unknown_type
 */
//TODO: delete this function
function deleteData($db, $Id, &$i = 0) 
{
	// to check that whether child exists
	$sql = sprintf('SELECT
				 		id_user, position, real_parent 
					FROM users
					WHERE 
						real_parent = %d ',
					$Id);
	$row = NULL;
	$i++;		
	echo $i;
	if ($result = $db->query($sql))
	{		
		while ($row = $db->fetchObject($result)) 
		{	  
			// if element type is not slave, 
			// there can be childs belonging to that master  	
			/*if ($row->slave == "0") 
			{
				// recursive operation, to reach the deepest element
				deleteData($db, $row->Id, $i);				
			}	*/		
		}
	}
	$i--;
	
	// only update the elements' position on the same level of our first element
	if ($i == 0) 
	{
		$sql = sprintf('SELECT 
							position, real_parent
						FROM users
						WHERE
							id_user = %d',
						$Id);

					
		if ($result = $db->query($sql)) 
		{			
			if ($row = $db->fetchObject($result)) 
			{
				$sql = sprintf('UPDATE users
								SET 
									position = position - 1
								WHERE 
									real_parent = %d
									AND
									position > %d',
								$row->real_parent, $row->position);
				$db->query($sql);
			}	
		}			
	}
		
	// start to delete it from bottom to top
	$sql = sprintf('DELETE FROM users
	        		WHERE 
			        	real_parent = %d 
			        	OR
			        	id_user = %d ',
					$Id, $Id);
	
	if (!$db->query($sql)) {
		return false;
	}	
	return true;
}
/**
 * 
 * @param $string
 * @return unknown_type
 */
function checkVariable($string)
{
	return str_replace ( array ( '&', '"', "'", '<', '>' ),
	array ( '&amp;' , '&quot;', '&apos;' , '&lt;' , '&gt;' ), $string );
}


?>