<?php
include '../config.php';

$service = $_GET["service"];
// make sure if $service doesn't not work to fallback...
$q = "select * from `$service` order by id";

$d = mktime(11, 14, 54, 8, 12, 2014);
$filename = date('Y-m-d-His') . '.csv';
$headers = array();
$res = mysqli_query($db, $q);

while ($fieldinfo = mysqli_fetch_field($res)) {
    $headers[] = $fieldinfo->name;
}

$fp = fopen('php://output', 'w');
fputcsv($fp, $headers);

while ($row = mysqli_fetch_assoc($res)) {
    
    if ($fp) {
        header('Content-Type: text/csv');
        header("Content-Disposition: attachment; filename=$filename");
        header('Pragma: no-cache');
        header('Expires: 0');
        // fputcsv($fp, $headers);
        // print_r($row);
        fputcsv($fp, array_values($row));
        
    }
    // echo "$row";
};


/*
if (!$result) die('Couldn\'t fetch records'); // this won't work
$num_fields = mysqli_num_fields($result);

$headers = array();
while ($fieldinfo = mysqli_fetch_field($result)) {
    $headers[] = $fieldinfo->name;
}

$fp = fopen('php://output', 'w');
if ($fp && $result) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="export.csv"');
    header('Pragma: no-cache');
    header('Expires: 0');
    fputcsv($fp, $headers);
    while ($row = $result->fetch_array(MYSQLI_NUM)) {
        fputcsv($fp, array_values($row));
    }
    die;
}

*/