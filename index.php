<html>
<head>
 <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
 <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css" integrity="sha384-PmY9l28YgO4JwMKbTvgaS7XNZJ30MK9FAZjjzXtlqyZCqBY6X6bXIkM++IkyinN+" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet<html>
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
jQuery(document).ready(function() {
   jQuery(".datepicker").datepicker();
   
   jQuery("#create_form").on("submit", function(){
	if(!jQuery("#accept").is(':checked')){
		jQuery("#accept-info").show();
		return false;
	}
	else
		jQuery("#accept-info").hide();
	
	if(jQuery("#insurance_type").val() == "0"){
		jQuery("#insurance_type_info").show();
		return false;
	}
	
		
   
 });
 
});
  
function change_type(){
	val = jQuery("#insurance_type").val();
	jQuery("#insurance_type_flight").hide();
	jQuery("#insurance_type_event").hide();
	jQuery("#insurance_type_work").hide();
	if(val == "1"){
		jQuery("#insurance_type_flight").show();	
	}
	else if(val == "2"){
		jQuery("#insurance_type_event").show();	
	}
	else if(val == "3"){
		jQuery("#insurance_type_work").show();	
	}	
}


 
</script>

</head>
<div class="container">
<div style="padding-top:20px; padding-bottom: 5px;">
	<a href="/i"><img src="l.jpg"></a>
</div>
	<h1>Create insurance contract</h1>

<?
require_once("settings.php");
require_once("mysql.php");

$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : "";

if($op == "create"){
	$pdo = GetConnection();
	
	$insurance_type = isset($_REQUEST['insurance_type']) ? $_REQUEST['insurance_type'] : "";
	$flight_number = isset($_REQUEST['flight_number']) ? $_REQUEST['flight_number'] : "";
	$flight_date = isset($_REQUEST['flight_date']) ? $_REQUEST['flight_date'] : "";
	$event_date = isset($_REQUEST['event_date']) ? $_REQUEST['event_date'] : "";
	$work_date = isset($_REQUEST['work_date']) ? $_REQUEST['work_date'] : "";
	$event_location = isset($_REQUEST['event_location']) ? $_REQUEST['event_location'] : "";
	$work_location = isset($_REQUEST['work_location']) ? $_REQUEST['work_location'] : "";
	$customer_name = isset($_REQUEST['customer_name']) ? $_REQUEST['customer_name'] : "";
	
	$provider_id = isset($_REQUEST['provider_id']) ? $_REQUEST['provider_id'] : "";
	
	

	$contract = array();
	$contract['type_id'] = $insurance_type;
	$contract['order_id'] = $flight_number;
	$contract['customer'] = $customer_name;
	$contract['status'] = "start";
	$contract['provider_id'] = $provider_id;
	
	if($insurance_type == "1"){
		$claim_date = $flight_date;
	}
	if($insurance_type == "2"){
		$claim_date = $event_date;
		$location = $event_location;
	}
	if($insurance_type == "3"){
		$claim_date = $work_date;
		$location = $work_location;
	}
	
	$claim_date = strtotime($claim_date);
	$contract['claim_date'] = date("Y-m-d H:i:s", $claim_date);
	
	$contract['location'] = $location;
	
	add_contract($pdo, $contract);
	//var_dump($contract);
	
	echo '<div class="alert alert-success" role="alert"> Contract successfully created! </div>';
}
?>	
	
	
	<form id="create_form" method="post">
	  <input type="hidden" name="op" value="create">
	  <div class="alert alert-danger" style="display: none" id="insurance_type_info">
			Please select insurance type.
	  </div>
	  <div class="form-group">
		<label class="control-label" for="insurance_type">Insurance type</label>
		<select class="form-control input-lg" id="insurance_type" name="insurance_type" onchange="change_type();">
			<option value="0">Select...</option>
			<option value="1">Flight delay</option>
			<option value="2">Cancellation of outdoor event</option>
			<option value="3">Employers liability</option>
			<option value="4">Ski trip cancellation</option>
		</select>
	  </div>
	  
	  <div class="form-group" style="display: none" id="insurance_type_flight">
		<label for="flight_number">Flight No.</label>
		<input class="form-control" name="flight_number" id="flight_number">
		<label for="flight_date">Flight date</label>
		<input class="form-control datepicker" name="flight_date" id="flight_date">
	  </div>
	  <div class="form-group" style="display: none" id="insurance_type_event">
		<label for="event_date">Event date</label>
		<input class="form-control datepicker" name="event_date" id="event_date">
		<label for="event_date">Event Location</label>
		<input class="form-control" name="event_location" id="event_location">
	  </div>
	  <div class="form-group" style="display: none" id="insurance_type_work">
		<label for="work_date">Work date</label>
		<input class="form-control datepicker" name="work_date" id="work_date">
		<label for="event_date">Work Location</label>
		<input class="form-control" name="work_location" id="work_location">
	  </div>
	  
	  <div class="form-group">
		<label for="customer_name">Customer Name</label>
		<input class="form-control" name="customer_name" id="customer_name">
	  </div>	  
	  <div class="alert alert-danger" style="display: none" id="accept-info">
			Please accept service rules and conditions.
	  </div>
	  <div class="checkbox" >
		<label>
		  <input type="checkbox" id="accept" value="1"> Accept rules and conditions
		</label>
	  </div>
	  <button type="submit" class="btn btn-default">Submit</button>

	</form>
</div>



</html>

" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap-theme.min.css" integrity="sha384-jzngWsPS6op3fgRCDTESqrEJwRKck+CILhJVO5VvaAZCq8JYf8HsR/HPpBOOPZfR" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js" integrity="sha384-vhJnz1OVIdLktyixHY4Uk3OHEwdQqPppqYR8+5mjsauETgLOcEynD9oPHhhz18Nw" crossorigin="anonymous"></script>


<script>
jQuery(document).ready(function() {
   jQuery(".datepicker").datepicker();
   
   jQuery("#create_form").on("submit", function(){
	if(!jQuery("#accept").is(':checked')){
		jQuery("#accept-info").show();
		return false;
	}
	else
		jQuery("#accept-info").hide();
	
	if(jQuery("#insurance_type").val() == "0"){
		jQuery("#insurance_type_info").show();
		return false;
	}
	
		
   
 });
 
});
  
function change_type(){
	val = jQuery("#insurance_type").val();
	jQuery("#insurance_type_flight").hide();
	jQuery("#insurance_type_event").hide();
	jQuery("#insurance_type_work").hide();
	if(val == "1"){
		jQuery("#insurance_type_flight").show();	
	}
	else if(val == "2"){
		jQuery("#insurance_type_event").show();	
	}
	else if(val == "3"){
		jQuery("#insurance_type_work").show();	
	}	
}


 
</script>

</head>
<div class="container">
	<h1>Create insurance contract</h1>

<?
require_once("settings.php");
require_once("mysql.php");

$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : "";

if($op == "create"){
	$pdo = GetConnection();
	
	$insurance_type = isset($_REQUEST['insurance_type']) ? $_REQUEST['insurance_type'] : "";
	$flight_number = isset($_REQUEST['flight_number']) ? $_REQUEST['flight_number'] : "";
	$flight_date = isset($_REQUEST['flight_date']) ? $_REQUEST['flight_date'] : "";
	$event_date = isset($_REQUEST['event_date']) ? $_REQUEST['event_date'] : "";
	$work_date = isset($_REQUEST['work_date']) ? $_REQUEST['work_date'] : "";
	$event_location = isset($_REQUEST['event_location']) ? $_REQUEST['event_location'] : "";
	$work_location = isset($_REQUEST['work_location']) ? $_REQUEST['work_location'] : "";
	$customer_name = isset($_REQUEST['customer_name']) ? $_REQUEST['customer_name'] : "";
	
	$provider_id = isset($_REQUEST['provider_id']) ? $_REQUEST['provider_id'] : "";
	
	

	$contract = array();
	$contract['type_id'] = $insurance_type;
	$contract['order_id'] = $flight_number;
	$contract['customer'] = $customer_name;
	$contract['status'] = "start";
	$contract['provider_id'] = $provider_id;
	
	if($insurance_type == "1"){
		$claim_date = $flight_date;
	}
	if($insurance_type == "2"){
		$claim_date = $event_date;
		$location = $event_location;
	}
	if($insurance_type == "3"){
		$claim_date = $work_date;
		$location = $work_location;
	}
	
	$claim_date = strtotime($claim_date);
	$contract['claim_date'] = date("Y-m-d H:i:s", $claim_date);
	
	$contract['location'] = $location;
	
	add_contract($pdo, $contract);
	//var_dump($contract);
	
	echo '<div class="alert alert-success" role="alert"> Contract successfully created! </div>';
}
?>	
	
	
	<form id="create_form" method="post">
	  <input type="hidden" name="op" value="create">
	  <div class="alert alert-danger" style="display: none" id="insurance_type_info">
			Please select insurance type.
	  </div>
	  <div class="form-group">
		<label class="control-label" for="insurance_type">Insurance type</label>
		<select class="form-control input-lg" id="insurance_type" name="insurance_type" onchange="change_type();">
			<option value="0">Select...</option>
			<option value="1">Flight delay</option>
			<option value="2">Cancellation of outdoor event</option>
			<option value="3">Employers liability</option>
			<option value="4">Ski trip cancellation</option>
		</select>
	  </div>
	  
	  <div class="form-group" style="display: none" id="insurance_type_flight">
		<label for="flight_number">Flight No.</label>
		<input class="form-control" name="flight_number" id="flight_number">
		<label for="flight_date">Flight date</label>
		<input class="form-control datepicker" name="flight_date" id="flight_date">
	  </div>
	  <div class="form-group" style="display: none" id="insurance_type_event">
		<label for="event_date">Event date</label>
		<input class="form-control datepicker" name="event_date" id="event_date">
		<label for="event_date">Event Location</label>
		<input class="form-control" name="event_location" id="event_location">
	  </div>
	  <div class="form-group" style="display: none" id="insurance_type_work">
		<label for="work_date">Work date</label>
		<input class="form-control datepicker" name="work_date" id="work_date">
		<label for="event_date">Work Location</label>
		<input class="form-control" name="work_location" id="work_location">
	  </div>
	  
	  <div class="form-group">
		<label for="customer_name">Customer Name</label>
		<input class="form-control" name="customer_name" id="customer_name">
	  </div>	  
	  <div class="alert alert-danger" style="display: none" id="accept-info">
			Please accept service rules and conditions.
	  </div>
	  <div class="checkbox" >
		<label>
		  <input type="checkbox" id="accept" value="1"> Accept rules and conditions
		</label>
	  </div>
	  <button type="submit" class="btn btn-default">Submit</button>

	</form>
</div>



</html>

