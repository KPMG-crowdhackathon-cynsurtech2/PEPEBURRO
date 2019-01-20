<?
include_once("/var/www/html/topcyprus.net/i/settings.php");	
include_once("/var/www/html/topcyprus.net/i/mysql.php");	

$contract_id_to_predict = isset($_REQUEST['contract_id']) ? $_REQUEST['contract_id'] : 0;
if(!$contract_id_to_predict)
	die("contract_id is missing");

$contract_to_predict = get_by_id($contract_id_to_predict);
$type_id = $contract_to_predict["type_id"];
$contracts = get_by_type_id($type_id);

if(count($contracts) < 2)
	die("Not enought data");

require_once __DIR__ . '/vendor/autoload.php';

use Phpml\Classification\KNearestNeighbors;

foreach ($contracts as $contract){
	if($contract['id'] != $contract_id_to_predict){
		$data[] = $contract;
		$labels[] = $contract['status'];
	}
	else
		$unknown_user = $contract;
}

$classifier = new KNearestNeighbors();
$classifier->train($data, $labels);

echo $classifier->predict($unknown_user);

?>