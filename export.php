
<?php

//php_spreadsheet_export.php
include ('config.php');
include('./admin/session.php');

if(isset($_GET['service'])){
  $service =mysqli_real_escape_string($db,htmlspecialchars($_GET['service'], ENT_QUOTES, 'UTF-8'));


$sql = "SELECT * FROM $service ;";
mysqli_query($db, "SET NAMES 'utf8'");
$res = mysqli_query($db, $sql);

while ($fieldinfo = mysqli_fetch_field($res)) {
    $headers[] = $fieldinfo->name;
}



};

?>
<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8">

     <title>Data Report for <?php echo $service; ?></title>
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
   </head>
   <body>
     <div class="container">
      <br />
      <h3 align="center">Export Data Report for <?php echo $service; ?></h3>
      <br />
        <div class="panel panel-default">
          <div class="panel-heading">
            <form method="post">
              <div class="row">
                <div class="col-md-6"><?php echo "You Can Download Data"; ?></div>
                
              </div>
            </form>
          </div>
          <div class="panel-body">
          <div class="table-responsive">
           <table class="table table-striped table-bordered">
                <tr>
                  <?php
                  foreach ($headers as $key => $value) {
                    echo "<th>$value</th>";

                      };



                  ?>
                  
                
                </tr>
                <?php
                mysqli_query($db, "SET NAMES 'utf8'");
                $res = mysqli_query($db, $sql);

                while ($row = mysqli_fetch_assoc($res)) 
                  {
                    echo "<tr>";
                    foreach ($row as $key => $value) {
                      echo "<td>$value</td>";
                    }
                    echo "</tr>";
                    
                  };

                
                ?>

              </table>
          </div>
          </div>
        </div>
     </div>
      <br />
      <br />
      <script src="xlsx.core.min.js"></script>
      <script src="Blob.js"></script>
      <script src="FileSaver.js"></script>
      <script src="tableexport.js"></script>
      <script type="text/javascript">
        //new TableExport(document.getElementsByTagName("table"));

// OR simply

/* Defaults */

TableExport(document.getElementsByTagName("table"), {
  headers: true,                      // (Boolean), display table headers (th or td elements) in the <thead>, (default: true)
  footers: true,                      // (Boolean), display table footers (th or td elements) in the <tfoot>, (default: false)
  formats: ["xlsx", "csv", "txt"],    // (String[]), filetype(s) for the export, (default: ['xlsx', 'csv', 'txt'])
  filename: "id",                     // (id, String), filename for the downloaded file, (default: 'id')
  bootstrap: false,                   // (Boolean), style buttons using bootstrap, (default: true)
  exportButtons: true,                // (Boolean), automatically generate the built-in export buttons for each of the specified formats (default: true)
  position: "bottom",                 // (top, bottom), position of the caption element relative to table, (default: 'bottom')
  ignoreRows: null,                   // (Number, Number[]), row indices to exclude from the exported file(s) (default: null)
  ignoreCols: null,                   // (Number, Number[]), column indices to exclude from the exported file(s) (default: null)
  trimWhitespace: true,               // (Boolean), remove all leading/trailing newlines, spaces, and tabs from cell text in the exported file(s) (default: false)
  RTL: false,                         // (Boolean), set direction of the worksheet to right-to-left (default: false)
  sheetname: "id"                     // (id, String), sheet name for the exported spreadsheet, (default: 'id')
});


document.getElementsByClassName('xlsx')[0].className="btn btn-primary btn-sm";
document.getElementsByClassName('csv')[0].className="btn btn-primary btn-sm"
document.getElementsByClassName('txt')[0].className="btn btn-primary btn-sm"

// OR using jQuery

//$("table").tableExport();
        




      </script>





     <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
  </body>
</html>

