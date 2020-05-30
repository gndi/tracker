<?php
   define('DB_SERVER', 'localhost:3306');
   define('DB_USERNAME', 'root');
   define('DB_PASSWORD', '');
   define('DB_DATABASE', 'covid1');
   $db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
   $sitelink = "http://localhost/tracker/";
   $salt='Hellocovid';
   $sitename="Covid-19 Managment - Sudan";

   // define()
?>
    
