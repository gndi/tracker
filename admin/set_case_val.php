<?php
require_once('../config.php');

require_once('./session.php');


mysqli_query($db,"set names utf8");
$result =mysqli_query($db,"SHOW COLUMNS FROM cases ");

while($row = mysqli_fetch_assoc($result)) {
$name = $row['Field'];
$id =mysqli_real_escape_string($db,$_GET['id']);
$el = array();
if (isset($_GET[$name])){
	$el[$name] = mysqli_real_escape_string($db,$_GET[$name]);
	if(isset($_GET['text'])){
		$q = "UPDATE `cases` SET `$name`='".$el[$name]."' WHERE id=$id " ;
	}else{
		$q = "UPDATE `cases` SET `$name`=".$el[$name]." WHERE id=$id " ;
	}
mysqli_query($db,$q) or trigger_error("Query Failed! SQL: $sql - Error: " . mysqli_error($db), E_USER_ERROR);

}


}

header("location:./cases_list.php");

?>