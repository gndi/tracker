<?php
include('../config.php');
include('./session.php');
include('./header.php');
if ($login_permission == 2 or $login_permission == 0 or $login_permission == 10) {
} else {
  header("location:./index.php");
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
      <th scope="col">Name</th>
      <th scope="col">Physician name</th>
      <th scope="col">Collection date</th>
      <th scope="col">Patient code</th>
    </tr>
  </thead>

<tbody>

<?php
mysqli_query($db, "SET NAMES 'utf8'");
mysqli_query($db, 'SET CHARACTER SET utf8');
$q = "select * from cases";
$res = mysqli_query($db, $q);
$id = "id";
$name = "name";
$ethnicity = "ethnicity";
$physician_name = "physician_name";
$collection_date = "collection_date";
$patient_code = "patient_code";

   while ($row = mysqli_fetch_array($res)) { 
    echo "<tr>
        <td> ". $row[$id] ." </td>
        <td>" . $row[$name] . "</td>
        <td>" . $row[$ethnicity] . "</td>
        <td>" . $row[$physician_name] . "</td>
        <td>" . $row[$collection_date] . "</td>
        <td>" . $row[$patient_code] . "</td>
      </tr>"; 
   }
  ?>

</tbody>

  <?php
  include('./footer.php');
  ?>