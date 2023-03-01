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
    session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pizerria";


$conn = mysqli_connect($servername, $username, $password, $dbname);

if (isset($_POST['haslo']) and isset($_POST['login'])){
  $login = $_POST['login'];
  $haslo= $_POST['haslo'];
  $email= $_POST ['email'];
  

  $hashed_haslo = sha1($_POST['haslo']);


  if($conn->connect_error){
    die("Connection failed: " . $conn->connection_error);
  }
  else{
    echo("Działa");
  }
  $sql = "INSERT INTO user (haslo, login, email) VALUES ('$hashed_haslo' , '$login' , '$email')";


if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
  $_SESSION["login"]=$login;
        $_SESSION["hashed_password"]=$hashed_haslo;
        $_SESSION["password"]=$haslo; 
}
  if (isset($_POST['haslo2']) and isset($_POST['login2'])){
    $login2 = $_POST['login2'];
    $haslo2= sha1($_POST['haslo2']);
    
    $query = "SELECT * FROM `user` WHERE login='$login2' and haslo='$haslo2'";
    $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
    $count = mysqli_num_rows($result);
 
    if ($count == 1){
    header("location: index2.php");;
    }
    else {
    echo("NIE DZIAUA");
    }
    
  }

