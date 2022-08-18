<html>
    <head>
    <link rel="stylesheet" href="style.css">
    </head>
<body>


<div class="content">
<h1><a href=index.php>Certificate Web Request</a></h1>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">




    <table class="tg" width="100%">
<thead>
  <tr>
    <td>    
        <p><table border=0 class="tg">
    <tbody>
		<tr>
		</tr>
  		<tr>
    		<th class="tg-0lax">CN</th>
		<th class="tg-0lax"><input type="text" name="CN"></th>	
  		</tr>
	        <tr>
    		<th class="tg-0lax">Password</th>
    		<th class="tg-0lax"><input type="text" name="password"></th>
  		</tr>
  		<tr>
    		<th class="tg-0lax"><input type="submit" value="Send Request"></th>
    		<th class="tg-0lax"><input type="checkbox" name="p12">Create client cert and convert to .p12?<br></th>
  		</tr>

    </tbody>
    </table></p>
</td>
</form>
<?php
$CAnow = date("Y-m-d");
$CAexpire = exec('sudo openssl x509 -in files/ca/ca.pem -noout -enddate');
$CAregexp = '/[a-zA-Z]{3}\s+[0-9].*[0-9]{4}/';
preg_match($CAregexp, $CAexpire, $CAmatches);
$CAexpiredate = date('Y-m-d', strtotime($CAmatches[0]));

$CAtxt = file_get_contents("/var/www/html/files/ca/ca.txt");
$CAC = file_get_contents("/var/www/html/files/ca/C.txt");
$CAO = file_get_contents("/var/www/html/files/ca/O.txt");
$CAKEY_renew = file_get_contents("/var/www/html/files/ca/CA_KEY.txt");




if (isset($_POST['carenew'])){
	exec ('openssl x509 -x509toreq -in /var/www/html/files/ca/ca.pem -signkey /var/www/html/files/ca/ca.key -out /var/www/html/files/ca/new-ca.csr -passin pass:'.$CAKEY_renew.'');
	exec ('echo | openssl x509 -req -days 1095 -in /var/www/html/files/ca/new-ca.csr -signkey /var/www/html/files/ca/ca.key -out /var/www/html/files/ca/ca-new.pem -passin pass:'.$CAKEY_renew.'');
	exec ('cp /var/www/html/files/ca/ca-new.pem /var/www/html/files/ca.pem');
	exec ('cp /var/www/html/files/ca/ca-new.pem /var/www/html/files/ca/ca.pem');
	}


?>


    <td><p><table class="tg" align=center border=0>
    <thead>
        <tr>
	<th align="center" colspan="3"><form action="files/ca.pem"><input type="submit" value="Download CA certificate" /></form><form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>"><input type='submit' name='carenew' value='Renew CA' onclick="return confirm('Are you sure you want to renew CA?')"> <?php echo " " .$CAexpiredate; ?></td></form></th>
        <tr>
        </tr>
            <th colspan="3">Certificate files</th>
        <tr>
    </thead>
    <tbody>
        
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
<?php

// Looking for files in files/
$path    = 'files/';
$files = scandir($path);
$files = array_diff(scandir($path), array('.', '..', 'ca', 'ca.pem'));
foreach($files as $file){
    $now = date("Y-m-d");
    $warning = date('Y-m-d', strtotime($now. ' + 60 days'));
    $expire = exec('sudo openssl x509 -in files/'.$file.'/'.$file.'.crt -noout -enddate');
    $regexp = '/[a-zA-Z]{3}\s+[0-9].*[0-9]{4}/';
    preg_match($regexp, $expire, $matches);
    $expiredate = date('Y-m-d', strtotime($matches[0]));
    if($now > $expiredate) {


    echo "<tr>";
    echo "<td><a href=files.php?name=$file>$file</a><br></th></td>";
    echo "<th style='color: red;'>$expiredate</th>";
    }elseif($warning > $expiredate) {
    echo "<tr>";
    echo "<td><a href=files.php?name=$file>$file</a><br></th></td>";
    echo "<th style='color: yellow;'>$expiredate</th>";
    }else{ 

    echo "<tr>";
    echo "<td><a href=files.php?name=$file>$file</a><br></th></td>";
    echo "<th>$expiredate</th>";
}




    ?>
            <th><input type="checkbox" name="fileid" value="<?php echo $file; ?>"></th>
    <?php
    echo "</tr>";
}

// Check if delid is set
if (isset($_POST['Delete'])){
        $fileid = $_POST['fileid'];
        if(!empty($_POST['fileid'])) {

                $regexp = '/-[a-zA-Z]+\.[a-zA-Z]+/';
                $trimmedfile = preg_replace($regexp, '', $fileid);
//              Remove file from disk
                exec('rm -rf /var/www/html/files/'.$fileid.'');
                echo '<meta http-equiv="refresh" content="0; URL=index.php">';
        }
    } 


if(isset($_POST['Renew'])){


        $fileid = $_POST['fileid'];
        if(!empty($_POST['fileid'])){
            $CACA = file_get_contents("/var/www/html/files/ca/CA_NAME.txt");
            $CAKEY = file_get_contents("/var/www/html/files/ca/CA_KEY.txt");
            $p12_path = 'files/'.$fileid.'/'.$fileid.'.p12';
            if(file_exists($p12_path))
            {
             
                $p12_pw = file_get_contents('/var/www/html/files/'.$fileid.'/'.$fileid.'.pw');
                exec('cp client_cert_san_ext.conf '.$fileid.'.san.ext');
                exec('echo "DNS.1 = '.$fileid.'" >> '.$fileid.'.san.ext');
                exec('sudo openssl req -new -key /var/www/html/files/'.$fileid.'/'.$fileid.'.key -out /var/www/html/files/'.$fileid.'/'.$fileid.'.new.csr -subj "/CN='.$fileid.'"');
                exec('sudo openssl x509 -req -in /var/www/html/files/'.$fileid.'/'.$fileid.'.new.csr -CA /var/www/html/files/ca/ca.pem -CAkey /var/www/html/files/ca/ca.key -CAcreateserial -out /var/www/html/files/'.$fileid.'/'.$fileid.'.crt -days 365 -sha256 -passin pass:'.$CAKEY.' -extfile '.$fileid.'.san.ext');
                exec('rm -f /var/www/html/'.$fileid.'.san.ext');
                exec('sudo openssl pkcs12 -export -out /var/www/html/files/'.$fileid.'/'.$fileid.'.p12 -in /var/www/html/files/'.$fileid.'/'.$fileid.'.crt -inkey /var/www/html/files/'.$fileid.'/'.$fileid.'.key -passout pass:'.$p12_pw.'');
                echo '<meta http-equiv="refresh" content="0; URL=index.php">';
                
            }
            else 
            {

            
                exec('cp server_cert_san_ext.conf '.$fileid.'.san.ext');
                exec('echo "DNS.1 = '.$fileid.'" >> '.$fileid.'.san.ext');
                exec('sudo openssl req -new -key /var/www/html/files/'.$fileid.'/'.$fileid.'.key -out /var/www/html/files/'.$fileid.'/'.$fileid.'.new.csr -subj "/CN='.$fileid.'"');
                exec('sudo openssl x509 -req -in /var/www/html/files/'.$fileid.'/'.$fileid.'.new.csr -CA /var/www/html/files/ca/ca.pem -CAkey /var/www/html/files/ca/ca.key -CAcreateserial -out /var/www/html/files/'.$fileid.'/'.$fileid.'.crt -days 365 -sha256 -passin pass:'.$CAKEY.' -extfile '.$fileid.'.san.ext');
                exec('rm -f /var/www/html/'.$fileid.'.san.ext');
                echo '<meta http-equiv="refresh" content="0; URL=index.php">';
            }
        }
}



?>

<td></td>
<?php 
// Show delete button if there is a file in files/
if(!empty($file)) {
echo "<td colspan='2'><input type='submit' name='Delete' value='Delete'> <input type='submit' name='Renew' value='Renew'></td>";
}
?>
<tr>
</tbody>
</table></p>
</td>
  </tr>
</thead>
</table>








    </form>


          </div>




<?php


$CACA = file_get_contents("/var/www/html/files/ca/CA_NAME.txt");
$CAKEY = file_get_contents("/var/www/html/files/ca/CA_KEY.txt");
// Check if CN is set
if (isset($_POST['CN']) ){
	$CN = $_POST['CN'];
	$pw = $_POST['password'];
        // If CN=empty return echo
        if (empty($CN) || empty($pw)) {
        ?>
            <script>
            alert('CN or PW cannot be empty');
            </script>
        <?php
        } else {
        // Check if p12 is checked
        if(!empty($_POST['p12'])) {
            // Check if password is set when p12 is checked
            if(!empty($_POST['password'])) {

			
			// Create SAN
            exec('cp client_cert_san_ext.conf '.$CN.'.san.ext');
            exec('echo "DNS.1 = '.$CN.'" >> '.$CN.'.san.ext');
			// Create CSR and sign with CA
            exec('sudo mkdir files/'.$CN.'');
			exec('sudo chown www-data:www-data files/'.$CN.'');
			exec('sudo openssl genrsa -out /var/www/html/files/'.$CN.'/'.$CN.'.key 2048');
            exec('sudo openssl req -new -key /var/www/html/files/'.$CN.'/'.$CN.'.key -out /var/www/html/files/'.$CN.'/'.$CN.'.csr -subj "/CN='.$CN.'"');
			exec('sudo openssl x509 -req -in /var/www/html/files/'.$CN.'/'.$CN.'.csr -CA /var/www/html/files/ca/ca.pem -CAkey /var/www/html/files/ca/ca.key -CAcreateserial -out /var/www/html/files/'.$CN.'/'.$CN.'.crt -days 365 -sha256 -passin pass:'.$CAKEY.' -extfile '.$CN.'.san.ext');
            file_put_contents('files/'.$CN.'/'.$CN.'.pw', ''.$pw.'');
		    sleep(2);

                    // Convert .pem files to .p12
                    exec('sudo openssl pkcs12 -export -out /var/www/html/files/'.$CN.'/'.$CN.'.p12 -in /var/www/html/files/'.$CN.'/'.$CN.'.crt -inkey /var/www/html/files/'.$CN.'/'.$CN.'.key -passout pass:'.$pw.'');
                    sleep(2);
                    // Change ownership of created files
                    exec('sudo chown www-data:www-data /var/www/html/files/'.$CN.'/'.$CN.'.key');
		            exec('sudo chown www-data:www-data /var/www/html/files/'.$CN.'/'.$CN.'.crt');  
		            exec('sudo chown www-data:www-data /var/www/html/files/'.$CN.'/'.$CN.'.csr'); 
                    exec('sudo chown www-data:www-data /var/www//html/files/'.$CN.'/'.$CN.'.p12');
                    // Change permission on created files
                    exec('sudo chmod 666 /var/www/html/files/'.$CN.'/'.$CN.'.p12');
		            exec('sudo chmod 666 /var/www/html/files/'.$CN.'/'.$CN.'.key');
		            exec('sudo chmod 666 /var/www/html/files/'.$CN.'/'.$CN.'.csr');
		            exec('sudo chmod 666 /var/www/html/files/'.$CN.'/'.$CN.'.crt');
		            exec('rm -f /var/www/html/'.$CN.'.san.ext');
		    echo '<meta http-equiv="refresh" content="0; URL=index.php">';

            } else {
                echo "Password cannot be empty if p12 is checked";

            }
	} else {
		// Create SAN
                        exec('cp server_cert_san_ext.conf '.$CN.'.san.ext');
                        exec('echo "DNS.1 = '.$CN.'" >> '.$CN.'.san.ext');
			// Send CSR for sign to CA
			exec('sudo mkdir files/'.$CN.'');
			exec('sudo chown www-data:www-data files/'.$CN.'');
			exec('sudo openssl genrsa -out /var/www/html/files/'.$CN.'/'.$CN.'.key 2048');
			exec('sudo openssl req -new -key /var/www/html/files/'.$CN.'/'.$CN.'.key -out /var/www/html/files/'.$CN.'/'.$CN.'.csr -subj "/CN='.$CN.'"');
			exec('sudo openssl x509 -req -in /var/www/html/files/'.$CN.'/'.$CN.'.csr -CA /var/www/html/files/ca/ca.pem -CAkey /var/www/html/files/ca/ca.key -CAcreateserial -out /var/www/html/files/'.$CN.'/'.$CN.'.crt -days 365 -sha256 -passin pass:'.$CAKEY.' -extfile '.$CN.'.san.ext');
			sleep(2);
                    // Change ownership of created files
                	exec('sudo chown www-data:www-data /var/www/html/files/'.$CN.'/'.$CN.'.csr');
			exec('sudo chown www-data:www-data /var/www/html/files/'.$CN.'/'.$CN.'.key');
			exec('sudo chown www-data:www-data /var/www/html/files/'.$CN.'/'.$CN.'.crt');
			
			exec('sudo chmod 666 /var/www/html/files/'.$CN.'/'.$CN.'.csr');
			exec('sudo chmod 666 /var/www/html/files/'.$CN.'/'.$CN.'.crt');
			exec('sudo chmod 666 /var/www/html/files/'.$CN.'/'.$CN.'.key');
			exec('rm -f /var/www/html/'.$CN.'.san.ext');
			echo '<meta http-equiv="refresh" content="0; URL=index.php">';
        }
    }

}
?>


</body>
</html>
