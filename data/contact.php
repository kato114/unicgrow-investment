<!-- Banner Area Starts -->
<section class="banner-area">
	<div class="banner-overlay">
		<div class="banner-text text-center">
			<div class="container">
				<!-- Section Title Starts -->
				<div class="row text-center">
					<div class="col-xs-12">
						<!-- Title Starts -->
						<h2 class="title-head">Get in <span>touch</span></h2>
						<!-- Title Ends -->
						<hr>
						<!-- Breadcrumb Starts -->
						<ul class="breadcrumb">
							<li><a href="index.php"> home</a></li>
							<li>contact</li>
						</ul>
						<!-- Breadcrumb Ends -->
					</div>
				</div>
				<!-- Section Title Ends -->
			</div>
		</div>
	</div>
</section>
<!-- Banner Area Ends -->
<!-- Contact Section Starts -->
<section class="contact">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-md-8 contact-form">
				<h3 class="col-xs-12">feel free to drop us a message</h3>
				<p class="col-xs-12">Need to speak to us? Do you have any queries or suggestions? Please contact us about all enquiries including membership and volunteer work using the form below.</p>
					<?php
					if(isset($_SESSION['success_msg']))
					{
						echo $_SESSION['success_msg'];
						unset($_SESSION['success_msg']);
					}
					if(isset($_POST['submit']))
						{
								$name = $_POST['name'];
								$email = $_POST['email'];
								$subject = $_POST['subject'];
								$mobile_no = $_POST['mobile_no'];
								$message = $_POST['message'];
								//$message = $_POST['message'];  
								
							$mail_message = 'Name : '.$name.' <br>E-Mail : '.$email.' <br>Subject : '.$subject.' <br>Phone No : '.$mobile_no.' <br>Message : '.$message; 
							
							$title = 'Feedback Information';
							$to='support@unicgrow.com';
							// Always set content-type when sending HTML email
							$headers = "MIME-Version: 1.0" . "\r\n";
							$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
							// More headers
							$headers .= "From: <$email>" . "\r\n";
							mail($to,$title,$mail_message,$headers);
							
						//$_SESSION['success_msg'] =  "<span style=\"color:red;\"></spna>";?>
						<script>alert("Thanks for Contacting us Our team will contact you shortly"); window.location = "index.php?page=contact";</script>
					<?php	}	
					  //$_SESSION['sess_feedbackSubmit'] = "FFT".rand(111111 , 999999); 
				?> 
				<!-- Contact Form Starts -->
				<form class="form-contact" method="post" action="php/process-form.php">
					<!-- Input Field Starts -->
					<div class="form-group col-md-6">
						<input name="name" class="form-control" type="text" placeholder="Enter Name" required="">
					</div>
					<!-- Input Field Ends -->
					<!-- Input Field Starts -->
					<div class="form-group col-md-6">
						<input name="email" class="form-control required email" type="email" placeholder="Enter Email">
					</div>
					<!-- Input Field Ends -->
					<!-- Input Field Starts -->
					<div class="form-group col-md-6">
						<input name="subject" class="form-control required" type="text" placeholder="Enter Subject">
					</div>
					<!-- Input Field Ends -->
					<!-- Input Field Starts -->
					<div class="form-group col-md-6">
						 <input name="mobile_no" class="form-control" type="number" placeholder="Enter Phone">
					</div>
					<!-- Input Field Ends -->
					<!-- Input Field Starts -->
					<div class="form-group col-xs-12">
						 <textarea name="message" class="form-control required" rows="5" placeholder="Enter Message"></textarea>
					</div>
					<!-- Input Field Ends -->
					<!-- Submit Form Button Starts -->
					<div class="form-group col-xs-12 col-sm-4">
					<input type="submit" value="Send your message" name="submit" class="btn btn-primary btn-contact" >
					</div>
					<!-- Submit Form Button Ends -->
					<!-- Form Submit Message Starts -->
					<div class="col-xs-12 text-center output_message_holder d-none">
						<p class="output_message"></p>
					</div>
					 <!-- Form Submit Message Ends -->
				</form>
				<!-- Contact Form Ends -->
			</div>
			<!-- Contact Widget Starts -->
			<div class="col-xs-12 col-md-4">
				<div class="widget">
					<div class="contact-page-info">
						<!-- Contact Info Box Starts -->
						<div class="contact-info-box">
							<i class="fa fa-home big-icon"></i>
							<div class="contact-info-box-content">
								<h4>Address</h4>
								<p><span style="color:#f6bb42">Lundsgade 15, 1680 Copenhagen Denmark</span></p>
							</div>
						</div>
						<!-- Contact Info Box Ends -->
						<!-- Contact Info Box Starts -->
						<div class="contact-info-box">
							<i class="fa fa-phone big-icon"></i>
							<div class="contact-info-box-content">
								<h4>Phone Numbers</h4>
								<p><a href="tel:+(012) 345 6789" style="color:#f6bb42"> +(45) 924 59 796</a></p>
							</div>
						</div>
						<!-- Contact Info Box Ends -->
						<!-- Contact Info Box Starts -->
						<div class="contact-info-box">
							<i class="fa fa-envelope big-icon"></i>
							<div class="contact-info-box-content">
								<h4>Email Address</h4>

								<p><a href="mailto:support@unicgorw.com" style="color:#f6bb42">support@unicgrow.com</a></p>
							</div>
						</div>
						<!-- Contact Info Box Ends -->
						<!-- Social Media Icons Starts -->
						<div class="contact-info-box">
							<i class="fa fa-share-alt big-icon"></i>
							<div class="contact-info-box-content">
								<h4>Social Profiles</h4>
								<div class="social-contact">
									<ul>
										<li class="facebook"><a href="#" target="_blank"><i class="fa fa-facebook"></i></a></li>
										<li class="twitter"><a href="#" target="_blank"><i class="fa fa-twitter"></i></a></li>
										<li class="google-plus"><a href="#" target="_blank"><i class="fa fa-google-plus"></i></a></li>
									</ul>
								</div>
							</div>
						</div>
						<!-- Social Media Icons Starts -->
					</div>
				</div>
			</div>
			<!-- Contact Widget Ends -->
		</div>
	</div>
</section>
<!-- Contact Section Ends -->