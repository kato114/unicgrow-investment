    <?php  
        function validateName($name){  
            //if it's NOT valid  
            if(strlen($name) < 1)  
                return false;  
            //if it's valid  
            else  
                return true;  
        }  
		function validateLname($l_name){  
            //if it's NOT valid  
            if(strlen($l_name) < 1)  
                return false;  
            //if it's valid  
            else  
                return true;  
        } 
		function validateDate($date){  
            //if it's NOT valid  
            if(strlen($date) <1)  
                return false;  
            //if it's valid  
            else  
                return true;  
        } 
		function validateAdderss($address){  
            //if it's NOT valid  
            if(strlen($address) < 1)  
                return false;  
            //if it's valid  
            else  
                return true;    
        } 
		function validateCity($city){  
            //if it's NOT valid  
            if(strlen($city) < 1)  
                return false;  
            //if it's valid  
            else  
                return true;    
        } 
		function validateCountry($country){  
            //if it's NOT valid  
            if(strlen($country) < 1)  
                return false;  
            //if it's valid  
            else  
                return true;    
        } 
		function validateProvience($provience){  
            //if it's NOT valid  
            if(strlen($provience) < 5)  
                return false;  
            //if it's valid  
            else  
                return true;    
        } 
		function validatePhone($phone){  
            //if it's NOT valid  
            if(strlen($phone) < 5)  
                return false;  
            //if it's valid  
            else  
                return true;    
        } function validateUsername($username){  
            //if it's NOT valid  
            if(strlen($username) < 1)  
                return false;  
            //if it's valid  
            else  
                return true;    
        } 
		
        function validateEmail($email){  
            return ereg("^[a-zA-Z0-9]+[a-zA-Z0-9._-]+@[a-zA-Z0-9]+[a-zA-Z0-9.-]+[a-zA-Z0-9]+.[a-z]{2,4}$", $email);  
        }  
        function validatePasswords($pass1, $pass2) 
		{  
            //if DOESN'T MATCH  
            if(strlen($pass1) < 1)  
                return false;  
            else
			{
				if(strlen($pass2) < 1)  
               		return false;  
				else
				{
					$res = strcmp($pass1,$pass2); 
					 if($res == 0)
						return true;
					else	 
					//if are valid  
					return false;  
				}
			}		
        }  
        function validateMessage($message){  
            //if it's NOT valid  
            if(strlen($message) < 1)  
                return false;  
            //if it's valid  
            else  
                return true;  
        } 
		
		function validateCaptcha($pass1, $pass2) 
		{  
            //if DOESN'T MATCH  
            if(strlen($pass1) < 1)  
                return false;  
            else
			{
				if(strlen($pass2) < 1)  
               		return false;  
				else
				{
					$res = strcmp($pass1,$pass2); 
					 if($res == 0)
						return true;
					else	 
					//if are valid  
					return false;  
				}
			}		
        }  
    ?>  