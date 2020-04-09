<table class="table table-sm table-hover">
  <thead>
    <tr>
      <th scope="col">#ID</th>
      <th scope="col">Name</th>
      
      
        <th scope="col">Adress</th>
        <th scope="col">Phone</th>
        <th scope="col">A. Phone</th>
        
        <th scope="col">Location</th>
    </tr>
  </thead>
  <tbody>
<?php

require_once('../config.php');
include('./session2.php');

function getstatename($db,$code){
$q = "SELECT * FROM `states` WHERE admin1Pcode='$code' ";
$result1 =mysqli_query($db,$q);
$row1 = mysqli_fetch_assoc($result1);
$name = $row1['admin1Name_en'];
  return $name;
};
$online_thresh =1;
$q =mysqli_real_escape_string($db,$_GET['q']);
 $state_ =mysqli_real_escape_string($db,$_GET['state']);
 $loc =mysqli_real_escape_string($db,$_GET['loc']);
mysqli_query($db,"set names utf8");
if($state_==''){
  $q = "SELECT * FROM `cases` WHERE (name LIKE '%$q%' OR info LIKE '%$q%' OR phone LIKE '%$q%' OR phone2 LIKE '%$q%' OR adress LIKE '%$q%') AND (state = -1) order by id ";
}elseif($loc==''){
  $q = "SELECT * FROM `cases` WHERE ((name LIKE '%$q%' OR info LIKE '%$q%' OR phone LIKE '%$q%' OR phone2 LIKE '%$q%' OR adress LIKE '%$q%') AND (state_ = '$state_' ) ) AND (state = -1) order by id";

}else{
  $q = "SELECT * FROM `cases` WHERE ((name LIKE '%$q%' OR info LIKE '%$q%' OR phone LIKE '%$q%' OR phone2 LIKE '%$q%' OR adress LIKE '%$q%') AND (state_ = '$state_' AND locality = '$loc') ) AND (state = -1) order by id ";
}

$result =mysqli_query($db,$q);
while($row = mysqli_fetch_assoc($result)) {
   
            $lat =$row['lat'];
            $lon =$row['lon'];
            $id = $row['id'];
			$name = $row['name'];
          $info = $row['info'];
          $state_ = getstatename($db,$row['state_']);
          $state=$row['state'];
          //'Site_Type' => $row['Site_Type'];
          $adress = $row['adress'];
          $phone = $row['phone'];
           $phone2 = $row['phone2'];
           $type = $row['type'];
         if($state==-1){
          $icon='ambulans.png';

          }elseif($state==0){
            $icon='wait.png';
          }elseif($state==1){
            $icon='cases.png';
          }elseif($state==2){
            $icon='ok.png';
          }elseif($state==3){
            $icon='dead.png';
          };
          
     echo "
    <tr>
      <th>$id</th>
      <td>$name</td>
     
    <td>$adress</td>
    <td>$phone</td>
    <td>$phone2</td>
    
    <td><a href='#' onclick= \" map.setView(new ol.View({ center: ol.proj.fromLonLat([$lon,$lat], 'EPSG:3857'), zoom: 15 })); \" > <img src='../images/$icon' width='20px' height='20px' /></a> <a href='http://maps.google.com/maps?daddr=$lat,$lon'><img src='../images/on.png' width='20px' height='20px' /> </a></td>
    </tr>";
    
    
    /*
    <a href='#' class='list-group-item list-group-item-action' onclick= \" map.setView(new ol.View({ center: ol.proj.fromLonLat([$lon,$lat], 'EPSG:3857'), zoom: 18 })); \" > <img src='images/antena32.png' /></a>";
    */
   
};


 
?>

      </tbody>
</table>