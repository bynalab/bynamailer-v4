<?php

$url = "https://madoublec.com/";
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://safebrowsing.googleapis.com/v4/threatMatches:find?key=AIzaSyBVDjLa1fez7mD8PNk679a41xBSwB8z2tU",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => '{
        "client": {
        "clientId":      "bynalab",
        "clientVersion": "1.5.2"
    },
        "threatInfo": {
        "threatTypes":      ["MALWARE", "SOCIAL_ENGINEERING"],
        "platformTypes":    ["WINDOWS"],
        "threatEntryTypes": ["URL"],
        "threatEntries": { "url": "https://bynalab.com/" }
}
  }',
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "content-type: application/json",
    "postman-token: b05b8d34-85f2-49cf-0f8e-03686a71e4e9"
  ),
));

$response = curl_exec($curl);
$json_data = json_decode($response);
$err = curl_error($curl);
$resp = "";

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {

if(count($json_data->matches) == 0){
    $resp = 0;
} else {
    $resp = 1;
}

}