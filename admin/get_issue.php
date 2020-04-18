<?php


require_once('../config.php');
include('./session2.php');

function getmanager_name($db,$code){
$q = "SELECT * FROM `users` WHERE id='$code' ";
$result1 =mysqli_query($db,$q);
$row1 = mysqli_fetch_assoc($result1);
$name = $row1['name'];
  return $name;
};

$id =mysqli_real_escape_string($db,$_GET['id']);
mysqli_query($db,"set names utf8");

$q = "SELECT * FROM `issues` WHERE id=$id ;";
$result =mysqli_query($db,$q);

$row = mysqli_fetch_assoc($result);
$date = $row['date'];
$time =$row['time'];
$des = $row['issue_description'];
$img =$row['img'];
$made_by = getmanager_name($db,$row['made_by']);
$id = $row['id'];
<<<<<<< HEAD
$due_date = $row['due_date'];
$is_done = $row['is_done'];
=======
$qid = $row["quarantine_id"];
$issue_id = $row["id"];
$abspath = "admin/" . $img;
$due_date = $row["due_date"];
$is_done = $row["is_done"];
>>>>>>> bee573830c1be63d6d40ebbd217fca46e9e86dcb

echo '<div class="card text-center" style="max-width:500px;">


<img class="card-img-top" src="../'.$img. '" alt="No Image " >
<a href="../'.$abspath.'">Image url</a>
<h5 class="card-title">'.$made_by. '</h5>
  <div class="card-body">
        <p>Issue description</p>: ' . $des . '
        <br><br>
        
        <p>Made by: ' . $made_by . '</p>
        <p>Due date: ' . $due_date . '</p>
        <p>Issue Status: ' . $is_done . '</p>
  </div>
  <div class="card-footer text-muted">
    <p> Issue submitted on: '.$date.' at '.$time.'
  </div>
  <div
</div>';

echo '<div>';

echo '<p>All images related to this issue:</p>';

// echo "The id is: $id";
  $q = "select * from `images` where issue_id=$issue_id";
$result = mysqli_query($db, $q);
while ($row = mysqli_fetch_assoc($result)) {
  $img = $row["img"];
  $abspath = "admin/" . $img;
  echo '<a href="../' . $abspath . '">Image url</a>
  <hr>';
  
}




?>

</div>





