<table class="table table-sm table-hover">
  <thead>
    <tr>
      <th scope="col">#ID</th>
      <th scope="col">Name</th>
      <th scope="col">Information</th>
      <th scope="col">State</th>
      <th scope="col">Adress</th>
      <th scope="col">Phone</th>
      <th scope="col">A. Phone</th>
      <th scope="col">Type</th>
      <th scope="col">Location</th>
    </tr>
  </thead>
  <tbody>
    <?php

    require_once('../config.php');
    include('./session2.php');

    function getstatename($db, $code)
    {
      $q = "SELECT * FROM `states` WHERE admin1Pcode='$code' ";
      $result1 = mysqli_query($db, $q);
      $row1 = mysqli_fetch_assoc($result1);
      $name = $row1['admin1Name_en'];
      return $name;
    };
    $online_thresh = 1;
    $q = mysqli_real_escape_string($db, $_GET['q']);
    $state_ = mysqli_real_escape_string($db, $_GET['state']);
    $loc = mysqli_real_escape_string($db, $_GET['loc']);
    mysqli_query($db, "set names utf8");
    if (isset($_GET["locality_id"])) {
      $queryLocality = "";
      $cities = array(1 => "SD01001", 2 => "SD01002", 3 => "SD01003", 4 => "SD01004", 5 => "SD01005", 6 => "SD01006", 7 => "SD01007");
      $loca_ = $cities[$_GET["locality_id"]] ?? "*";
      error_log($loca_);
      $q = "SELECT * FROM `cases` where locality = '$loca_' order by id ";

    } elseif ($state_ == '' and $loc == '') {
      $q = "SELECT * FROM `cases` order by id ";
    } else {
      $q = "SELECT * FROM `cases` where state_ = '$state_' AND locality = '$loc' order by id ";
    }

    $result = mysqli_query($db, $q) or trigger_error("Query Failed! SQL: $sql - Error: " . mysqli_error($db), E_USER_ERROR);
    while ($row = mysqli_fetch_assoc($result)) {

      $lat = $row['lat'];
      $lon = $row['lon'];
      $id = $row['id'];
      $name = $row['name'];
      $info = $row['info'];
      $state_ = getstatename($db, $row['state_']);
      $state = $row['state'];
      //'Site_Type' => $row['Site_Type'];
      $adress = $row['adress'];
      $phone = $row['phone'];
      $phone2 = $row['phone2'];
      $type = $row['type'];
      if ($state == -1) {
        $icon = 'ambulans.png';
      } elseif ($state == 0) {
        $icon = 'wait.png';
      } elseif ($state == 1) {
        $icon = 'cases.png';
      } elseif ($state == 2) {
        $icon = 'ok.png';
      } elseif ($state == 3) {
        $icon = 'dead.png';
      };

      echo "
    <tr>
      <th>$id</th>
      <td>$name</td>
      <td>$info</td>
      <td>$state_</td>
    <td>$adress</td>
    <td><a href='tel:$phone'>$phone</a></td>
    <td><a href='tel:$phone2'>$phone2</a></td>
    <td>$type</td>
    <td>
    <a href='#' onclick= \" map.setView(new ol.View({ center: ol.proj.fromLonLat([$lon,$lat], 'EPSG:3857'), zoom: 15 })); \" ><img src='../images/$icon' width='20px' height='20px' /></a>
     <a href='del.php?table=cases&id=$id'><img src='../images/delete.png' width='20px' height='20px' /> </a></td>
    </tr>";


      /*
    <a href='#' class='list-group-item list-group-item-action' onclick= \" map.setView(new ol.View({ center: ol.proj.fromLonLat([$lon,$lat], 'EPSG:3857'), zoom: 18 })); \" > <img src='images/antena32.png' /></a>";
    */
    };



    ?>

  </tbody>
</table>