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

$q = "SELECT * FROM `issues` WHERE quarantine_id=$id and is_done = false order by id desc limit 0,10  ;";
$result =mysqli_query($db,$q);
echo '<div class="accordion" id="accordionExample">';
while($row = mysqli_fetch_assoc($result)){
$date = $row['date'];
$time =$row['time'];
$des = $row['issue_description'];
$img =$row['img'];
$made_by = $row['made_by'];
$id = $row['id'];
$is_done = $row['is_done'];
$due_date = $row['due_date'];
echo '<div class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>' . $des . '</p>

        <img src="../' . $img . '" width="auto" height="auto" style="max-width:100%;" />
        <br><br>
        
        <p>Made by: ' . $made_by . '</p>
        <p>Due date: ' . $due_date . '</p>
        <p>Status: ' . $is_done . '</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>


        <button class="btn btn-primary" type="button" onclick="updateissue(' . $id . ')">
        Mark as complete
        </button>
        </div>
    </div>
  </div>
</div>';


}


?>

</div>





