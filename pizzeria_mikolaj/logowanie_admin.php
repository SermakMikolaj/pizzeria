<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php

        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "pizerria";

        $login_admin =$_POST['login3'];
        $haslo_admin =$_POST['haslo3'];

        $hashed_password = sha1($haslo_admin);
        $conn = new mysqli($servername, $username, $password, $dbname);
        
        if (isset($_POST['haslo3']) and isset($_POST['login3'])){
            $login3 = $_POST['login3'];
            $haslo3= $_POST['haslo3'];
            
            $query = "SELECT * FROM `admin` WHERE login_admin='$login3' and haslo_admin='$haslo3'";
            echo($login3);
            echo("<br>");
            echo($haslo3);
            $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
            $count = mysqli_num_rows($result);
         
            if ($count == 1){
            
            header("location: index2.php");
            
            }
            else {
            echo("NIE DZIAUA");
            }
            
          }
    ?>

</body>
</html>