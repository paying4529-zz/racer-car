<!DOCTYPE html>
<html>
<body>
<?php
    $pws = file("racecar_password.txt");
    if(count($pws)==0){
        $pws = file("password.txt");
    }
    echo count($pws);
    echo $pws[count($pws)-1];
    echo "<br>";
    $myfile = fopen("racecar_password.txt", "w") or die("Unable to open file!");
    for($x = 0; $x < count($pws)-1; $x++) {
        $txt = $pws[$x];
        fwrite($myfile, $txt); 
    }
    fclose($myfile);
?>

</body>
</html>