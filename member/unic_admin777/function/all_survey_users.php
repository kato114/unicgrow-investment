<?php
function total_user_survey($survey_id)
{
	$query = query_execute_sqli("select * from survey where survey_id = '$survey_id' ");
	$num = mysqli_num_rows($query);
	return $num;
}