<html>
<head>
 <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
 <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css" integrity="sha384-PmY9l28YgO4JwMKbTvgaS7XNZJ30MK9FAZjjzXtlqyZCqBY6X6bXIkM++IkyinN+" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap-theme.min.css" integrity="sha384-jzngWsPS6op3fgRCDTESqrEJwRKck+CILhJVO5VvaAZCq8JYf8HsR/HPpBOOPZfR" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js" integrity="sha384-vhJnz1OVIdLktyixHY4Uk3OHEwdQqPppqYR8+5mjsauETgLOcEynD9oPHhhz18Nw" crossorigin="anonymous"></script>

<html>
<head>
 <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
 <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css" integrity="sha384-PmY9l28YgO4JwMKbTvgaS7XNZJ30MK9FAZjjzXtlqyZCqBY6X6bXIkM++IkyinN+" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap-theme.min.css" integrity="sha384-jzngWsPS6op3fgRCDTESqrEJwRKck+CILhJVO5VvaAZCq8JYf8HsR/HPpBOOPZfR" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js" integrity="sha384-vhJnz1OVIdLktyixHY4Uk3OHEwdQqPppqYR8+5mjsauETgLOcEynD9oPHhhz18Nw" crossorigin="anonymous"></script>


<script>
function check_id(id){
	
	$.post(
	  "api.php",
	  {
		"op": "get_by_id",
		"id": id
	  },
	  onAjaxSuccess
	);
	return false;
}

function onAjaxSuccess(data)
{
	contract = JSON.parse(data);
	out = '<p><label>Insurance Type: '+contract['insurance_type']+'</label></p>';
	if(contract['claim_date'])
		out += '<p><label>Date: '+contract['claim_date']+'</label></p>';
	if(contract['order_location'])
		out += '<p><label>Location: '+contract['order_location']+'</label></p>';
	out += '<p><a class="btn btn-default" href="#" onclick="return check_claim('+contract['type_id']+',\''+contract['claim_date']+'\',\''+contract['order_location']+'\',\''+contract['order_id']+'\')" role="button">Check claim</a> <a class="btn btn-default" href="#" onclick="return predict_claim('+contract['id']+')" role="button">Predict claim status</a></p>';
	out += '<div id="info_details"></div>';
	out += ' <iframe id="info_details_frame" src="" width="100%" height="500" style="display:none">';
	jQuery("#info").html(out);
	jQuery("#info").show();
	//alert(data);
}
function predict_claim(contract_id){
	$.post(
	  "ai/tool.php",
	  {
		"contract_id": contract_id,
	  },
	  onClaimPredictAjaxSuccess,
	);
}

function check_claim(insurance_type, claim_date, order_location, order_id){
	if(insurance_type == 1){
		$("#info_details_frame").attr("src", "delay.php?flight="+order_id);
		$("#info_details_frame").show();
	}
	else if(insurance_type == 3){
		$("#info_details_frame").hide();
		
		$.post(
		  "liability.php",
		  {
			"city": order_location,
		  },
		  onClaimLiabilityAjaxSuccess,
		);
		
	}
	else if(insurance_type == 2){
		$("#info_details_frame").hide();
		$.post(
		  "air.php",
		  {
			"order_location": order_location,
		  },
		  onClaimAjaxSuccess,
		);
	}
	else if(insurance_type == 4){
		$("#info_details_frame").hide();
	}
	
	return false;
}

function onClaimPredictAjaxSuccess(data){
	data_answer = parseInt(data);
	if(data_answer == 0){
		out = '<h4 class="alert alert-success" role="alert">Prediction: No claim event</h4>';
	}
	else if(data_answer == 1){
		out = '<h4 class="alert alert-danger">Prediction: Claim event</h4>';
	}
	else{
		out = '<h4 class="alert alert-danger">'+data+'</h4>';
	}
	
	jQuery("#info_details").html(out);

	jQuery("#info_details").show();
}

function onClaimLiabilityAjaxSuccess(json){
	data = JSON.parse(json);
	air = data['air'];
	weather = data['weather'];
	out = air['reply'] + "<hr />" + weather['reply'];
	if(parseInt(data['claim_status']) == 0){
		out += '<h4 class="alert alert-success" role="alert">No claim event</h4>';
	}
	else{
		out += '<h4 class="alert alert-danger">Claim event</h4>';
	}
	
	jQuery("#info_details").html(out);

	jQuery("#info_details").show();
}

function onClaimWeatherAjaxSuccess(data){
	wether = JSON.parse(data);
	jQuery("#info_details").html(data);

	jQuery("#info_details").show();
}

function onClaimAjaxSuccess(data)
{	
	air = JSON.parse(data);
	console.log(data);
	out = air['reply']; 
	if(parseInt(air['claim_status']) == 0){
		out += '<h4 class="alert alert-success" role="alert">No claim event</h4>';
	}
	else{
		out += '<h4 class="alert alert-danger">Claim event</h4>';
	}
	jQuery("#info_details").html(out);

	jQuery("#info_details").show();
}
 
</script>

</head>
<div class="container">

<div style="padding-top:20px; padding-bottom: 5px;">
	<a href="/i/backend.php"><img src="l.jpg"></a>
</div>

	<h1>Contracts</h1>

<?
require_once("settings.php");
require_once("mysql.php");

$pdo = GetConnection();
$contracts = get_all($pdo);

echo '
<div id="info" style="display:none; padding-bottom: 20px"></div>
<table class="table table-condensed">
<tr><th>ID</th><th>Insurance</th><th>Insurance data</th><th>Customer</th><th>Status</th><th>Claim Date</th><th>Location</th><th>Claim Date</th><th>Action</th></tr>
';
  
foreach($contracts as $contract){
	echo '<tr><td>'.$contract['id'].'</td><td>'.(Get_Insurance($contract['type_id'])).'</td><td>'.$contract['order_id'].'</td><td>'.$contract['customer'].'</td><td>'.$contract['status'].'</td><td>'.$contract['claim_date'].'</td><td>'.$contract['order_location'].'</td><td>'.$contract['claim_date'].'</td><td><a href="#" onclick="return check_id('.$contract['id'].')">View</a></td>';
}
echo '</table>';


?>	
	
	
	
</div>



</html>


<script>
function check_id(id){
	
	$.post(
	  "api.php",
	  {
		"op": "get_by_id",
		"id": id
	  },
	  onAjaxSuccess
	);
	return false;
}

function onAjaxSuccess(data)
{
	contract = JSON.parse(data);
	out = '<p><label>Insurance Type: '+contract['insurance_type']+'</label></p>';
	if(contract['claim_date'])
		out += '<p><label>Date: '+contract['claim_date']+'</label></p>';
	if(contract['order_location'])
		out += '<p><label>Location: '+contract['order_location']+'</label></p>';
	out += '<p><a class="btn btn-default" href="#" onclick="return check_claim('+contract['type_id']+',\''+contract['claim_date']+'\',\''+contract['order_location']+'\',\''+contract['order_id']+'\')" role="button">Check claim</a></p>';
	out += '<div id="info_details"></div>';
	out += ' <iframe id="info_details_frame" src="" width="100%" height="500" style="display:none">';
	jQuery("#info").html(out);
	jQuery("#info").show();
	//alert(data);
}

function check_claim(insurance_type, claim_date, order_location, order_id){
	if(insurance_type == 1){
		$("#info_details_frame").attr("src", "delay.php?flight="+order_id);
		$("#info_details_frame").show();
	}
	else if(insurance_type == 3){
		$("#info_details_frame").hide();
		
		$.post(
		  "liability.php",
		  {
			"city": order_location,
		  },
		  onClaimLiabilityAjaxSuccess,
		);
		
	}
	else if(insurance_type == 2){
		$("#info_details_frame").hide();
		$.post(
		  "air.php",
		  {
			"order_location": order_location,
		  },
		  onClaimAjaxSuccess,
		);
	}
	else if(insurance_type == 4){
		$("#info_details_frame").hide();
	}
	
	return false;
}

function onClaimLiabilityAjaxSuccess(json){
	data = JSON.parse(json);
	air = data['air'];
	weather = data['weather'];
	out = air['reply'] + "<br />" + weather['reply'];
	if(parseInt(data['claim_status']) == 0){
		out += '<h4 class="alert alert-success" role="alert">No claim event</h4>';
	}
	else{
		out += '<h4 class="alert alert-danger">Claim event</h4>';
	}
	
	jQuery("#info_details").html(out);

	jQuery("#info_details").show();
}

function onClaimWeatherAjaxSuccess(data){
	wether = JSON.parse(data);
	jQuery("#info_details").html(data);

	jQuery("#info_details").show();
}

function onClaimAjaxSuccess(data)
{	
	air = JSON.parse(data);
	console.log(data);
	out = air['reply']; 
	if(parseInt(data['claim_status']) == 0){
		out += '<h4 class="alert alert-success" role="alert">No claim event</h4>';
	}
	else{
		out += '<h4 class="alert alert-danger">Claim event</h4>';
	}
	jQuery("#info_details").html(out);

	jQuery("#info_details").show();
}
 
</script>

</head>
<div class="container">
	<h1>Contracts</h1>

<?
require_once("settings.php");
require_once("mysql.php");

$pdo = GetConnection();
$contracts = get_all($pdo);

echo '
<div id="info" style="display:none; padding-bottom: 20px"></div>
<table class="table table-condensed">
<tr><th>ID</th><th>Insurance</th><th>Insurance data</th><th>Customer</th><th>Status</th><th>Claim Date</th><th>Location</th><th>Claim Date</th><th>Action</th></tr>
';
  
foreach($contracts as $contract){
	echo '<tr><td>'.$contract['id'].'</td><td>'.(Get_Insurance($contract['type_id'])).'</td><td>'.$contract['order_id'].'</td><td>'.$contract['customer'].'</td><td>'.$contract['status'].'</td><td>'.$contract['claim_date'].'</td><td>'.$contract['order_location'].'</td><td>'.$contract['claim_date'].'</td><td><a href="javascript:check_id('.$contract['id'].')">View</a></td>';
}
echo '</table>';


?>	
	
	
	
</div>



</html>

