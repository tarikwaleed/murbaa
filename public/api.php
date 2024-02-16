<?php

echo 'Test API';

$adLicenseNumber = '7100000031';
$advertiserId = '1034758704';
$idType = '1';

$apiUrl = "https://integration-gw.housingapps.sa/nhc/dev/v1/brokerage/AdvertisementValidator?adLicenseNumber={$adLicenseNumber}&advertiserId={$advertiserId}&idType={$idType}";

$headers = array(
    'X-IBM-Client-Id: 13f2b8854a578a2e2868382a7ac82cba',
    'X-IBM-Client-Secret: 3aa859661ebb8626caf771d818e0c990',
    'RefId: 1'
);

$ch = curl_init($apiUrl);


curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);


$response = curl_exec($ch);


if (curl_errno($ch)) {
    echo 'Error: ' . curl_error($ch);
}


curl_close($ch);


print_r($response);
?>
