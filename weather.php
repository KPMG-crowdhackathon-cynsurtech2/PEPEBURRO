<?php
$configs = include('config.php');
$city = isset($_REQUEST['city']) ? $_REQUEST['city'] : "Nicosia";
$country_state = 'cy';
$format = 'json'; // json || xml
$unit_sys = 'c'; // c - metric || f - imperial

function buildBaseString($baseURI, $method, $params) {
    $r = array();
    ksort($params);
    foreach($params as $key => $value) {
        $r[] = "$key=" . rawurlencode($value);
    }
    return $method . "&" . rawurlencode($baseURI) . '&' . rawurlencode(implode('&', $r));
}
function buildAuthorizationHeader($oauth) {
    $r = 'Authorization: OAuth ';
    $values = array();
    foreach($oauth as $key=>$value) {
        $values[] = "$key=\"" . rawurlencode($value) . "\"";
    }
    $r .= implode(', ', $values);
    return $r;
}


$url = 'https://weather-ydn-yql.media.yahoo.com/forecastrss';
$app_id = $configs['app_id'];
$consumer_key = $configs['consumer_key'];
$consumer_secret = $configs['consumer_secret'];
$query = array(
    'location' => $city.','.$country_state,
    'format' => 'json',
    'u' => $unit_sys,
);
$oauth = array(
    'oauth_consumer_key' => $consumer_key,
    'oauth_nonce' => uniqid(mt_rand(1, 1000)),
    'oauth_signature_method' => 'HMAC-SHA1',
    'oauth_timestamp' => time(),
    'oauth_version' => '1.0'
);
$base_info = buildBaseString($url, 'GET', array_merge($query, $oauth));
$composite_key = rawurlencode($consumer_secret) . '&';
$oauth_signature = base64_encode(hash_hmac('sha1', $base_info, $composite_key, true));
$oauth['oauth_signature'] = $oauth_signature;
$header = array(
    buildAuthorizationHeader($oauth),
    'Yahoo-App-Id: ' . $app_id
);
$options = array(
    CURLOPT_HTTPHEADER => $header,
    CURLOPT_HEADER => false,
    CURLOPT_URL => $url . '?' . http_build_query($query),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYPEER => false
);
$ch = curl_init();
curl_setopt_array($ch, $options);
$response = curl_exec($ch);
curl_close($ch);
$json = json_decode($response, true);
$curr_condition = (array) [
    "speed" => $json["current_observation"]["wind"]["speed"],
    "visibility" => $json["current_observation"]["atmosphere"]["visibility"],
    "pressure" => $json["current_observation"]["atmosphere"]["pressure"],
    "temperature" => $json["current_observation"]["condition"]["temperature"],
    "code" => $json["current_observation"]["condition"]["code"]
];

$disaster = false;

switch ($curr_condition["code"]) {
    case '0':
        $curr_condition["weather_cond"] = "Tornado";
		$disaster = true;
        break;
    case '1':
        $curr_condition["weather_cond"] = "Tropical Storm";
		$disaster = true;
        break;
    case '2':
        $curr_condition["weather_cond"] = "Hurricane";
		$disaster = true;
        break;
    case '3':
        $curr_condition["weather_cond"] = "Severe Thunderstorms";
        break;
    case '4':
        $curr_condition["weather_cond"] = "Thunderstorms";
        break;
    case '19':
        $curr_condition["weather_cond"] = "Dust";
        break;
    case '20':
        $curr_condition["weather_cond"] = "Foggy";
        break;
    case '36':
        $curr_condition["weather_cond"] = "Hot";
        break;
    case '41':
        $curr_condition["weather_cond"] = "Heavy Snow";
        break;
    case '43':
        $curr_condition["weather_cond"] = "Heavy snow";
        break;
    
    default:
        $curr_condition["weather_cond"] = "Fine";
        break;
}

$reply = "Current tempterature: ".$curr_condition["temperature"]." °C ".$curr_condition["weather_cond"]." <br />Wind: ".$curr_condition["speed"]." km/h. Visibility ".round($curr_condition["visibility"])." km";
$curr_condition['reply'] = $reply;
$curr_condition['disaster'] = $disaster;
// print_r($curr_condition['speed']);
//print_r($curr_condition);
echo json_encode($curr_condition);
// $return_data = json_decode($response);
// print_r(json_encode($return_data));