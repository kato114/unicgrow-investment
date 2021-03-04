<?php


$share_buy = implode(", ",sharedata_buy_or_sale(1));
$share_sale = implode(", ",sharedata_buy_or_sale(2));
?>
<div class="col-lg-6">
	<div class="ibox">
		<div class="ibox-title">
			<h5>Buy Chart</h5>
			<div class="ibox-tools">
				<a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
				<a class="close-link"><i class="fa fa-times"></i></a>
			</div>
		</div>
		<div class="ibox-content">
			<div class="flot-chart">
				<div class="flot-chart-content" id="flot-line-chart"></div>
			</div>
		</div>
	</div>
</div>
<div class="col-md-6">
	<div class="ibox">
		<div class="ibox-title">
			<h5>Sale Chart</h5>
			<div class="ibox-tools">
				<a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
				<a class="close-link"><i class="fa fa-times"></i></a>
			</div>
		</div>
		<div class="ibox-content">
			<div class="flot-chart">
				<div class="flot-chart-content" id="flot-line-chart12"></div>
			</div>
		</div>
	</div>
</div>
<script>
$(function() {
    var barOptions = {
        series: {
            lines: {
                show: true,
                lineWidth: 2,
                fill: true,
                fillColor: {
                    colors: [{
                        opacity: 0.0
                    }, {
                        opacity: 0.0
                    }]
                }
            }
        },
        xaxis: {
            tickDecimals: 0
        },
        colors: ["#1ab394"],
        grid: {
            color: "#999999",
            hoverable: true,
            clickable: true,
            tickColor: "#D4D4D4",
            borderWidth:0
        },
        legend: {
            show: false
        },
        tooltip: true,
        tooltipOpts: {
             content: "Share: %y, Amount: %x"
        }
    };
    var barData = {
        label: "bar",
        data: [<?=$share_buy?> ]
    };
    $.plot($("#flot-line-chart"), [barData], barOptions);
});

$(function() {
    var barOptions = {
        series: {
            lines: {
                show: true,
                lineWidth: 2,
                fill: true,
                fillColor: {
                    colors: [{
                        opacity: 0.0
                    }, {
                        opacity: 0.0
                    }]
                }
            }
        },
        xaxis: {
            tickDecimals: 0
        },
        colors: ["#D08883"],
        grid: {
            color: "#999999",
            hoverable: true,
            clickable: true,
            tickColor: "#D4D4D4",
            borderWidth:0
        },
        legend: {
            show: false
        },
        tooltip: true,
        tooltipOpts: {
            content: "Share: %y, Amount: %x"
        }
    };
    var barData = {
        label: "bar",
        data: [<?=$share_sale?> ]
    };
    $.plot($("#flot-line-chart12"), [barData], barOptions);
});
</script>
<?php
function sharedata_buy_or_sale($type){
	$info = array();
	$dates;
	//for($i = 0; $i < count($dates); $i++){
		$date = $dates[$i];
		$sql = "SELECT COALESCE(total_amount,0) amt, share FROM trade_buy WHERE type = '$type' AND 
		mode = 0 GROUP BY share";
		$query = query_execute_sqli($sql);
		$num = mysqli_num_rows($query);
		if($num > 0){
			while($row = mysqli_fetch_array($query)){
				//$amt[] = $row['amt'];
				//$share[] = $row['share'];
				$info[] = "[".$row['amt'].",".$row['share']."]";
			}
		}
		else{
			$info[] = 0;
		}
		mysqli_free_result($query);
	//}
	return $info;
}
?>
