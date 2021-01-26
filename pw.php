<!DOCTYPE html>
<html>
<body>

<?php
$myfile = fopen("racercar_password.txt", "r") or die("Unable to open file!");
echo fread($myfile,filesize("racercar_password.txt"));
fclose($myfile);
?>

</body>
</html>