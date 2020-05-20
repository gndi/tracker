<table class="table table-sm table-hover">
  <thead>
    <tr>
      <th scope="col">#ID</th>
      <th scope="col">Name</th>
      <th scope="col">Medical Usage</th>
      <th scope="col">State</th>
      <th scope="col">Readiness Status</th>
      <th scope="col">Building Status</th>
      <th scope="col">Building Type</th>
      <th scope="col">Capacity</th>
      <th scope="col">N.Cases</th>
      <th scope="col">Events</th>
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

    function getlocalityname($db, $code)
    {
      $q = "SELECT * FROM `states` WHERE admin2Pcode='$code' ";
      $result1 = mysqli_query($db, $q);
      $row1 = mysqli_fetch_assoc($result1);
      $name = $row1['admin2Name_en'];
      return $name;
    };

    function getmu($code)
    {
      if ($code == 0) {
        return 'Isolation';
      } elseif ($code == 1) {
        return 'Self isolation';
      }
    };
    function getbs($code)
    {
      if ($code == 0) {
        return 'Approved by the team';
      } elseif ($code == 1) {
        return 'Under Maintenance';
      } elseif ($code == 2) {
        return 'Not visited yet';
      } elseif ($code == 3) {
        return 'etc.';
      }
    };

    function getbt($code)
    {
      // we will need to update these values
      if ($code == 0) {
        return 'Primary';
      } elseif ($code == 1) {
        return 'Secondary';
      } elseif ($code == 2) {
        return 'School Complex';
      } elseif ($code == 3) {
        return 'Others';
      }
    };

    function getrs($code)
    {
      if ($code == 0) {
        return 'Not ready';
      } elseif ($code == 1) {
        return 'Ready needs approval';
      } elseif ($code == 2) {
        return 'Ready';
      };
    };

    function getoa($code)
    {
      if ($code == 0) {
        return 'False';
      } elseif ($code == 1) {
        return 'True';
      };
    };

    $online_thresh = 1;
    $q = mysqli_real_escape_string($db, $_GET['q']);

    $state_ = mysqli_real_escape_string($db, $_GET['state']);
    $loc = mysqli_real_escape_string($db, $_GET['loc']);
    $bld = mysqli_real_escape_string($db, $_GET['building_status']); // building status
    $ow = mysqli_real_escape_string($db, $_GET['owner_acceptance']);
    $mu = mysqli_real_escape_string($db, $_GET['medical_usage']);
    $bt = mysqli_real_escape_string($db, $_GET['building_type']);
    mysqli_query($db, "SET NAMES 'utf8'");
    mysqli_query($db, 'SET CHARACTER SET utf8');


    if ($state_ != "" and $loc != "") {
      $q .= "select * from `hc` where state_ = '$state_' and locality = '$loc'";
    } else {
      $q .= "select * from `hc` where state_ is not null ";
    }

    // possibly we want to get it per state only
    if ($ow != "") {
      $q .= " and owner_acceptance = '$ow' ";
    }  if ($mu != "") {
      $q .= " and medical_usage = '$mu' ";
    }  if ($bt != "") {
      $q .= " and building_type = '$bt' ";
    }  if ($bld != "") {
      $q .= " and building_status = '$bld' ";
    } 

    // print("The query is:\n");
    // print($q);
    $q .= " order by id";
    
    // else if ($state_ == '') {
    //   $q = "SELECT * FROM `hc` WHERE (name LIKE '%$q%' OR info LIKE '%$q%' OR phone LIKE '%$q%' OR phone2 LIKE '%$q%' OR adress LIKE '%$q%')  order by id ";
    // } elseif ($loc == '' ) {
    //   $q = "SELECT * FROM `hc` WHERE ((name LIKE '%$q%' OR info LIKE '%$q%' OR phone LIKE '%$q%' OR phone2 LIKE '%$q%' OR adress LIKE '%$q%') AND (state_ = '$state_' ) ) order by id ";
    // } else {
    //   $q = "SELECT * FROM `hc` WHERE ((name LIKE '%$q%' OR info LIKE '%$q%' OR phone LIKE '%$q%' OR phone2 LIKE '%$q%' OR adress LIKE '%$q%') AND (state_ = '$state_' AND locality = '$loc')) order by id ";
    // }

    $result = mysqli_query($db, $q);
    while ($row = mysqli_fetch_assoc($result)) {

      $lat = $row['lat'];
      $lon = $row['lon'];
      $id = $row['id'];
      $name = $row['name'];
      $info = $row['info'];
      $state = getstatename($db, $row['state_']);
      $power = $row['power'];
      $medical_usage = getmu($row['medical_usage']);
      $building_status = getbs($row['building_status']);
      $readiness_status = getrs($row['readiness_status']);
      $building_type = getbt($row['building_type']);

      //'Site_Type' => $row['Site_Type'];
      $adress = $row['adress'];
      $phone = $row['phone'];
      $phone2 = $row['phone2'];
      // fix me add other acceptance criterion

      $o_a = $row['owner_acceptance'];
      $r_a = $row['resistnce_acceptance'];
      $b_s = $row["building_status"];
      $r_s = $row["readiness_status"];


      $allcount_query = "SELECT count(*) as allcount FROM cases WHERE (hc_id=$id and state<2);";
      $allcount_result = mysqli_query($db, $allcount_query);
      $allcount_fetch = mysqli_fetch_array($allcount_result);
      $allcount = $allcount_fetch['allcount'];

      /* make the checks here for:
        owner acceptance



      */
      // building status == 0 is a bug. It should be 1 for success (for consistency)
      if ($o_a == 1 and $r_a == 1 and $b_s == 1 and $r_s == 0) {

        $icon = 'green.png';
      } elseif ($o_a == 1 or $r_a == 1 or $b_s == 1 or $r_s == 0) {
        $icon = 'yellow.png';
      } else {
        $icon = 'red.png';
      }


      // <a href='del.php?table=hc&id=$id'><img src='../images/delete.png' width='20px' height='20px' /> </a>
      echo "
    <tr>
      <th>$id</th>
      <td>$name</td>
      <td>$medical_usage</td>
      <td>$state</td>
    <td>$readiness_status</td>
    <td>$building_status</td>
    <td>$building_type</td>
    <td>$power</td>
    <td>$allcount</td>
    <td>
    <a href='#' onclick=\" getmodal($id)\"><img src='../images/info.png' width='20px' height='20px' /> </a>
    <a href='#' onclick=\" getmodal3($id)\"><img src='../images/Ok.png' width='20px' height='20px' /> </a>
    <a href='#' onclick=\" getmodal2($id)\"><img src='../images/issues.png' width='20px' height='20px' /> </a>
    <a href='#' onclick= \" map.setView(new ol.View({ center: ol.proj.fromLonLat([$lon,$lat], 'EPSG:3857'), zoom: 15 })); \" > <img src='../images/$icon' width='20px' height='20px' /></a> </td>
    </tr>";


      /*
    <a href='#' class='list-group-item list-group-item-action' onclick= \" map.setView(new ol.View({ center: ol.proj.fromLonLat([$lon,$lat], 'EPSG:3857'), zoom: 18 })); \" > <img src='images/antena32.png' /></a>";
    */
    };



    ?>

  </tbody>
</table>