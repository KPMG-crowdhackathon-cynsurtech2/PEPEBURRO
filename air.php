<?
$order_location = isset($_REQUEST['order_location']) ? $_REQUEST['order_location'] : "Nicosia";

$claim_status = 0;
$reply = "";
$air_stats = GetAirStats($order_location);

$co = intval($air_stats[6]);
if($co){
	$reply .= chr(10)."Carbon Monoxide (CO): ".$co;
	if($co < 5000)
		$reply .= " (Good)";
	elseif($co < 10000)
		$reply .= " (Modrate)";
	elseif($co < 17000)
		$reply .= " (Unhealty)";
	elseif($co < 34000){
		$claim_status = 1;
		$reply .= " (Very Unhealty)";
	}
	else
		$reply .= " (Hazardous)";
}

$pm10 = intval($air_stats[25]);
if($pm10){
	$reply .= chr(10)."Particulate Matter (PM10): ".$pm10;
	if($pm10 < 50)
		$reply .= " (Good)";
	elseif($pm10 < 150)
		$reply .= " (Modrate)";
	elseif($pm10 < 350)
		$reply .= " (Unhealty)";
	elseif($pm10 < 420){
		$claim_status = 1;
		$reply .= " (Very Unhealty)";
	}
	else
		$reply .= " (Hazardous)";
}

$pm25 = intval($air_stats[26]);
if($pm25){
	$reply .= chr(10)."Particulate Matter (PM25): ".$pm25;
	if($pm25 < 12)
		$reply .= " (Good)";
	elseif($pm25 < 55)
		$reply .= " (Modrate)";
	elseif($pm25 < 150)
		$reply .= " (Unhealty)";
	elseif($pm25 < 250){
		$claim_status = 1;
		$reply .= " (Very Unhealty)";
	}
	else
		$reply .= " (Hazardous)";
}		

$so = intval($air_stats[4]);
if($so){
	$reply .= chr(10)."Sulphur dioxide (SO2): ".$so;
	if($so < 80)
		$reply .= " (Good)";
	elseif($so < 365)
		$reply .= " (Modrate)";
	elseif($so < 800)
		$reply .= " (Unhealty)";
	elseif($so < 1600){
		$claim_status = 1;
		$reply .= " (Very Unhealty)";
	}
	else
		$reply .= " (Hazardous)";
}

$o3 = intval($air_stats[5]);
if($o3){
	$reply .= chr(10)."Ozone (O3): ".$o3;
	if($o3 < 118)
		$reply .= " (Good)";
	elseif($o3 < 157)
		$reply .= " (Modrate)";
	elseif($o3 < 235)
		$reply .= " (Unhealty)";
	elseif($o3 < 785){
		$claim_status = 1;
		$reply .= " (Very Unhealty)";
	}
	else
		$reply .= " (Hazardous)";
}

$no2 = intval($air_stats[2]);
if($no2){
	$reply .= chr(10)."Nitrogen dioxide (NO2): ".$no2;
	if($no2 < 1130)
		$reply .= " (Good)";
	elseif($no2 < 2260){
		$claim_status = 1;
		$reply .= " (Very Unhealty)";
	}
	else
		$reply .= " (Hazardous)";
}

$res = array();
$res['no2']=$no2;
$res['o3']=$o3;
$res['so']=$so;
$res['pm25']=$pm25;
$res['pm10']=$pm10;
$res['co']=$co;
$res['claim_status']=$claim_status;
$res['reply'] = str_replace(chr(10), "<br />", $reply);
echo json_encode($res);





	function GetAirStats($city){
		$apc_name = "calm_data_".$city;
		$res = ApcGet($apc_name, $apc_success);
		if($apc_success)
			return json_decode($res, true);
		
		$xml = simplexml_load_file("http://178.62.245.17/air/airquality.php");
		if($city == "Limassol")
			$station_code = "3";
		if($city == "Nicosia")
			$station_code = "1";
		if($city == "Larnaca")
			$station_code = "4";
		if($city == "Paphos")
			$station_code = "15";
		if($city == "Paralimni")
			$station_code = "16";
		
		$res = array();
		if($xml){
			foreach ($xml->stations->station as $item) { //echo "<li>"; var_dump($item); 
				if($item->station_code == $station_code){ 
					if($item->pollutant_code == "6"){// CO
						$res[6]=(float) $item->pollutant_value[0];						
					}
					
					if($item->pollutant_code == "25"){// PM10
						$res[25]=(float) $item->pollutant_value[0];
					}
					
					if($item->pollutant_code == "4"){// SO
						$res[4]=(float) $item->pollutant_value[0];
					}
					
					if($item->pollutant_code == "5"){// O3
						$res[5]=(float) $item->pollutant_value[0];
					}
					
					if($item->pollutant_code == "2"){// NO2
						$res[2]=(float) $item->pollutant_value[0];
					}
					
					if($item->pollutant_code == "26"){// PM25
						$res[26]=(float) $item->pollutant_value[0];
					}
				}
			}
		}
		
		var_dump($res);
		ApcSet($apc_name, json_encode($res), 60); // 30 минут 	
		
		return $res;
	}
	
	function ApcSet($name, $value, $minutes = 0){
		if($name && $value && $minutes)
			return apcu_add($name, $value, $minutes * 60);
	}

	function ApcGet($name, &$success){
		if($name)
			return apcu_fetch($name, $success);
	}

	function ApcDelete($name){
		if($name)
			return apcu_delete($name);
	}