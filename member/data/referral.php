<div class="row">
	<div class="col-md-12">
		<div class="alert alert-info mb-2">
			<B>Referral Link :</B> 
			<input type="text" value="<?=$refferal_link?>/register.php?ref=<?=$_SESSION['mlmproject_user_refrral']?>" id="myInput" style="width:50%;" readonly="" />
			<button class="btn btn-danger btn-sm" onclick="myFunction()" id="copy_ref_b">Copy</button>
		</div>
	</div>
</div>
<script>
function myFunction() {
	var copyText = document.getElementById("myInput");
	copyText.select();
	document.execCommand("copy");
	document.getElementById("copy_ref_b").innerHTML = "Copied!";
}
</script>