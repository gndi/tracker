<?php
   define('DB_SERVER', 'localhost:3306');
   define('DB_USERNAME', 'root');
   define('DB_PASSWORD', '');
   define('DB_DATABASE', 'covid');
   $db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
<<<<<<< HEAD
   $sitelink = "http://localhost/covid/";
=======
   $sitelink = "http://127.0.0.1:8004/";
>>>>>>> bee573830c1be63d6d40ebbd217fca46e9e86dcb
   $salt='Hellocovid';
   $sitename="Covid-19 Managment - Sudan";
?>
    
