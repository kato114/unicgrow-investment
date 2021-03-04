<?php

function main_menu_name($val)
{
	$sqli = "Select * from menu where menu_file = '$val'";
	$querys = query_execute_sqli($sqli);
	while($rows = mysqli_fetch_array($querys))
	{
		$main_menu = $rows['menu'];
	}
	return $main_menu;
}	
function sub_menu($val)
{
	$sql = "Select * from menu where menu_file = (Select parent_menu from menu where menu_file = '$val') limit 1";
	$query = query_execute_sqli($sql);
	while($row = mysqli_fetch_array($query))
	{
		$sub_menu = $row['menu'];
	}
	if($sub_menu == ''){ $menu = "Dashboard"; }
	else{ $menu = $sub_menu; }
	return $menu;
}

function get_menu_icon($val)
{
	switch($val)
	{
		case '' : 								$icon = 'fa-dashboard';			break;
		case 'welcome' : 						$icon = 'fa-dashboard';			break;
		
		case 'edit-password' : 					$icon = 'fa-lock';				break;
		case 'user-profile' : 					$icon = 'fa-user';				break;
		case 'edit-profile' : 					$icon = 'fa-edit';				break;
		case 'edit_bank_details' : 				$icon = 'fa-edit';				break;
		
		case 'level_report' : 					$icon = 'fa-users';				break;
		
		case 'user-investment' : 				$icon = 'fa-money';				break;
		case 'your_investments' : 				$icon = 'fa-money';				break;
		case 'top_up_history' : 				$icon = 'fa-money';				break;
		
		case 'new-joining-in-network' : 		$icon = 'fa-sitemap';			break;
		case 'tree_view' : 						$icon = 'fa-users';				break;
		case 'direct-members' : 				$icon = 'fa-users';				break;
		case 'network-member-list' : 			$icon = 'fa-sitemap';			break;
		case 'current_matching_status' : 		$icon = 'fa-users';				break;
		case 'member_business' : 				$icon = 'fa-users';				break;
		case 'simple_tree' : 					$icon = 'fa-sitemap';			break;
		
		case 'daily-interest' : 				$icon = 'fa-trophy';			break;
		case 'direct-income' : 					$icon = 'fa-usd';				break;
		case 'binary-income' : 					$icon = 'fa-usd';				break;
		case 'reward_income' : 					$icon = 'fa-usd';				break;
		case 'level_income' : 					$icon = 'fa-usd';				break;
		
		case 'wallet-amount' : 					$icon = 'fa-usd';				break;
		case 'request-fund-transfer' : 			$icon = 'fa-usd';				break;
		case 'transfer-to-member' : 			$icon = 'fa-usd';				break;
		case 'account_history' :				$icon = 'fa-usd';				break;
		case 'exchange_account' : 				$icon = 'fa-usd';				break;
		case 'ledger' : 						$icon = 'fa-usd';				break;
		
		case 'generate_epin' : 					$icon = 'fa-key';				break;
		case 'used-pin' : 						$icon = 'fa-key';				break;
		case 'unused_pin' : 					$icon = 'fa-key';				break;
		case 'my_pin_history' : 				$icon = 'fa-key';				break;
		case 'epin_history' : 					$icon = 'fa-key';				break;
		case 'current_epin_history' : 			$icon = 'fa-key';				break;
		
		case 'my_link' : 						$icon = 'fa-bullhorn';			break;
		case 'add_kyc_docs' : 					$icon = 'fa-file';				break;
		case 'news' : 							$icon = 'fa-bullhorn';			break;
		case 'documents' : 						$icon = 'fa-download';			break;
		case 'support' : 						$icon = 'fa-comment';			break;
		case 'my_ticket' : 						$icon = 'fa-comment';			break;
		case 'logout' : 						$icon = 'fa fa-power-off';		break;
	}
	return $icon;
}
?>