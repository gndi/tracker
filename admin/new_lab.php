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
if ($login_permission == 1 or $login_permission == 0 or $login_permission == 10) {
} else {
  header("location:./index.php");
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = htmlspecialchars(mysqli_real_escape_string($db, $_POST['name']));
  $patient_name = htmlspecialchars(mysqli_real_escape_string($db, $_POST['patient_name']));
  $gender = htmlspecialchars(mysqli_real_escape_string($db, $_POST['gender']));
  $patient_age = htmlspecialchars(mysqli_real_escape_string($db, $_POST['patient_age']));
  $ethnicity = htmlspecialchars(mysqli_real_escape_string($db, $_POST['ethnicity']));
  $phone_number = htmlspecialchars(mysqli_real_escape_string($db, $_POST['phone_number']));
  $patient_address = htmlspecialchars(mysqli_real_escape_string($db, $_POST['patient_address']));
  $case_type = htmlspecialchars(mysqli_real_escape_string($db, $_POST['case_type']));

  $to_lab = htmlspecialchars(mysqli_real_escape_string($db, $_POST['to_lab']));
  $with_cases = htmlspecialchars(mysqli_real_escape_string($db, $_POST['contact_with_cases']));
  $arrival_date = htmlspecialchars(mysqli_real_escape_string($db, $_POST['patient_arrival_date']));
  $additional_comments = htmlspecialchars(mysqli_real_escape_string($db, $_POST['additional_comments']));

  $physician_name = htmlspecialchars(mysqli_real_escape_string($db, $_POST['physician_name']));
  $physician_mobile = htmlspecialchars(mysqli_real_escape_string($db, $_POST['physician_mobile']));
  $sample_type = htmlspecialchars(mysqli_real_escape_string($db, $_POST['sample_type']));
  $test_reason = htmlspecialchars(mysqli_real_escape_string($db, $_POST['test_reason']));
  // date("Y-m-d H:i:s"
  $sample_day = date("Y-m-d H:i:s", strtotime(htmlspecialchars(mysqli_real_escape_string($db, $_POST['sample_day']))));
  $collection_date = date("Y-m-d H:i:s", strtotime(htmlspecialchars(mysqli_real_escape_string($db, $_POST['collection_date']))));
  $accordance = htmlspecialchars(mysqli_real_escape_string($db, $_POST['accordance']));
  $patient_condition = htmlspecialchars(mysqli_real_escape_string($db, $_POST['patient_condition']));
  $recent_travel = htmlspecialchars(mysqli_real_escape_string($db, $_POST['recent_travel']));
  // leave this for other form
  /*$reference_number = htmlspecialchars(mysqli_real_escape_string($db, $_POST['reference_number']));
  $result = htmlspecialchars(mysqli_real_escape_string($db, $_POST['result']));
  $created_at = date("Y_m_d", strtotime(htmlspecialchars(mysqli_real_escape_string($db, $_POST['created_at']))));
*/

  mysqli_query($db, "SET NAMES 'utf8'");
  mysqli_query($db, 'SET CHARACTER SET utf8');

  $sql = "INSERT INTO `labs` (`name`,`patient_name`,`gender`,`patient_age`,`ethnicity`,`phone_number`,`patient_address`,`case_type`,`physician_name`,`physician_mobile`,`sample_type`,`test_reason`,`sample_day`,`collection_date`,`accordance`,`patient_condition`,`clinical_picture`,`recent_travel`,`comorobidities`,`additional_comments`, `to_lab`, `patient_arrival_date`, `contact_with_case`) VALUES ('$name','$patient_name','$gender','$patient_age','$ethnicity','$phone_number','$patient_address','$case_type','$physician_name','$physician_mobile','$sample_type','$test_reason','$sample_day','$collection_date','$accordance','$patient_condition','$clinical_picture','$recent_travel','$comorobidities','$additional_comments','$to_lab', '$arrival_date', '$with_cases')";
  $res = mysqli_query($db, $sql);
  $count = mysqli_num_rows($res);
  
  // insert into clinical picture

  $clinical_picture = $_POST['clinical_picture'];
  $comorobidities = $_POST['comorobidities'];

  $com = createArray(6, $comorobidities);
  $comVal = implode(',', $com);

  $valuearr = createArray(12, $clinical_picture);
  $values = implode(',', $valuearr);
  $lab_id =
  $in = join(',', array_fill(0, count($values), '?'));
  $sql = "insert into clinical_picture (`asymptomatic`,`urti`,`pneumonina`,`fever` ,`cough`,`sob`,`treatment`,`supportive`,`compassionate`,`clinical_trial`,`no_treatment`,`other`) values ($values)";

  $res = mysqli_query($db, $sql) or trigger_error("Query Failed! SQL: $sql - Error: " . mysqli_error($db), E_USER_ERROR);

  // comorobidities
  $sql = "insert into comorobidities(`lab_id`, `dm`, `htn`, `asthma`, `cardiac`, `renal`, `other`) values(10, $comVal)";
  $res = mysqli_query($db, $sql) or trigger_error("Query Failed! SQL: $sql - Error: " . mysqli_error($db), E_USER_ERROR);
  
  if ($res) {
    $success = true;
    $num_rows = mysqli_num_rows($res);

  } else {
    $success = false;
  }

  // append to the other tables
  
};

function arrayString($array) {
  return join(',', $array);
}

function createArray($length, $members) {
  $res = array_fill(0, $length, 0);
  for ($i = 0; $i <= $length; $i++){
    if ( in_array($i, $members)){
      $res[$i] = 1;
    }
  }
  return $res;
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
      <form method="post" accept-charset="utf-8">
        <div class="form-group col-md-12">
          <label for="exampleFormControlInput1">Name (hosptial, lab, or other)</label>
          <input required type="text" class="form-control" name="name" id="exampleFormControlInput1" placeholder="Quarantine Name">
        </div>

        <div class="form-group col-md-12">
          <label for="patient-name">Patient name</label>
          <input required type="text" class="form-control" name="patient_name" id="patient-name" placeholder="Patient Name">
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
          <input required type="number" class="form-control" name="patient_age" id="patient-age" placeholder="Patient age">
        </div>

        <div class="form-group col-md-12">
          <label for="ethnicity">Ethnicity</label>
          <input required type="number" class="form-control" name="ethnicity" id="ethnicity" placeholder="Patient ethnicity (free text)">
        </div>

        <div class="form-group col-md-12">
          <label for="phone-number">Phone number</label>
          <input required type="text" class="form-control" name="phone_number" id="phone-number" placeholder="Phone number">
        </div>

        <div class="form-group col-md-12">
          <label for="patient-address">Patient address</label>
          <input required type="text" class="form-control" name="patient_address" id="patient-address" placeholder="Patient address">
        </div>


        <div class="form-group col-md-12">
          <label for="case-type">Case category</label>
          <select class="form-control" name="case_type" id="case-type">
            <option selected>Select option</option>
            <option value='0'>Suspected case</option>
            <option value='1'>Probable case</option>
          </select>
        </div>

        <div class="form-group col-md-12">
          <label for="physician-name">Physician name</label>
          <input required type="text" class="form-control" name="physician_name" id="physician-name" placeholder="Physician name">
        </div>

        <div class="form-group col-md-12">
          <label for="physician-mobile">Physician mobile</label>
          <input required type="text" class="form-control" name="physician_mobile" id="physician-mobile" placeholder="Physician mobile">
        </div>
        <hr>

        <!--Specimen information-->

        <div class="form-group col-md-12">
          <label for="sample-type">Sample type</label>
          <select class="form-control" name="sample_type" id="sample-type">
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
          <select class="form-control" name="test_reason" id="test-reason">
            <option value='-1' selected>Select option</option>
            <option value='0'>Screening</option>
            <option value='1'>Diagnosis</option>
            <option value='2'>Follow-up</option>
          </select>
        </div>

        <div class="form-group col-md-12">
          <label for="sample-day">Sampling day</label>
          <select class="form-control" name="sample_day" id="sample-day">
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
          <input required type="date" class="form-control" name="collection_date" id="collection-date" placeholder="Collection date">
        </div>

        <div class="form-group col-md-12">
          <label for="date-to-lab">Date sent to laboratory</label>
          <input required type="date" class="form-control" name="to_lab" id="date-to-lab" placeholder="Date to lab">
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
          <select class="form-control" name="patient_condition" id="patient-condition">
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
          <select class="form-control" name="clinical_picture[]" id="clinical-picture" multiple>
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
          <label for="recent-travel">Recent travel</label>
          <select class="form-control" name="recent_travel" id="recent-travel">
            <option selected>Select option</option>
            <option value='0'>Local</option>
            <option value='1'>Internal</option>
            <option value='2'>NO</option>
          </select>
        </div>

        <div class="form-group col-md-12">
          <label for="patient-arrival-date">Patient arrival date</label>
          <input required type="date" class="form-control" name="patient_arrival_date" id="patient-arrival-date" placeholder="">
        </div>

        <div class="form-group col-md-12">
          <label for="contact-with-cases">Contact with cases</label>
          <select class="form-control" name="contact_with_cases" id="contact-with-cases">
            <option selected>Select option</option>
            <option value='0'>Yes</option>
            <option value='1'>No</option>
            <option value='2'>Unknown</option>
          </select>
        </div>

        <div class="form-group col-md-12">
          <label for="comorobidities">Underlying comorobidities</label>
          <select class="form-control" name="comorobidities[]" id="comorobidities" multiple>
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
          <label for="additional-comments">Additional comments</label>
          <textarea class="form-control" name="additional_comments" id="additional-comments">
          </textarea>
        </div>


        <!-- <div class="form-group col-md-12">
          <label for="reference_number">Reference number</label>
          <input required type="text" class="form-control" name="reference_number" id="reference_number" placeholder="">
        </div>

        <div class="form-group col-md-12">
          <label for="result">Result</label>
          <input required type="text" class="form-control" name="result" id="result" placeholder="">
        </div>

        <div class="form-group col-md-12">
          <label for="date">Created at</label>
          <input required type="date" class="form-control" name="created_at" id="date" placeholder="">
        </div> -->

        <?php

        if (isset($success)) {
          if ($success) {
            echo "<div class='alert alert-success' role='alert'>
                            Task Sent Successfully .
                                </div>";
          } else {
            echo "<div class='alert alert-danger' role='alert'>
                            Failed To Send Task .
                                </div>";
          }
        }



        ?>

        <div class="col-md-12">
          <button type="submit" class="btn btn-primary col-md-12">Save lab result</button>
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
    function fillform() {
      id = document.getElementById("additional-comments")
      if (id.value == "") {
        id.value = ""
      }
    }

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