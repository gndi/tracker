<table class="table table-sm table-hover">
  <thead>
    <tr>
      <th scope="col">#ID</th>
      <th scope="col">Name</th>
      <th scope="col">Information</th>
      <th scope="col">State</th>
      <th scope="col">Address</th>
      <th scope="col">Phone</th>
      <th scope="col">A. Phone</th>
      <th scope="col">Score</th>
      <th scope="col">Location</th>
    </tr>
  </thead>
  <tbody>
    <?php
    require_once('../config.php');
    include('./session2.php');
    $online_thresh = 1;
    $q = mysqli_real_escape_string($db, $_GET['q']);

    mysqli_query($db, "set names utf8");
    $permission = $_GET["user_type"];

  
    if ($permission == 6) {
      $locality_id = $_GET["user_locality"];
      // $locality = 
      /*
        <option value='SD01003'  selected>Bahri</option><option value='SD01001' >Jebel Awlia</option><option value='SD01005' >Karrari</option><option value='SD01007' >Khartoum</option><option value='SD01004' >Sharg An Neel</option><option value='SD01002' >Um Bada</option><option value='SD01006' >Um Durman</option>

      */
      $cities = array(1 => "SD01001", 2 => "SD01002", 3 => "SD01003", 4 => "SD01004", 5 => "SD01005", 6 => "SD01006", 7 => "SD01007");
      $locality = $cities[$locality_id] ?? "*";
      error_log($locality);

      $q = "SELECT * FROM `notifications` WHERE locality = '$locality' order by id ";
    } else {
      $q = "SELECT * FROM `notifications` order by id ";
    }

    error_log($q);
    $result = mysqli_query($db, $q) or trigger_error("Query Failed! SQL: $sql - Error: " . mysqli_error($db), E_USER_ERROR);;
    while ($row = mysqli_fetch_assoc($result)) {

      $lat = $row['lat'];
      $lon = $row['lon'];
      $id = $row['id'];
      $name = $row['name'];
      $info = $row['info'];
      $state = $row['state'];
      //'Site_Type' => $row['Site_Type'];
      $adress = $row['adress'];
      $phone = $row['phone'];
      $phone2 = $row['phone2'];
      $type = $row['type'];
      $p1 = $row['p1'];
      $p2 = $row['p2'];
      // $p3= $row['p3'];
      $p4 = $row['p4'];
      $p5 = $row['p5'];
      $p6 = $row['p6'];
      $p7 = $row['p7'];
      $p8 = $row['p8'];
      $p9 = $row['p9'];
      $p10 = $row['p10'];
      $child = $row['child'];


      if ($p1 == 1) {
        $score = 5;
        $score += $p5;
        $score += $p6;
        $score += $p7;
        $score += $p8;
      } else {
        if ($child == 1) {
          $score = $p2 * 3;
          // $score=$p3*2;
          $score = $p4 * 1;
          $score += $p5;
          $score += $p6;
          $score += $p7;
        } else {
          $score = $p2 * 3;
          // $score=$p3*2;
          $score = $p4 * 1;
          $score += $p5 * 2;
          $score += $p6 * 2;
          $score += $p7 * 2;
          $score += $p8;
          $score += $p9;
          $score += $p10;
        }
      };
      if ($score >= 3) {
        $icon = 'off.png';
      } else {
        $icon = 'on.png';
      };
      $q = "SELECT * FROM `hc` ";
      $h = '';
      $re = mysqli_query($db, $q);
      $h .= "<a class='dropdown-item' href='./not_to_case.php?hc=-1&id=" . $id . "'>Send Case to : Home Isolation</a>";
      while ($row = mysqli_fetch_assoc($re)) {
        $hc_n = $row['name'];
        $hc_id = $row['id'];
        $h .= "<a class='dropdown-item' href='./not_to_case.php?hc=" . $hc_id . "&id=" . $id . "'>Send Case to : " . $hc_n . "</a>";
      }

      echo "
    <tr>
      <th>$id</th>
      <td>$name</td>
      <td>$info</td>
      <td>$state</td>
    <td>$adress</td>
    <td><a href='tel:$phone'>$phone</a></td>
    <td><a href='tel:$phone2'>$phone2</a></td>
    <td>$score</td>
    <td><a href='#' onclick= \" map.setView(new ol.View({ center: ol.proj.fromLonLat([$lon,$lat], 'EPSG:3857'), zoom: 15 })); \" > <img src='../images/$icon' width='20px' height='20px' /></a> <a href='del.php?table=nots&id=$id'><img src='../images/delete.png' width='20px' height='20px' /> </a> 



  <button class='btn btn-secondary dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
    
  </button>
  <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
    " . $h . "
  </div>

    </td>
    </tr>";


      /*
    <a href='#' class='list-group-item list-group-item-action' onclick= \" map.setView(new ol.View({ center: ol.proj.fromLonLat([$lon,$lat], 'EPSG:3857'), zoom: 18 })); \" > <img src='images/antena32.png' /></a>";
    */
    };



    ?>

  </tbody>
</table>