<?php

require __DIR__ . '/vendor/autoload.php';

use Curl\Curl;

$curl = new Curl();
$curl->get('https://od.cdc.gov.tw/eic/Weekly_Age_County_Gender_19CoV.json');

if ($curl->error) {
    echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
} else {
    // echo 'Response:' . "\n";
    // var_dump(json_encode($curl->response));
    // 去除縣市為空值及2021年之前筆數
    $remove_empty = [];
    foreach (json_decode(json_encode($curl->response)) as $key => $val) {
        // var_dump($val->{'縣市'},"<br>");
        if ($val->{'縣市'} != "空值" && $val->{'發病年份'} > 2020) $remove_empty[] = $val;
    }
    echo json_encode($remove_empty);
}
