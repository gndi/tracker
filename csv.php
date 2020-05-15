<?php
include './config.php';
include './admin/session.php';
error_log($login_permission);

if ($login_permission == 0 or $login_permission == 6) {

}else {
    header("location:./index.php");

}

$service = $_GET["service"];
$q = "select * from `$service` order by id";

if ($login_permission == 6) {
    if (isset($user_locality)) {
        $cities = array(1 => "SD01001", 2 => "SD01002", 3 => "SD01003", 4 => "SD01004", 5 => "SD01005", 6 => "SD01006", 7 => "SD01007");
        $locality = $cities[$user_locality] ?? "*";
        error_log($locality);
        $q = "select * from `$service` where locality = '$locality' order by id ";
    }
}
// make sure if $service doesn't not work to fallback...


error_log($q);

$d = mktime(11, 14, 54, 8, 12, 2014);
$filename = date('Y-m-d-His') . '.csv';
$headers = array();
$res = mysqli_query($db, $q) or trigger_error("Query Failed! SQL: $sql - Error: " . mysqli_error($db), E_USER_ERROR);;

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