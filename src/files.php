<html>
    <head>
    <link rel="stylesheet" href="style.css">
    </head>
<body>


<div class="content">
<h1><a href=index.php>Certificate Web Request</a></h1>
<p><table border=0>
    <thead>
        <tr>
            <th colspan="2">Certificate files</th>
        <tr>
    </thead>
    <tbody>
        <tr>
    
        
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
<?php


   if( $_GET["name"] ) {
       $name = $_GET['name'];
   }else {
           echo '<meta http-equiv="refresh" content="0; URL=index.php">';
           }


// Looking for files in files/
$path    = 'files/'.$name.'';
$files = scandir($path);
$files = array_diff(scandir($path), array('.', '..', 'ca'));
foreach($files as $file){
    echo "<tr>";
    echo "<td><a href=files/$name/$file>$file</a><br></th></td>";
    echo "</tr>";
}
?>

<td>
    <form>
        <input type="button" value="Go back" onclick="history.go(-1)">
    </form>
</td>
<tr>
</tbody>
</table></p>
</form>
          </div>





</body>
</html>
