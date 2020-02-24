<?php

$cookie_file ='/tmp/cookie.txt';

if (! file_exists($cookie_file) || ! is_writable($cookie_file))
{
    echo 'Cookie file missing or not writable.';
    exit;
}

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://my.ovoenergy.com/api/v2/auth/login",
  CURLOPT_COOKIEJAR => $cookie_file,
  CURLOPT_COOKIEFILE => $cookie_file,
  CURLOPT_COOKIESESSION => true,
  CURLOPT_HEADER=> 1,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "{\"password\":\"*****\",\"rememberMe\":true,\"username\":\"*****\"}",
  CURLOPT_HTTPHEADER => array(
    "accept: application/json, text/plain, */*",
    "cache-control: no-cache",
    "content-type: application/json;charset=UTF-8",
    "origin: https://my.ovoenergy.com",
    "postman-token: de767d8a-1a43-0627-f243-6219bc725175",
    "sec-fetch-dest: empty",
    "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.87 Safari/537.36"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);


$ch = curl_init();
$date=date("Y-m-d", strtotime( '-1 days' ) );
curl_setopt($ch, CURLOPT_COOKIEJAR,  $cookie_file); 
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
curl_setopt($ch, CURLOPT_COOKIESESSION, true );
//login to OVO online and visit https://smartpaym.ovoenergy.com/api/customer-and-account-ids to get account ID for below
curl_setopt($ch, CURLOPT_URL ,"https://smartpaym.ovoenergy.com/api/energy-usage/half-hourly/*****?date=YYYY-MM-DD");  
curl_setopt($ch, CURLOPT_RETURNTRANSFER , true);
curl_setopt($ch, CURLOPT_ENCODING , "");
curl_setopt($ch, CURLOPT_MAXREDIRS , 10);
curl_setopt($ch, CURLOPT_TIMEOUT , 30);
curl_setopt($ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch, CURLOPT_HTTPHEADER , 		array(
    "accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9",
    "cache-control: no-cache",
    "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.87 Safari/537.36")
);

$response = curl_exec($ch);
$err = curl_error($ch);

curl_close($ch);

if ($err) {
  echo "cURL Error #:" . $err;
} else {	
	$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
	$header = substr($response, 0, $header_size);
	$body = substr($response, $header_size);
	$arr = json_decode($body, true);

	$ElectricityRecords = $arr['electricity']['data'];
	$GasRecords = $arr['gas']['data'];
	
    //MySqlAddress, USername, Password, Database
	$link = mysqli_connect("*****", "*****", "*****", "*****");
	// Check connection
	if($link === false){
		die("ERROR: Could not connect. " . mysqli_connect_error());
	}
	 
	foreach ($ElectricityRecords as $value) {
		$value["interval"]["end"] = substr(str_replace("T"," ",$value["interval"]["end"]),0,-4);

		$sql = "INSERT INTO Electricity (dateandtime, consumption, unit) VALUES ('".$value["interval"]["end"]."', '".$value["consumption"]."', 'kWh')";
		if(mysqli_query($link, $sql)){
			echo "Record inserted successfully.";
		} else{
			echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
		}
	}
	
	foreach ($GasRecords as $value) {
		$value["interval"]["end"] = substr(str_replace("T"," ",$value["interval"]["end"]),0,-4);
		
		$sql = "INSERT INTO Gas (dateandtime, consumption, unit) VALUES ('".$value["interval"]["end"]."', '".$value["consumption"]."', 'kWh')";
		if(mysqli_query($link, $sql)){
			echo "Record inserted successfully.";
		} else{
			echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
		}		
	}
	mysqli_close($link);
}
?>
