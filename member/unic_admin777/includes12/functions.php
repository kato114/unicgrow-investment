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
	//return $str;
}

function checkVariable($string)
{
	return str_replace ( array ( '&', '"', "'", '<', '>' ),
	array ( '&amp;' , '&quot;', '&apos;' , '&lt;' , '&gt;' ), $string );
}


?>