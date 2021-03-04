<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">  
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">  
<head>  
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />  
    <title>yensdesign.com - Validate Forms using PHP and jQuery</title>  
    <link rel="stylesheet" href="css/general.css" type="text/css" media="screen" />  
</head>  
<body>  
    <a id="logo" title="Go to yensdesign.com!" href="http://www.yensdesign.com"><img src="css/images/logo.jpg" alt="yensdesign.com" /></a>  
    <div id="container">  
        <h1>Registration process</h1>  
            <div id="error">  
                <ul>  
                    <li><strong>Invalid Name:</strong> We want names with more than 3 letters!</li>  
                    <li><strong>Invalid E-mail:</strong> Stop cowboy! Type a valid e-mail please :P</li>  
                    <li><strong>Passwords are invalid:</strong> Passwords doesn't match or are invalid!</li>  
                    <li><strong>Ivalid message:</strong> Type a message with at least with 10 letters</li>  
                </ul>  
            </div>  
            <div id="error" class="valid">  
                <ul>  
                    <li><strong>Congratulations!</strong> All fields are OK ;)</li>  
                </ul>  
            </div>  
  
        <form method="post" id="customForm" action="">  
            <div>  
                <label for="name">Name</label>  
                <input id="name" name="name" type="text" />  
                <span id="nameInfo">What's your name?</span>  
            </div>  
            <div>  
                <label for="email">E-mail</label>  
                <input id="email" name="email" type="text" />  
                <span id="emailInfo">Valid E-mail please, you will need it to log in!</span>  
            </div>  
            <div>  
                <label for="pass1">Password</label>  
                <input id="pass1" name="pass1" type="password" />  
                <span id="pass1Info">At least 5 characters: letters, numbers and '_'</span>  
            </div>  
            <div>  
                <label for="pass2">Confirm Password</label>  
                <input id="pass2" name="pass2" type="password" />  
                <span id="pass2Info">Confirm password</span>  
            </div>  
            <div>  
                <label for="message">Message</label>  
                <textarea id="message" name="message" cols="" rows=""></textarea>  
            </div>  
            <div>  
                <input id="send" name="send" type="submit" value="Send" />  
            </div>  
        </form>  
    </div>  
    <script type="text/javascript" src="jquery.js"></script>  
    <script type="text/javascript" src="validation.js"></script>  
</body>  
</html> 