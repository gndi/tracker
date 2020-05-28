<?php
include('../config.php');
include('./session.php');
include('./header.php');
if ($login_permission == 2 or $login_permission == 0 or $login_permission == 10) {
} else {
  header("location:./index.php");
}
?>


<?php 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // post request
  $case_id = $_POST["case_id"];
  $value = $_POST["result"];
  // if (isset($value)){
  //   if ($value != 0 or $value != 1){
  //     return;
  //   }
  // }

  $q = "update cases set is_positive = $value, is_tested = 1 where id = '$case_id'";
  $res = mysqli_query($db, $q);
  return;
}


?>


<style>
  #monitortable {
    font-size: 10pt;
    background-color: white;
  }

  table {
    font-size: 10pt;
  }

  .ol-popup {
    position: absolute;
    background-color: white;
    -webkit-filter: drop-shadow(0 1px 4px rgba(0, 0, 0, 0.2));
    filter: drop-shadow(0 1px 4px rgba(0, 0, 0, 0.2));
    padding: 15px;
    border-radius: 10px;
    border: 1px solid #cccccc;
    bottom: 12px;
    left: -50px;
    min-width: 200px;
    font-size: 10pt;
  }

  .ol-popup:after,
  .ol-popup:before {
    top: 100%;
    border: solid transparent;
    content: " ";
    height: 0;
    width: 0;
    position: absolute;
    pointer-events: none;
  }

  .ol-popup:after {
    border-top-color: white;
    border-width: 10px;
    left: 48px;
    margin-left: -10px;
  }

  .ol-popup:before {
    border-top-color: #cccccc;
    border-width: 11px;
    left: 48px;
    margin-left: -11px;
  }

  .ol-popup-closer {
    text-decoration: none;
    position: absolute;
    top: 2px;
    right: 8px;
  }

  .ol-popup-closer:after {
    content: "✖";
  }
</style>


<table class="table">
  <thead>
  <tr>
      <th scope="col">#</th>
      <th scope="col">Case ID</th>
      <th scope="col">Set Case Result</th>

    </tr>
  </thead>

<tbody>

<?php
mysqli_query($db, "SET NAMES 'utf8'");
mysqli_query($db, 'SET CHARACTER SET utf8');
$q = "select * from cases where is_tested != 1 order by id";
$res = mysqli_query($db, $q);
$id = "id";
$name = "patient_code";
$ethnicity = "result_id";

   while ($row = mysqli_fetch_array($res)) { 
    echo "<tr>
        <td> ". $row[$id] ." </td>
        <td> ".$row["patient_code"]." </td>
        <td>
        <div class='form-group'>
            <select class='form-control' id='result'>
            <option selected>Carefully set the result</option>
            <option value=0>NEGATIVE RESULT</option>
            <option value=1>POSITIVE RESULT</option>
            </select>
            <button type='submit' class='btn btn-primary mb-2' onClick='submitCase(". $row[$id] .")'>Submit result</button>
        </div>
      </td>
      </tr>"; 
   }
  ?>

</tbody>
</table>

<script>

function submitCase(v) {
  var e = document.getElementById("result");
  var result = e.options[e.selectedIndex].value;
  var data = new FormData();
  data.append("result", result)
  data.append("case_id", v)
  fetch("/admin/lab_result.php", {method: 'POST', body: data}).then(response=>{
    console.log("The response is: ", response);
    window.location.reload();
  })
  
  // window.location.reload();
  
}

</script>

  <?php
  include('./footer.php');
  ?>