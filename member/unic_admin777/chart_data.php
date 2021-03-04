<?php
$user_id = $_SESSION['mlmproject_user_id'];
$sql = "SELECT *,DATE_FORMAT(date, '%h:%i%p') time_f FROM trade_buy WHERE mode IN (0) GROUP BY DATE_FORMAT(`date`, '%H:%i') LIMIT 20";
/*$sql = "SELECT DATE_FORMAT(date, '%h%p') time_f, HOUR(date) dates FROM trade_buy WHERE mode IN (0) GROUP BY dates ORDER BY date LIMIT 20";*/
//$sql = "SELECT extract(hour from date) hours from trade_buy group by extract(hour from date)";
//$sql = "SELECT * FROM trade_buy WHERE user_id = '$user_id' AND mode IN (0) GROUP BY DATE(date) LIMIT 10";
$query = query_execute_sqli($sql);
$totalrows = mysqli_num_rows($query);
$dates = $buy_share = $sale_share = array();

while($row = mysqli_fetch_array($query)){
	$date = $row['date'];
	$date1[] = date('H:i' , strtotime($date));
	$time_f = $row['time_f'];
	//$dates[] = '"'.date('H:i' , strtotime($date)).'"';
	$dates[] = '"'.$time_f.'"';
}

$share_buy = implode(", ",sharedata_buy_or_sale($user_id, 1,$date1));
$share_sale = implode(", ",sharedata_buy_or_sale($user_id, 2,$date1));
$imp_dats = implode(", ",$dates);
//$share_buy = implode(", ",$buy_share);
//$share_sale = implode(", ",$sale_share);


?>
<script>
$(function () {

    var lineData = {
        labels: [<?=$imp_dats?>],
	    datasets: [
            {
                label: "Example dataset",
                fillColor: "rgba(26,179,148,0.5)",
                strokeColor: "rgba(26,179,148,0.7)",
                pointColor: "rgba(26,179,148,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(26,179,148,1)",
                data: [<?=$share_buy?>]
				//data: [65, 59, 80, 81, 56, 55, 40]
            },
            {
                label: "Example dataset",
				fillColor: "rgba(208,136,131,0.5)",
                strokeColor: "rgba(208,136,131,1)",
                pointColor: "rgba(208,136,131,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(208,136,131,1)",
                data: [<?=$share_sale?>]
				//data: [28, 48, 40, 19, 86, 27, 90]
            }
        ]
    };

    var lineOptions = {
        scaleShowGridLines: true,
        scaleGridLineColor: "rgba(0,0,0,.05)",
        scaleGridLineWidth: 1,
        bezierCurve: true,
        bezierCurveTension: 0.4,
        pointDot: true,
        pointDotRadius: 4,
        pointDotStrokeWidth: 1,
        pointHitDetectionRadius: 20,
        datasetStroke: true,
        datasetStrokeWidth: 2,
        datasetFill: true,
        responsive: true,
    };

    var ctx = document.getElementById("lineChart").getContext("2d");
    var myNewChart = new Chart(ctx).Line(lineData, lineOptions);

    var myNewChart = new Chart(ctx).Radar(radarData, radarOptions);
});
</script>

<?php
function sharedata_buy_or_sale($user_id, $type,$dates){
	$info = array();
	 $dates;
	for($i = 0; $i < count($dates); $i++){
		$date = $dates[$i];
		$sql = "SELECT COALESCE(total_amount,0) buy_share FROM trade_buy WHERE type = '$type' AND 
		DATE_FORMAT(`date`, '%H:%i') = '$date' GROUP BY DATE_FORMAT(`date`, '%H:%i') ";
		$query = query_execute_sqli($sql);
		$num = mysqli_num_rows($query);
		if($num > 0){
			while($row = mysqli_fetch_array($query)){
				$info[] = $row['buy_share'];
			}
		}
		else{
			$info[] = 0;
		}
		mysqli_free_result($query);
	}
	return $info;
}
?>