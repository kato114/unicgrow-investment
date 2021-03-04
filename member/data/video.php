<?php
include('../security_web_validation.php');

$sql = "SELECT * FROM video_marketing ORDER BY id DESC LIMIT 10";
$query = query_execute_sqli($sql);
$totalrows = mysqli_num_rows($query);
while($row = mysqli_fetch_array($query)){
	$title = $row['title'];
	$video_link = $row['video_link'];
	$video_link = explode('watch?v=',$row['video_link']);
	$link1 = $video_link[0];
	$link2 = $video_link[1]; ?>
	
	<div class="videoWrapper">
		<iframe width="560" height="349" src="<?=$link1."embed/".$link2?>?rel=0&hd=1" frameborder="0" allowfullscreen></iframe>
	</div> <?php
}  
?>


<style>
.videoWrapper {
    position: relative;
    padding-bottom: 56.25%; /* 16:9 */
    padding-top: 25px;
    height: 0;
	margin-top:20px;
}
.videoWrapper iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}
</style>
<!--<div class="fb-like" data-href=" https://www.unicgrow.com" data-send="true" data-width="450" data-show-faces="true" data-font="verdana" data-colorscheme="dark">
<div class="fb-shared-activity" data-width="300" data-height="300">
<a target="_blank" href="http://www.facebook.com/sharer.php?u=https://youtu.be/ew5egdWnoFc" title="Share this video on Facebook"><iframe width="100%" height="315" src="https://www.youtube.com/embed/ew5egdWnoFc" frameborder="0" gesture="media" allowfullscreen></iframe>
<div style="font-size:16pt;" class="btn btn-blue" >Share on Facebook</a></div></a><BR /><BR />
</div>-->

<!--<h3>Introduction Video</h3>
<div style="padding:56.25% 0 0 0;position:relative;">
	<iframe src="https://player.vimeo.com/video/66865270" style="position:absolute;top:0;left:0;width:100%;height:100%;" webkitallowfullscreen="" mozallowfullscreen="" allowfullscreen="" frameborder="0"></iframe>
</div>
<p>&nbsp;</p><p>&nbsp;</p>

<h3>Compensation Plan</h3>
<div style="padding:56.25% 0 0 0;position:relative;"><iframe src="assets/video/286020006.htm" style="position:absolute;top:0;left:0;width:100%;height:100%;" webkitallowfullscreen="" mozallowfullscreen="" allowfullscreen="" frameborder="0"></iframe></div>-->