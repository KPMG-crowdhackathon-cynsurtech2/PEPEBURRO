<?
$city = isset($_REQUEST['city']) ? $_REQUEST['city'] : "Nicosia";

$weather = file_get_contents("https://www.topcyprus.net/i/weather.php?city=".$city); 
$air = file_get_contents("https://www.topcyprus.net/i/air.php?order_location=".$city);

$res = array();
if($weather && $air){
	$weather = json_decode($weather, true);
	$air = json_decode($air, true);
	
	if($weather['temperature'] < 45 && !$weather['disaster'] && $air['claim_status'] == 0 && $air['speed'] < 110)
		$res['claim_status'] = 0;
	else
		$res['claim_status'] = 1;
	
	$res['air'] = $air;
	$res['weather'] = $weather;
}
echo json_encode($res);
