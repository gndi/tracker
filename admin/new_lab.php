<?php
include('../config.php');
include('./session.php');
include('./header.php');
//include('./includes/setting.php');
function saveimage($fff)
{
  $file_name = $fff;
  if (isset($file_name)) {
  } else {
    return 'No Image';
  }


  //$scr = base64_decode($image);
  $filename = md5(date('Y-m-d H:i:s:u'));
  $filename .= uniqid('img');
  $link = "qurantines_images/" . $filename;
  $link .= ".jpg";
  $img_quality = 100;

  $im = imagecreatefromstring(file_get_contents($file_name));
  $im_w = imagesx($im);
  $im_h = imagesy($im);
  $tn = imagecreatetruecolor($im_w, $im_h);
  imagecopyresampled($tn, $im, 0, 0, 0, 0, $im_w, $im_h, $im_w, $im_h);
  imagejpeg($tn, $link, $img_quality);
  //file_put_contents($link, $scr);

  return 'admin/' . $link;
};

// TODO make it visible only for medical staff
if ($login_permission == 1 or $login_permission == 0) {
} else {
  header("location:./index.php");
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = htmlspecialchars(mysqli_real_escape_string($db, $_POST['name']));
  $info = htmlspecialchars(mysqli_real_escape_string($db, $_POST['info']));
  $lon = htmlspecialchars(mysqli_real_escape_string($db, $_POST['lon']));
  $lat = htmlspecialchars(mysqli_real_escape_string($db, $_POST['lat']));
  $adress = htmlspecialchars(mysqli_real_escape_string($db, $_POST['adress']));
  $power = htmlspecialchars(mysqli_real_escape_string($db, $_POST['power']));
  $phone = htmlspecialchars(mysqli_real_escape_string($db, $_POST['phone']));
  $phone2 = htmlspecialchars(mysqli_real_escape_string($db, $_POST['phone2']));
  $state = 0;
  $owner_name = htmlspecialchars(mysqli_real_escape_string($db, $_POST['owner_name']));
  $owner_contact = htmlspecialchars(mysqli_real_escape_string($db, $_POST['owner_contact']));
  $project_manager = htmlspecialchars(mysqli_real_escape_string($db, $_POST['project_manager']));
  $stakeholders = htmlspecialchars(mysqli_real_escape_string($db, $_POST['stakeholders']));
  $i_teams = htmlspecialchars(mysqli_real_escape_string($db, $_POST['i_teams']));
  $r_t_contacts = htmlspecialchars(mysqli_real_escape_string($db, $_POST['r_t_contacts']));
  $medical_usage = htmlspecialchars(mysqli_real_escape_string($db, $_POST['medical_usage']));
  $building_status = htmlspecialchars(mysqli_real_escape_string($db, $_POST['building_status']));
  $owner_acceptance = htmlspecialchars(mysqli_real_escape_string($db, $_POST['owner_acceptance']));
  $resistnce_acceptance = htmlspecialchars(mysqli_real_escape_string($db, $_POST['resistnce_acceptance']));
  $readiness_status = htmlspecialchars(mysqli_real_escape_string($db, $_POST['readiness_status']));
  $building_type = htmlspecialchars(mysqli_real_escape_string($db, $_POST['building_type']));
  $init_budget = htmlspecialchars(mysqli_real_escape_string($db, $_POST['init_budget']));
  $e_f_date = date("Y-m-d", strtotime(htmlspecialchars(mysqli_real_escape_string($db, $_POST['e_f_date']))));
  $i_date = date("Y-m-d", strtotime(htmlspecialchars(mysqli_real_escape_string($db, $_POST['i_date']))));
  $state_ = htmlspecialchars(mysqli_real_escape_string($db, $_POST['state_']));
  $locality = htmlspecialchars(mysqli_real_escape_string($db, $_POST['locality']));
  if (isset($_POST['myFile'])) {
    $img = saveimage($_FILES['myFile']['tmp_name']);
  } else {
    $img = 'No image';
  }


  if (isset($name) and isset($lon) and isset($info) and isset($lat) and isset($power) and isset($phone) and isset($phone2) and  isset($state) and isset($adress)) {

    mysqli_query($db, "SET NAMES 'utf8'");
    mysqli_query($db, 'SET CHARACTER SET utf8');
    //$sql = "INSERT INTO `tasks` (`location`, `f_userid`, `userid`, `title`, `info`, `datetime`, `state`) VALUES (GeomFromText('POINT($lon $lat)'), $f_user , $u_user, '$title', '$info', now(),  0)" ;
    $sql = "INSERT INTO `hc` ( `name`, `info`, `power`, `phone`, `phone2`, `lon`, `lat`, `adress`, `state`, `owner_name`, `owner_contact`, `project_manager`, `stakeholders`, `i_teams`, `r_t_contacts`, `medical_usage`, `building_status`, `owner_acceptance`, `resistnce_acceptance`, `readiness_status`, `building_type`, `init_budget`, `e_f_date`, `i_date`, `state_`, `locality`,`img`) VALUES ( '$name', '$info', $power, '$phone', '$phone2', $lon, $lat, '$adress', 0, '$owner_name', '$owner_contact', '$project_manager', '$stakeholders', '$i_teams', '$r_t_contacts', $medical_usage, $building_status, $owner_acceptance, $resistnce_acceptance, $readiness_status, $building_type, $init_budget, '$e_f_date', '$i_date', '$state_', '$locality','$img')";


    $res = mysqli_query($db, $sql);
    if ($res) {
      $success = true;
    } else {
      $success = false;
    }
  };
}

$long = '';
$latg = '';
$userg = 0;



if (isset($_GET['site'])) {

  $site = mysqli_real_escape_string($db, $_GET['site']);
  $q = "SELECT X(location) as lon , Y(location) as lat From monitor WHERE id=$site ";
  $re = mysqli_query($db, $q);
  $row = mysqli_fetch_assoc($re);
  $long = $row['lon'];
  $latg = $row['lat'];
};
if (isset($_GET['user'])) {
  $userg = mysqli_real_escape_string($db, $_GET['user']);
}




?>
<style>
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

<div class="row">
  <div class="col-sm-6" style="border-style: solid;border-width: 2px;border-color:#007BFF;">

    <div style="overflow-x:auto;height:450px;" id="form">
      <form method="post" enctype="multipart/form-data" accept-charset="utf-8">
        <div class="form-group col-md-12">
          <label for="exampleFormControlInput1">Name (hosptial, lab, or other)</label>
          <input required type="text" class="form-control" name="name" id="exampleFormControlInput1" placeholder="Quarantine Name">
        </div>

        <div class="form-group col-md-12">
          <label for="patient-name">Patient name</label>
          <input required type="text" class="form-control" name="patient-name" id="patient-name" placeholder="Quarantine Name">
        </div>

        <div class="form-group col-md-12">
          <label for="gender">Patient gender</label>
          <select class="form-control" name="gender" id="gender">
            <option selected>Other</option>
            <option value='0'>Male</option>
            <option value='1'>Female</option>
          </select>
        </div>

        <div class="form-group col-md-12">
          <label for="patient-age">Age</label>
          <input required type="number" class="form-control" name="patient-age" id="patient-age" placeholder="Quarantine Adress">
        </div>

        <div class="form-group col-md-12">
          <label for="ethnicity">Ethnicity</label>
          <input required type="text" class="form-control" name="ethnicity" id="ethnicity" placeholder="Quarantine Adress">
        </div>

        <div class="form-group col-md-12">
          <label for="phone-number">Phone number</label>
          <input required type="text" class="form-control" name="phone-number" id="phone-number" placeholder="Quarantine Adress">
        </div>

        <div class="form-group col-md-12">
          <label for="patient-address">Patient address</label>
          <input required type="text" class="form-control" name="patient-address" id="patient-address" placeholder="Quarantine Adress">
        </div>


        <div class="form-group col-md-12">
          <label for="case-type">Case category</label>
          <select class="form-control" name="case-type" id="case-type">
            <option selected>Select option</option>
            <option value='0'>Suspected case</option>
            <option value='1'>Probable case</option>
          </select>
        </div>

        <div class="form-group col-md-12">
          <label for="physician-name">Physician name</label>
          <input required type="number" class="form-control" name="physician-name" id="physician-name" placeholder="Quarantine Adress">
        </div>

        <div class="form-group col-md-12">
          <label for="physician-mobile">Physician mobile</label>
          <input required type="number" class="form-control" name="physician-mobile" id="physician-mobile" placeholder="Quarantine Adress">
        </div>
        <hr>

        <!--Specimen information-->

        <div class="form-group col-md-12">
          <label for="sample-type">Sample type</label>
          <select class="form-control" name="sample-type" id="sample-type">
            <option selected>Select option</option>
            <option value='0'>NS</option>
            <option value='1'>TS</option>
            <option value='2'>BAL</option>
            <option value='3'>Sputum</option>
            <option value='4'>Blood/EDTA</option>
            <option value='5'>Other</option>
          </select>
        </div>

        <div class="form-group col-md-12">
          <label for="test-reason">Reason for testing</label>
          <select class="form-control" name="test-reason" id="test-reason">
            <option selected>Select option</option>
            <option value='0'>Screening</option>
            <option value='1'>Diagnosis</option>
            <option value='2'>Follow-up</option>
          </select>
        </div>

        <div class="form-group col-md-12">
          <label for="sample-day">Sampling day</label>
          <select class="form-control" name="sample-day" id="sample-day">
            <option selected>Select option</option>
            <option value='0'>Day 1</option>
            <option value='1'>Day 5 - 7</option>
            <option value='2'>Day 14</option>
            <option value='3'>Day 21</option>
            <option value='4'>Other</option>
          </select>
        </div>

        <div class="form-group col-md-12">
          <label for="collection-date">Collection date</label>
          <input required type="date" class="form-control" name="collection-date" id="collection-date" placeholder="Quarantine Power">
        </div>

        <div class="form-group col-md-12">
          <label for="date-to-lab">Date sent to laboratory</label>
          <input required type="date" class="form-control" name="date-to-lab" id="date-to-lab" placeholder="Quarantine Power">
        </div>

        <div class="form-group col-md-12">
          <label for="accordance">All samples must be sent in accordance with CAT B transport</label>
          <select class="form-control" name="accordance" id="accordance">
            <option selected>Select option</option>
            <option value='0'>Yes</option>
            <option value='1'>No</option>
            <option value='2'>Other</option>
            <option value='3'>Leaking</option>
            <option value='4'>Speciment rejected</option>
          </select>
        </div>

        <hr>

        <!--Clinical details-->

        <div class="form-group col-md-12">
          <label for="patient-condition">Patient current condition</label>
          <select class="form-control" name="patient-condition" id="patient-condition">
            <option selected>Select option</option>
            <option value='0'>Home</option>
            <option value='1'>Hospital</option>
            <option value='2'>ICU</option>
            <option value='3'>ECMO</option>
            <option value='4'>Deceased</option>
          </select>
        </div>

        <div class="form-group col-md-12">
          <label for="clinical-picture">Clinical picture</label>
          <select class="form-control" name="clinical-picture" id="clinical-picture">
            <option selected>Select option</option>
            <option value='0'>Asymptomatic</option>
            <option value='1'>URTI</option>
            <option value='2'>ILI</option>
            <option value='3'>Pneumonia</option>
            <option value='4'>Fever</option>
            <option value='5'>Cough</option>
            <option value='6'>SOB</option>
            <option value='7'>Treatment</option>
            <option value='8'>Supportive</option>
            <option value='9'>Compassionate</option>
            <option value='10'>Clinical trial</option>
            <option value='11'>No treatment</option>
            <option value='12'>Other</option>
          </select>
        </div>

        <div class="form-group col-md-12">
          <label for="recent-travel">Sampling day</label>
          <select class="form-control" name="recent-travel" id="recent-travel">
            <option selected>Select option</option>
            <option value='0'>Local</option>
            <option value='1'>Internal</option>
            <option value='2'>NO</option>
          </select>
        </div>

        <div class="form-group col-md-12">
          <label for="patient-arrival-date">Patient arrival date</label>
          <input required type="date" class="form-control" name="patient-arrival-date" id="patient-arrival-date" placeholder="">
        </div>

        <div class="form-group col-md-12">
          <label for="contact-with-cases">Contact with cases</label>
          <select class="form-control" name="contact-with-cases" id="contact-with-cases">
            <option selected>Select option</option>
            <option value='0'>Yes</option>
            <option value='1'>No</option>
            <option value='2'>Unknown</option>
          </select>
        </div>

        <div class="form-group col-md-12">
          <label for="comorobidities">Underlying comorobidities</label>
          <select class="form-control" name="comorobidities" id="comorobidities">
            <option selected>Select option</option>
            <option value='0'>DM</option>
            <option value='1'>HTN</option>
            <option value='2'>Asthma / COPD</option>
            <option value='3'>Cardiac</option>
            <option value='4'>Renal</option>
            <option value='5'>Other</option>
          </select>
        </div>

        <div class="form-group col-md-12">
          <label for="additional-comments">Owner Name</label>
          <textarea class="form-control" name="additional-comments" id="additional-comments">
          </textarea>

        </div>


        <div class="form-group col-md-12">
          <label for="reference_number">Reference number</label>
          <input required type="text" class="form-control" name="reference_number" id="reference_number" placeholder="">
        </div>

        <div class="form-group col-md-12">
          <label for="result">Result</label>
          <input required type="text" class="form-control" name="result" id="result" placeholder="">
        </div>

        <div class="form-group col-md-12">
          <label for="date">Date</label>
          <input type="date" class="form-control" name="date" id="date" placeholder="">
        </div>

        <div class="col-md-12">
          <button type="submit" class="btn btn-primary col-md-12">Save Case</button>
        </div>
      </form>




    </div>

  </div>




  <div class="col-sm-6">
    <div id="map" class="map" width="100%" height="500px" style="border-style: solid;border-width: 2px;border-color:#007BFF;height:500px;"></div>
    <div id="popup" class="ol-popup">
      <a href="#" id="popup-closer" class="ol-popup-closer"></a>
      <div id="popup-content"></div>
    </div>



  </div>







  <script>
    function getlocality() {
      var loc = document.getElementById('locality');
      var st = document.getElementById('state_');
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          var myObj = JSON.parse(this.responseText);
          var html_data = '';

          for (var i = 0; i < myObj['localitis'].length; i++) {
            var newobj = myObj['localitis'][i];
            var code = newobj['CODE'];
            var name = newobj['name'];

            html_data += '<option value="' + code + '">' + name + '</option>';
          };
          loc.innerHTML = html_data;

        }
      };
      xmlhttp.open("GET", "../get_locality.php?id=" + st.options[st.selectedIndex].value, true);
      xmlhttp.send();

    }



    var q = '';
    document.getElementById("new_hc").classList.add("active");
    var marker1 = new ol.style.Icon({
      anchor: [0.5, 1],
      src: '../images/on.png'
    });
    var stage = new ol.style.Icon({
      anchor: [0.5, 1],
      src: '../images/stage.png'
    });
    var marker2 = new ol.style.Icon({
      anchor: [0.5, 1],
      src: '../images/off.png'
    });


    var style = new ol.style.Style({
      image: new ol.style.Icon({
        anchor: [0.5, 1],
        src: 'images/antena20.png'
      }),
      text: new ol.style.Text({
        font: '12px Calibri,sans-serif',
        fill: new ol.style.Fill({
          color: '#000'
        }),
        stroke: new ol.style.Stroke({
          color: '#fff',
          width: 3
        })
      })
    });
    var container = document.getElementById('popup');

    var content = document.getElementById('popup-content');
    var closer = document.getElementById('popup-closer');
    var overlay = new ol.Overlay( /** @type {olx.OverlayOptions} */ ({
      element: container,
      autoPan: true,
      autoPanAnimation: {
        duration: 250
      }
    }));
    var stage = new ol.style.Icon({
      anchor: [0.5, 1],
      src: '../images/hospital.png'
    });

    var style = new ol.style.Style({
      image: new ol.style.Icon({
        anchor: [0.5, 1],
        src: '../images/marker.png'
      }),
      text: new ol.style.Text({
        font: '12px Calibri,sans-serif',
        fill: new ol.style.Fill({
          color: '#000'
        }),
        stroke: new ol.style.Stroke({
          color: '#fff',
          width: 3
        })
      })
    });

    var vectorLayer = new ol.layer.Vector({
      source: new ol.source.Vector({
        url: '<?php echo $sitelink; ?>gis/hc.php',
        format: new ol.format.GeoJSON()
      }),
      name: 'hospitals',
      style: function(feature, resolution) {
        style.getText().setText(resolution < 50 ? feature.get('current') : '');

        style.setImage(stage);

        return style;
      }
    });

    var map = new ol.Map({
      layers: [
        new ol.layer.Tile({
          source: new ol.source.OSM()
        }),
        vectorLayer
      ],
      overlays: [overlay],
      target: 'map',
      view: new ol.View({
        center: ol.proj.fromLonLat(<?php echo "[ 32.547948, 15.609359]"; ?>, 'EPSG:3857'),
        zoom: <?php echo 10; ?>
      }),
      controls: ol.control.defaults().extend([
        new ol.control.ZoomSlider(), new ol.control.FullScreen(), new ol.control.Attribution()
      ])
    });

    //Reload Part
    <?php
    if (isset($_GET['site'])) {
      echo "map.setView(new ol.View({ center: ol.proj.fromLonLat([32,15], 'EPSG:3857'), zoom: 15 }));";
    }



    ?>

    var myVar = setInterval(myTimer, 5000);

    function myTimer() {
      vectorLayer.setSource(new ol.source.Vector({
        url: '<?php echo $sitelink; ?>gis/hc.php',
        format: new ol.format.GeoJSON()
      }));


    }


    closer.onclick = function() {
      overlay.setPosition(undefined);
      closer.blur();
      return false;
    };
    map.on('singleclick', function(evt) {
      var coordinate = evt.coordinate;

      var is_not_on_feature = true;
      map.forEachFeatureAtPixel(evt.pixel, function(feature, layer) {
        //do something
        is_not_on_feature = false;
        var coord = feature.getGeometry().getCoordinates();

        var proj = ol.proj.transform(
          coord, 'EPSG:3857', 'EPSG:4326');
        document.getElementById('lon').value = proj[0];
        document.getElementById('lat').value = proj[1];
        content.innerHTML = '<center><p>Quarantine  Name : ' + feature.get('name') + '</p><br />' +
          '<p> Quarantine  information : ' + feature.get('info') + '</p><br /><h5><p>Quarantine Power </p></h5><p> ' + feature.get('power') + '</p><h5> <br /><a href=del.php?table=hc&id=' + feature.get('id') + ' ><img src="../images/delete.png" /></a></center>';

        overlay.setPosition(coordinate);



      });
      if (is_not_on_feature) {
        var hdms = ol.coordinate.toStringHDMS(ol.proj.transform(
          coordinate, 'EPSG:3857', 'EPSG:4326'));
        var proj = ol.proj.transform(
          coordinate, 'EPSG:3857', 'EPSG:4326');
        document.getElementById('lon').value = proj[0];
        document.getElementById('lat').value = proj[1];
        content.innerHTML = 'Coordinates <br />' + hdms;
        overlay.setPosition(coordinate);

      }

    });
  </script>

  <?php
  include('./footer.php');
  ?>