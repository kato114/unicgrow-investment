<style>
img {
    border-radius: 5px;
    cursor: pointer;
    transition: 0.3s;
}

/*img:hover {opacity: 0.7;}*/

/* The Modal (background) */
.modals {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    /*z-index: 1;*/ /* Sit on top */
    padding-top: 100px; /* Location of the box */
    left: 280px;
    top: 50px;
    width: 70%; /* Full width */
    height: 70%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.9); /* Black w/ opacity */
	z-index:999;
}

/* Modal Content (image) */
.modal-contents {
    margin: auto;
    display: block;
}

/* Caption of Modal Image */
#caption {
    margin: auto;
    display: block;
    text-align: center;
    color: #ccc;
    padding: 10px 0;
    height: 150px;
}

/* Add Animation */
.modal-contents, #caption {    
    -webkit-animation-name: zoom;
    -webkit-animation-duration: 0.6s;
    animation-name: zoom;
    animation-duration: 0.6s;
}

@-webkit-keyframes zoom {
    from {-webkit-transform:scale(0)} 
    to {-webkit-transform:scale(1)}
}

@keyframes zoom {
    from {transform:scale(0)} 
    to {transform:scale(1)}
}

/* The Close Button */
.close {
    position: absolute;
    top: 15px;
    right: 35px;
    color: #f1f1f1;
    font-size: 40px;
    font-weight: bold;
    transition: 0.3s;
}

.close:hover,
.close:focus {
    color: #bbb;
    text-decoration: none;
    cursor: pointer;
}

/* 100% Image Width on Smaller Screens */
@media only screen and (max-width: 700px){
    .modal-contents {
        width: 100%;
    }
}
</style>
<div id="myModal" class="modals" align="center">
  <span class="close">&times;</span>
  <img class="modal-contents" id="img01">
  <div id="caption"></div>
</div>

<script>
var modal = document.getElementById('myModal');

$(document).ready(function(){
 $("img").click(function(){
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