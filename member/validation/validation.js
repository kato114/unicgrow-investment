/***************************/
//@Author: Adrian "yEnS" Mato Gondelle & Ivan Guardado Castro
//@website: www.yensdesign.com
//@email: yensamg@gmail.com
//@license: Feel free to use it, but keep this credits please!					
/***************************/

$(document).ready(function(){
	//global vars
	var form = $("#registrarionForm");
	var name = $("#name");
	var nameInfo = $("#nameInfo");
	var l_name = $("#l_name");
	var l_nameInfo = $("#l_nameInfo");
	//var date = $("#date");
	//var dateInfo = $("#dateInfo");
	//var alerts = $("#alerts");
	//var alertsInfo = $("#alertsInfo");
	//var liberty = $("#liberty");
	//var libertyInfo = $("#libertyInfo");
	
	//var city = $("#city");
	//var cityInfo = $("#cityInfo");
	var username = $("#username");
	var usernameInfo = $("#usernameInfo");
	var phone = $("#phone");
	var phoneInfo = $("#phoneInfo");
	//var country = $("#country");
	//var countryInfo = $("#countryInfo");
	//var provience = $("#provience");
	//var provienceInfo = $("#provienceInfo");
	
	var email = $("#email");
	var emailInfo = $("#emailInfo");
	var pass1 = $("#pass1");
	var pass1Info = $("#pass1Info");
	var pass2 = $("#pass2");
	var pass2Info = $("#pass2Info");
	var message = $("#message");
	var messageInfo = $("#messageInfo");
	
	//On blur
	name.blur(validateName);
	l_name.blur(validateLname);
	//date.blur(validateDate);
	//adderss.blur(validateAdderss);
	
	//provience.blur(validateProvience);
	username.blur(validateUsername);
	//city.blur(validateCity);
	//country.blur(validateCountry);
	phone.blur(validatePhone);
	message.blur(validateMessage);
	
	//alerts.blur(validateAlerts);
	//liberty.blur(validateLiberty);
	email.blur(validateEmail);
	pass1.blur(validatePass1);
	pass2.blur(validatePass2);
	//On key press
	//name.keyup(validateName);
	//l_name.keyup(validateLname);
	//date.keyup(validateDate);
	//adderss.keyup(validateAdderss);
	
	//city.keyup(validateCity);
	phone.keyup(validatePhone);
	//country.keyup(validateCountry);
	username.keyup(validateUsername);
	//provience.keyup(validateProvience);
	
	email.keyup(validateEmail);
	pass1.keyup(validatePass1);
	pass2.keyup(validatePass2);
	message.keyup(validateMessage);
	//On Submitting
	form.submit(function(){
		if(validateEmail() & validatePass1() & validatePass2() & validateUsername() & validatePhone() & validateMessage() & validateName() & validateLname() )
			return true
		else
			return false;   
	});
	
	//validation functions
	function validateEmail(){
		//testing regular expression
		var a = $("#email").val();
		var filter = /^[a-zA-Z0-9]+[a-zA-Z0-9_.-]+[a-zA-Z0-9_-]+@[a-zA-Z0-9]+[a-zA-Z0-9.-]+[a-zA-Z0-9]+.[a-z]{2,4}$/;
		//if it's valid email
		if(filter.test(a)){
			email.removeClass("error");
			emailInfo.text("Your E-mail Address is valid!");
			emailInfo.removeClass("error");
			return true;
		}
		//if it's NOT valid
		else{
			email.addClass("error");
			emailInfo.text("Enter your E-mail Address");
			emailInfo.addClass("error");
			return false;
		}
	}
	function validateAlerts(){
		//testing regular expression
		var a = $("#alerts").val();
		var filter = /^[a-zA-Z0-9]+[a-zA-Z0-9_.-]+[a-zA-Z0-9_-]+@[a-zA-Z0-9]+[a-zA-Z0-9.-]+[a-zA-Z0-9]+.[a-z]{2,4}$/;
		//if it's valid email
		if(filter.test(a)){
			alerts.removeClass("error");
			alertsInfo.text("Valid E-mail please");
			alertsInfo.removeClass("error");
			return true;
		}
		//if it's NOT valid
		else{
			alerts.addClass("error");
			alertsInfo.text("Type a valid E-mail Address");
			alertsInfo.addClass("error");
			return false;
		}
	}
	function validateLiberty(){
		//testing regular expression
		var a = $("#liberty").val();
		var filter = /^[a-zA-Z0-9]+[a-zA-Z0-9_.-]+[a-zA-Z0-9_-]+@[a-zA-Z0-9]+[a-zA-Z0-9.-]+[a-zA-Z0-9]+.[a-z]{2,4}$/;
		//if it's valid email
		if(filter.test(a)){
			liberty.removeClass("error");
			libertyInfo.text("Valid E-mail please");
			libertyInfo.removeClass("error");
			return true;
		}
		//if it's NOT valid
		else{
			liberty.addClass("error");
			libertyInfo.text("Please Type a valid e-mail please");
			libertyInfo.addClass("error");
			return false;
		}
	}
	function validateName(){
		//if it's NOT valid
		if(name.val().length < 4){
			name.addClass("error");
			nameInfo.text("Enter Your First Name!");
			nameInfo.addClass("error");
			return false;
		}
		//if it's valid
		else{
			name.removeClass("error");
			nameInfo.text("Your First Name is Correct!");
			nameInfo.removeClass("error");
			return true;
		}
	}
	function validateLname(){
		//if it's NOT valid
		if(l_name.val().length < 4){
			l_name.addClass("error");
			l_nameInfo.text("Enter Your Last Name!");
			l_nameInfo.addClass("error");
			return false;
		}
		//if it's valid
		else{
			l_name.removeClass("error");
			l_nameInfo.text("Your Last Name is Correct!");
			l_nameInfo.removeClass("error");
			return true;
		}
	}
	function validateDate(){
		//if it's NOT valid
		if(date.val().length < 1){
			date.addClass("error");
			dateInfo.text("Please Enter Your Date Of Birth!");
			dateInfo.addClass("error");
			return false;
		}
		//if it's valid
		else{
			date.removeClass("error");
			dateInfo.text("What's your Date Of Birth?");
			dateInfo.removeClass("error");
			return true;
		}
	}
	
	
	function validateCity(){
		//if it's NOT valid
		if(city.val().length < 5){
			city.addClass("error");
			cityInfo.text("Please Enter Your City!");
			cityInfo.addClass("error");
			return false;
		}
		//if it's valid
		else{
			city.removeClass("error");
			cityInfo.text("What's your City?");
			cityInfo.removeClass("error");
			return true;
		}
	}
	function validateCountry(){
		//if it's NOT valid
		if(country.val().length < 5){
			country.addClass("error");
			countryInfo.text("Please Enter Your Date Of Birth!");
			countryInfo.addClass("error");
			return false;
		}
		//if it's valid
		else{
			country.removeClass("error");
			countryInfo.text("What's your Date Of Birth?");
			countryInfo.removeClass("error");
			return true;
		}
	}
	function validateProvience(){
		//if it's NOT valid
		if(provience.val().length < 5){
			provience.addClass("error");
			provienceInfo.text("Please Enter provience!");
			provienceInfo.addClass("error");
			return false;
		}
		//if it's valid
		else{
			provience.removeClass("error");
			provienceInfo.text("What's your provience?");
			provienceInfo.removeClass("error");
			return true;
		}
	}
	function validatePhone(){
		//if it's NOT valid
		if(phone.val().length < 5){
			phone.addClass("error");
			phoneInfo.text("Enter Your phone No!");
			phoneInfo.addClass("error");
			return false;
		}
		//if it's valid
		else{
			phone.removeClass("error");
			phoneInfo.text("Phone No.is Correct!");
			phoneInfo.removeClass("error");
			return true;
		}
	}function validateUsername(){
		//if it's NOT valid
		if(username.val().length < 5){
			username.addClass("error");
			usernameInfo.text("Please Enter At least 5 characters !");
			usernameInfo.addClass("error");
			return false;
		}
		//if it's valid
		else{
			username.removeClass("error");
			usernameInfo.text("Username Is Correct !");
			usernameInfo.removeClass("error");
			return true;
		}
	}
	function validatePass1(){
		var a = $("#password1");
		var b = $("#password2");

		//it's NOT valid
		if(pass1.val().length <5){
			pass1.addClass("error");
			pass1Info.text("Please Enter At least 5 characters");
			pass1Info.addClass("error");
			return false;
		}
		//it's valid
		else{			
			pass1.removeClass("error");
			pass1Info.text("Password is Correct !");
			pass1Info.removeClass("error");
			validatePass2();
			return true;
		}
	}
	function validatePass2(){
		var a = $("#password1");
		var b = $("#password2");
		//are NOT valid
		if( pass1.val() != pass2.val() ){
			pass2.addClass("error");
			pass2Info.text("Passwords doesn't match!");
			pass2Info.addClass("error");
			return false;
		}
		//are valid
		else{
			pass2.removeClass("error");
			pass2Info.text("Confirm password matched !");
			pass2Info.removeClass("error");
			return true;
		}
	}
	function validateMessage(){
		//it's NOT valid
		if(message.val().length < 1){
			message.addClass("error");
			messageInfo.text("Enter Your Adderss!");
			messageInfo.addClass("error");
			return false;
		}
		//it's valid
		else{			
			message.removeClass("error");
			messageInfo.text("Address is Correct!");
			messageInfo.removeClass("error");
			return true;
		}
	}
});