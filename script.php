<?php

$dir = '/Users/XXX/.gitshots/'.date('Y/m/d');
$file = date('His').'.jpg';

if (false === is_dir($dir)) {
    echo "Create directory {$dir}\n";
    mkdir($dir,0777,true);
}

echo "Taking capture into {$dir}/{$file} \n";

// linux only
pclose(popen("imagesnap -q -w 3 {$dir}/{$file} &> /dev/null &", 'r'));

$channelId = 'XXXXXXXX';

sleep(5);

$slacktoken = "XXXXXXXX-XXXXXXXX-XXXXXXXX-XXXXXXXX-XXXXXXXX";
$header = array();
$header[] = 'Content-Type: multipart/form-data';
$file = new CurlFile("{$dir}/{$file}", 'image/jpg');

$postitems =  array(
    'token' => $slacktoken,
    'channels' => $channelId,
    'file' =>  $file,
    'text' => "コミットしました",
    'title' => "コミットしました",
    'filename' => date('Y/m/d').".jpg"
);
    
$curl = curl_init();
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
curl_setopt($curl, CURLOPT_URL, "https://slack.com/api/files.upload");
curl_setopt($curl, CURLOPT_POST, 1);
curl_setopt($curl, CURLOPT_POSTFIELDS,$postitems);

//Execute curl and store in variable
$data = curl_exec($curl);
echo "sent photo to slack channel \n";
curl_close($curl);
