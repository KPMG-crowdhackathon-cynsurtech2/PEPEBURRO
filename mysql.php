<?
include_once("/var/www/html/tools/Aura.SqlQuery-3.x/autoload.php");
use Aura\SqlQuery\QueryFactory;


function add_contract($pdo, $contract){
	$query_factory = new QueryFactory('mysql');
	
	$insert = $query_factory->newInsert();

	$insert
    ->into('contracts')                 
    ->cols(array(                  
		'type_id',
        'order_id',
		'customer',
		'status',
		'provider_id',
		'modify_date',
		'claim_date',
    ))
	->set('modify_date', 'NOW()')     // raw value as "(ts) VALUES (NOW())"
    ->bindValues(array(             // bind these values
		'type_id' => $contract['type_id'],
		'order_id' => $contract['order_id'],
		'customer' => $contract['customer'],
		'status' => $contract['status'],
		'provider_id' => $contract['provider_id'],
		'claim_date' => $contract['claim_date'],
		
    ))
	;


	$sth = $pdo->prepare($insert->getStatement());
	$sth->execute($insert->getBindValues());
}

function get_by_id($id) {
    $pdo = GetConnection();

	/* todo params to filter */
	$query_factory = new QueryFactory('mysql');

	$select = $query_factory->newSelect();


	$select->cols(array(
		'id',
        'type_id',
        'order_id',
		'customer',
		'status',
		'provider_id',
		'modify_date',
		'claim_date',
		
		))
		->from('contracts')
		->where('id = :id')
		->bindValues(array(             // bind these values
			'id' => $id,
			))
	;
		   
	// prepare the statment
	$sth = $pdo->prepare($select->getStatement());

	// bind the values and execute
	$sth->execute($select->getBindValues());

	// get the results back as an associative array
	$result = $sth->fetch(PDO::FETCH_ASSOC);	   

	return $result;
} 


function get_all() {
    $pdo = GetConnection();

	/* todo params to filter */
	$query_factory = new QueryFactory('mysql');

	$select = $query_factory->newSelect();


	$select->cols(array(
		'id',
        'type_id',
        'order_id',
		'customer',
		'status',
		'provider_id',
		'modify_date',
		'claim_date',
		
		))
		->from('contracts')
	;
		   
	// prepare the statment
	$sth = $pdo->prepare($select->getStatement());

	// bind the values and execute
	$sth->execute($select->getBindValues());

	// get the results back as an associative array
	$result = $sth->fetch(PDO::FETCH_ASSOC);	   

	return $result;
} 



function get_by_type_id($type_id) {
    $pdo = GetConnection();

	/* todo params to filter */
	$query_factory = new QueryFactory('mysql');

	$select = $query_factory->newSelect();


	$select->cols(array(
		'id',
        'type_id',
        'order_id',
		'customer',
		'status',
		'provider_id',
		'modify_date',
		'claim_date',
		
		))
		->from('contracts')
		->where('type_id = :type_id')
		->bindValues(array(             // bind these values
			'type_id' => $type_id,
			))
	;
		   
	// prepare the statment
	$sth = $pdo->prepare($select->getStatement());

	// bind the values and execute
	$sth->execute($select->getBindValues());

	// get the results back as an associative array
	$result = $sth->fetchAll(PDO::FETCH_ASSOC);	   

	return $result;
} 