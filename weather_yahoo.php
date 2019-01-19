<?php
// header('Content-Type: application/json');
$configs = include('config.php');
$city = 'Nicosia';
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
switch ($curr_condition["code"]) {
    case '0':
        $curr_condition["weather_cond"] = "tornado";
        break;
    case '1':
        $curr_condition["weather_cond"] = "tropical_storm";
        break;
    case '2':
        $curr_condition["weather_cond"] = "hurricane";
        break;
    case '3':
        $curr_condition["weather_cond"] = "severe_thunderstorms";
        break;
    case '4':
        $curr_condition["weather_cond"] = "thunderstorms";
        break;
    case '19':
        $curr_condition["weather_cond"] = "dust";
        break;
    case '20':
        $curr_condition["weather_cond"] = "foggy";
        break;
    case '36':
        $curr_condition["weather_cond"] = "hot";
        break;
    case '41':
        $curr_condition["weather_cond"] = "heavy_snow";
        break;
    case '43':
        $curr_condition["weather_cond"] = "heavy_snow";
        break;
    
    default:
        $curr_condition["weather_cond"] = "all_good";
        break;
}
// print_r($curr_condition['speed']);
print_r($curr_condition);
return $curr_condition;
// $return_data = json_decode($response);
// print_r(json_encode($return_data));