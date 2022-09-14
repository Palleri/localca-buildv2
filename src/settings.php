<html>
    <head>
    <title>Certificate Web Request</title>
    <link rel="icon" href="favico.jpeg">
    <link rel="stylesheet" href="style.css">


    </head>
<body>

<?php 





if (isset($_POST['smtpserver']) ){
    if (file_exists("/var/www/html/script/smtpserver.txt")) {
    unlink('/var/www/html/script/smtpserver.txt');
    }
    if (file_exists("/var/www/html/script/sendto.txt")) {
    unlink('/var/www/html/script/sendto.txt');
    }
    if (file_exists("/var/www/html/script/smtpuser.txt")) {
    unlink('/var/www/html/script/smtpuser.txt');
    }
    if (file_exists("/var/www/html/script/smtppass.txt")) {
    unlink('/var/www/html/script/smtppass.txt');
    }
    if (file_exists("/var/www/html/script/smtpusetls.txt")) {
    unlink('/var/www/html/script/smtpusetls.txt');
    }
    if (file_exists("/var/www/html/script/smtpstarttls.txt")) {
    unlink('/var/www/html/script/smtpstarttls.txt');
    }
	$smtpserver = $_POST['smtpserver'];
    $reconf = "1";
    exec('script/smtp.sh mailhub='.$smtpserver.' '.$reconf.'');
    file_put_contents("/var/www/html/script/smtpserver.txt", $smtpserver);
    
    if (empty($smtpserver)) {
        ?>
            <script>
            alert('SMTP server cannot be empty');
            </script>
        <?php
        } else {
            file_put_contents("/var/www/html/script/smtpconfig.conf", "1");
        if (!empty($_POST['sendto']) ){
            $sendto = $_POST['sendto'];
            file_put_contents("/var/www/html/script/sendto.txt", $sendto);
            
            }
        if (!empty($_POST['smtpuser']) ){
            $smtpuser = $_POST['smtpuser'];
            exec('script/smtp.sh AuthUser='.$smtpuser.'');  
            file_put_contents("/var/www/html/script/smtpuser.txt", $smtpuser);
            
            }
        if (!empty($_POST['smtppass']) ){
            $smtppass = $_POST['smtppass'];
            exec('script/smtp.sh AuthPass='.$smtppass.'');  
            file_put_contents("/var/www/html/script/smtppass.txt", $smtppass);
            
            }
        if (!empty($_POST['smtpusetls']) ){
            $smtpusetls = "YES";
            exec('script/smtp.sh UseTLS='.$smtpusetls.'');  
            file_put_contents("/var/www/html/script/smtpusetls.txt", $smtpusetls);
            
            }
        if (!empty($_POST['smtpstarttls']) ){
            $smtpstarttls = "YES";
            exec('script/smtp.sh UseSTARTTLS='.$smtpstarttls.'');  
            file_put_contents("/var/www/html/script/smtpstarttls.txt", $smtpstarttls);
            
            }
    }
}






?>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">


<div class="content">
<h1><a class="ca" href=index.php>Email Notification Settings</a></h1>
<p><table border=0>
    <thead>
        <tr>
            <th colspan="2">Send mail when a certificate is about to expire</th>
            
        <tr>
    </thead>
    <tbody>
        <tr>
    



        <table class="tg" border="0">

<tbody align="left">
  <tr>
    <th>Send To: </th>
    <?php
    if (file_exists("/var/www/html/script/sendto.txt")) {
        $confsendto = file_get_contents("/var/www/html/script/sendto.txt");
    echo "<td><input type=\"text\" value =\"$confsendto\" name=\"sendto\"></td>";
    }else {
    echo "<td><input type=\"text\" value =\"\" name=\"sendto\"></td>";
    }?>
  </tr>
  <tr>
    <th>SMTP Server: </th>
    <?php 
     if (file_exists("/var/www/html/script/smtpserver.txt")) {
        $confsmtpserver = file_get_contents("/var/www/html/script/smtpserver.txt");
    echo "<td><input type=\"text\" value =\"$confsmtpserver\" name=\"smtpserver\"></td><th>Format: mail.example.com:587</th>";
     }else {
        echo "<td><input type=\"text\" value =\"\" name=\"smtpserver\"></td><th>Format: mail.example.com:587</th>";
     }
     ?>
  </tr>
  <tr>
    <th>AuthUser: </th>
    <?php
    if (file_exists("/var/www/html/script/smtpuser.txt")) {
        $confsmtpuser = file_get_contents("/var/www/html/script/smtpuser.txt");
    echo "<td><input type=\"text\" value=\"$confsmtpuser\" name=\"smtpuser\"></td>";
    }else {
        echo "<td><input type=\"text\" value=\"\" name=\"smtpuser\"></td>";
    }
    ?>
  </tr>
  <tr>
    <th>AuthPass: </th>
    <?php 
    if (file_exists("/var/www/html/script/smtppass.txt")) {
        $confsmtppass = file_get_contents("/var/www/html/script/smtppass.txt");
    echo "<td><input type=\"text\" value=\"$confsmtppass\" name=\"smtppass\"></td>";
    }else {
        echo "<td><input type=\"text\" value=\"\" name=\"smtppass\"></td>";
    }
    ?>
  </tr>
  <tr>
    <th>Use TLS: </th>
    <?php 
    if (file_exists("/var/www/html/script/smtpusetls.txt")) {
        $confsmtpusetls = file_get_contents("/var/www/html/script/smtpusetls.txt");
    echo "<td><input type=\"checkbox\" name=\"smtpusetls\" checked></td>";
    }else {
        echo "<td><input type=\"checkbox\" name=\"smtpusetls\"></td>";
    }
    ?>
  </tr>
  <tr>
    <th>Use STARTTLS: </th>
    <?php
    if (file_exists("/var/www/html/script/smtpstarttls.txt")) {
        $confsmtpstarttls = file_get_contents("/var/www/html/script/smtpstarttls.txt");
    echo "<td><input type=\"checkbox\" name=\"smtpstarttls\" checked></td>";
    }else {
        echo "<td><input type=\"checkbox\" name=\"smtpstarttls\"></td>";
    }
    ?>
  </tr>
  <tr>
    <td><input type="submit" value="Save settings"></td>
  <tr>

    
  </tr>


</form>



<?php
    if (isset($_POST['smtptestmail'])){
        $smtptestmail = $_POST['smtptestmail'];
        exec('script/smtptest.sh '.$smtptestmail.'');
        ?>
        <script>
        alert('Test mail sent');
        </script>
        <?php
    }
            
        
?>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
<td align="left">
    <input type="submit" value="Test SMTP settings">
</td>
<td><input type="text" name="smtptestmail"></td><th>test@example.com</th>
</tr>
</form>
<tr>
<td align="left">
<form action="index.php">
    <input type="submit" value="Go back">
</form>
</td>
<tr>
</tbody>
</table>







          </div>





</body>
</html>
