<html>
    <head>
    <title>Certificate Web Request</title>
    <link rel="icon" href="favico.jpeg">
    <link rel="stylesheet" href="style.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<style>
    .bs-example{
        margin: 5;
        color: black;
    }
    .accordion .fa{
        margin-right: 0.5rem;
      	font-size: 15;
      	font-weight: bold;
        position: relative;
    	top: 2px;
        color: black;
    }
    .a {
        color: white;
    }
</style>
<script>
    $(document).ready(function(){
        // Add down arrow icon for collapse element which is open by default
        $(".collapse.show").each(function(){
        	$(this).prev(".card-header").find(".fa").addClass("fa-angle-down").removeClass("fa-angle-right");
        });
        
        // Toggle right and down arrow icon on show hide of collapse element
        $(".collapse").on('show.bs.collapse', function(){
        	$(this).prev(".card-header").find(".fa").removeClass("fa-angle-right").addClass("fa-angle-down");
        }).on('hide.bs.collapse', function(){
        	$(this).prev(".card-header").find(".fa").removeClass("fa-angle-down").addClass("fa-angle-right");
        });
    });
</script>
    </head>
<body>

<?php
  if( $_GET["name"] ) {
    $name = $_GET['name'];
}else {
        echo '<meta http-equiv="refresh" content="0; URL=index.php">';
        }
?>

<div class="content">
<h1><a class="ca" href=index.php><?php echo $name;?></a></h1>
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


 
?>
<hr class="header1">
<br>        
<h2 class="mb-0"><button type="button" class="btn-ca btn-link-ca" data-toggle="collapse" data-target="#collapseOne"><i class="fa fa-angle-right"></i> Show Base64-encoded</button></h2>
<?php

// Looking for files in files/
$path    = 'files/'.$name.'';
$files = scandir($path);
$files = array_diff(scandir($path), array('.', '..', 'ca'));
foreach($files as $file){
    if (strpos($file, "pw") == false) {
    echo "<tr>";
    echo "<td><a class=\"ca\"href=files/$name/$file>$file</a><br></td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td>";
    
    if (strpos($file, "p12") == false) {

    ?>
        <div class="bs-example">
            <div class="accordion" id="accordionExample">
                <div class="card-ca">
                    <div class="card-header-ca" id="headingOne">
                        
                    </div>
                    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                        <div class="card-body-ca">
                             <?php echo nl2br( file_get_contents( "files/$name/$file" ) ); ?>
                        </div>
                    </div>
                </div>

    <?php
    };
    };
    echo "</td>";
    echo "</tr>";


};
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
