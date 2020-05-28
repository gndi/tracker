<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="../css/ol.css" type="text/css">
  <!-- The line below is only needed for old environments like Internet Explorer and Android 4.x -->
  <script src="https://cdn.polyfill.io/v2/polyfill.min.js?features=requestAnimationFrame,Element.prototype.classList,URL"></script>
  <script src="../../js/jquery.min.js"></script>
  <script src="../js/ol.js"></script>
  <!-- The line below is only needed for old environments like Internet Explorer and Android 4.x -->
  <script src="https://cdn.polyfill.io/v2/polyfill.min.js?features=requestAnimationFrame,Element.prototype.classList,URL"></script>


  <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

</head>

<body>




  <?php
  include('../../config.php');
  include('../session.php');
  if ($login_permission != 0) {
    header("location:../users.php");
    exit();
  }
  //include('../includes/setting.php');
  $massage = "";
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_escape_string($db, $_POST['username']);
    $password = md5($salt . mysqli_escape_string($db, $_POST['password']));
    $name = mysqli_escape_string($db, $_POST['fname']);
    $about = mysqli_escape_string($db, $_POST['about']);
    $phone = mysqli_escape_string($db, $_POST['phone']);
    $permission = mysqli_escape_string($db, $_POST['permission']);
    $location_id = mysqli_escape_string($db, $_POST['location_id']);


    $profile = "profiles/profile.jpg";

    $q = "INSERT INTO users (username, name,password,permission,session,online,about,phone, locality_id)
SELECT '$username', '$name','$password',$permission,'$password',0,'$about','$phone', '$location_id' FROM DUAL WHERE NOT EXISTS (
    SELECT username FROM users WHERE username = '$username'
) ;";

    // $q = "insert into users(username, name,password,permission,about,phone, locality_id, online) values ('$username', '$name', '$password', '$permission', '$about', '$phone', '$location_id', 0)";
    $res = mysqli_query($db, $q) or trigger_error("Query Failed! SQL: $sql - Error: " . mysqli_error($db), E_USER_ERROR);

    if ($res) {

      $massage .= " Added Successfully";
      $massage = "
    
    <div class='alert alert-success alert-dismissible fade show' role='alert'>
  $massage
  <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
    <span aria-hidden='true'>&times;</span>
  </button>
</div>
    
    ";
      header("location:../users.php");
    } else {
      echo "
        <div class='alert alert-danger alert-dismissible fade show' role='alert'>
  Error Happened While Submit.
  <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
    <span aria-hidden='true'>&times;</span>
  </button>
</div>
        
        
        ";
    }
  }


/*
  <option value='SD01003'  selected>Bahri</option>
  <option value='SD01001' >Jebel Awlia</option>
  <option value='SD01005' >Karrari</option>
  <option value='SD01007' >Khartoum</option>
  <option value='SD01004' >Sharg An Neel</option>
  <option value='SD01002' >Um Bada</option>
  <option value='SD01006' >Um Durman</option>

*/



  ?>
  <div class="container">
    <div class="row">
      <?php echo $massage; ?>

    </div>

    <div class="row">
      Add New Office User

    </div>

    <div class="row">

      <form method="post">
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="inputEmail4">Username</label>
            <input type="text" class="form-control" id="inputEmail4" name='username' placeholder="username">
          </div>
          <div class="form-group col-md-6">
            <label for="inputPassword4">Password</label>
            <input type="password" name='password' class="form-control" id="inputPassword4" placeholder="Password">
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="fname">Name</label>
            <input type="text" class="form-control" id="fname" name='fname' placeholder="First Name">
          </div>
          <div class="form-group col-md-6">
            <label for="lastname">About</label>
            <input type="text" name='about' class="form-control" id="lastname" placeholder="About">
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="lastname">Phone</label>
            <input type="text" name='phone' class="form-control" id="lastname" placeholder="Phone">
          </div>
          <div class="form-group col-md-6">
            <label for="permission">User Type</label>
            <select class="form-control" name="permission" id="permission">
              <option value='0' selected>Admin</option>
              <option value='1'>Qurantine</option>
              <option value='2'>Cases</option>
              <option value='3'>Ambulance</option>
              <option value='4'>Project Manager</option>
              <option value='5'>Team Member</option>
              <option value='10'>Labs</option>
              <option value='6'>Locality administrator</option>
              <option value='7'>Call center admin</option>
              <option value='12'>Sample Collector</option>
            </select>
          </div>

        
      
          <!-- Localities -->
          <div class="form-group col-md-6">
            <label for="location_id">User locality (important for locality admin)</label>
            <select class="form-control" name="location_id" id="location_id">
            
              <option value='0' selected>Select a city</option>
              <option value='7'>Khartoum</option>
              <option value='3'>Bahri</option>
              <option value='1'>Jebel Awalia</option>
              <option value='5'>Karrari</option>
              <option value='4'>Sharg Elneel</option>
              <option value='2'>Umbadda</option>
              <option value='6'>Um Durman</option>
            </select>
          </div>


        </div>

        <button type="submit" class="btn btn-primary">ADD </button>
      </form>

    </div>
  </div>
</body>

</html>