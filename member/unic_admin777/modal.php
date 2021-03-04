<style>
img {
    border-radius: 1px;
    cursor: pointer;
}

.close {
    font-size: 40px;
    font-weight: bold;
}
</style>


<div class="modal inmodal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content animated flipInY">
			<div class="modal-header">
				<span class="close" aria-hidden="true">&times;</span><span class="sr-only">Close</span>
				<h4 class="modal-title">KYC Docs</h4>
			</div>
			<div class="modal-body">
				<img class="modal-contents" id="img01" />
				<div id="caption"></div>
			</div>
		</div>
	</div>
</div>

<script>
var modal = document.getElementById('myModal');

$(document).ready(function(){
	$(".imgss").click(function(){
		var modalImg = document.getElementById("img01");
		var captionText = document.getElementById("caption");
		modal.style.display = "block";
		modalImg.src = this.src;
		captionText.innerHTML = this.alt;
	});
});
var span = document.getElementsByClassName("close")[0];

span.onclick = function() { 
    modal.style.display = "none";
}
</script>