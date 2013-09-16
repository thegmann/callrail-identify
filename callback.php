<?

  // Replace with the Company ID
  $company='CompanyID';

  // Replace with your API Key
  $api_key = '5b6e4157963a4a34d5a95e7f35f6a2fc';

  // Retrieve the request's body and parse it as JSON
  $body = @file_get_contents('php://input');
  $event_json = json_decode($body);
  curl_close($ch);

  // Examine meta data from the response
  $caller = $event_json->callernum;

  $start=date('c',date(U)-60); //You may want to shorten this if you get tons of calls, try 30 instead of 60
  $end=date('c',date(U)+60); //You may want to shorten this if you get tons of calls, try 30 instead of 60
  
  $api_url = "https://api.callrail.com/v1/calls.json?company_id=$company&start_date=$start&end_date=$end";

  $ch = curl_init($api_url);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Token token=\"{$api_key}\""));

  $json_data = curl_exec($ch);
  $parsed_data = json_decode($json_data);
  curl_close($ch);

  // Examine meta data from the response
  $calls = $parsed_data->calls;

  // Loop through companies
  foreach($calls as $call){
  	if($call->caller_number == $caller){
  	$parts = explode('.',$call->utma);
  	$gid = $parts[1];
  	file_put_contents('log.txt', $caller.': '.$gid);
    }
  }
?>
