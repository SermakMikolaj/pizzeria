<?php
session_start();

// Połączenie z bazą danych
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pizerria";


$conn = mysqli_connect($servername, $username, $password, $dbname);

// Inicjalizacja koszyka
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// Tablica z dostępnymi rodzajami pizzy
$pizzas = array(
  1 => array('name' => 'Margherita', 'price' => 15),
  2 => array('name' => 'Funghi', 'price' => 18),
  3 => array('name' => 'Capricciosa', 'price' => 20),
  4 => array('name' => 'Quattro Stagioni', 'price' => 22)
);

// Dodawanie pizzy do koszyka
if (isset($_GET['add'])) {
  $pizzaId = $_GET['add'];
  if (!isset($_SESSION['cart'][$pizzaId])) {
    $_SESSION['cart'][$pizzaId] = 1;
  } else {
    $_SESSION['cart'][$pizzaId]++;
  }
}

// Usuwanie pizzy z koszyka
if (isset($_GET['delete'])) {
  $pizzaId = $_GET['delete'];
  unset($_SESSION['cart'][$pizzaId]);
}

// Dodawanie zamówienia do historii zakupów
if (isset($_GET['checkout'])) {
  $userId = 1; // Tymczasowe ustawienie id użytkownika
  $date = date('Y-m-d H:i:s');
  $total = 0;
  foreach ($_SESSION['cart'] as $pizzaId => $quantity) {
    $pizza = $pizzas[$pizzaId];
    $price = $pizza['price'] * $quantity;
    $total += $price;
    $query = "INSERT INTO orders (user_id, pizza_id, quantity, price, date) VALUES ($userId, $pizzaId, $quantity, $price, '$date')";
    mysqli_query($conn, $query);
  }
  $_SESSION['cart'] = array();
}

// Wypisanie formularza dodawania pizzy
echo '<h2>Dodaj pizzę do koszyka</h2>';
echo '<form>';
echo '<select name="add">';
foreach ($pizzas as $id => $pizza) {
  echo "<option value   = $id>{$pizza['name']} ({$pizza['price']} zł)</option>";
}
echo '</select>';
echo '<input type="submit" value="Dodaj">';
echo '</form>';

// Wypisanie zawartości koszyka
echo '<h2>Zawartość koszyka</h2>';
if (!empty($_SESSION['cart'])) {
  echo '<table>';
  echo '<tr><th>Nazwa</th><th>Ilość</th><th>Cena</th><th></th></tr>';
  $total = 0;
  foreach ($_SESSION['cart'] as $pizzaId => $quantity) {
    $pizza = $pizzas[$pizzaId];
    $price = $pizza['price'] * $quantity;
    $total += $price;
    echo "<tr>";
    echo "<td>{$pizza['name']}</td>";
    echo "<td>$quantity</td>";
    echo "<td>$price zł</td>";
    echo "<td><a href='?delete=$pizzaId'>Usuń</a></td>";
    echo "</tr>";
  }
  echo "<tr><td colspan='2'>Łącznie:</td><td colspan='2'>$total zł</td></tr>";
  echo '</table>';
  echo "<p><a href='?checkout'class='checkout-button'>Zamów</a></p>";
} else {
  echo '<p>Koszyk jest pusty</p>';
}
// Wyświetlanie historii zakupów
echo '<h2>Historia zakupów</h2>';
$userId = 1; // Tymczasowe ustawienie id użytkownika
$query = "SELECT * FROM orders WHERE user_id = $userId ORDER BY date DESC";
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) > 0) {
  echo '<table>';
  echo '<tr><th>Data</th><th>Nazwa</th><th>Ilość</th><th>Cena</th></tr>';
  while ($row = mysqli_fetch_assoc($result)) {
    $pizzaId = $row['pizza_id'];
    $pizza = $pizzas[$pizzaId];
    echo '<tr>';
    echo "<td>{$row['date']}</td>";
    echo "<td>{$pizza['name']}</td>";
    echo "<td>{$row['quantity']}</td>";
    echo "<td>{$row['price']}</td>";
    echo '</tr>';
  }
  echo '</table>';
} else {
  echo 'Brak zamówień.';
}
$query = "SELECT SUM(price) AS total FROM orders WHERE user_id = $userId";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$total = $row['total'];
echo "<h2>Suma zamówień w historii: $total zł</h2>";
?>
<!DOCTYPE html>
<html lang="en">
    
<head>
  <style>
    body {
      background-color: lightgray;
    }
    h2 {
      text-align: center;
      color: darkblue;
      margin-top: 50px;
    }
    
    table {
      width: 500px;
      margin: 0 auto;
      border-collapse: collapse;
    }
    th, td {
      border: 1px solid black;
      padding: 10px;
      text-align: center;
    }
    th {
      background-color: lightblue;
    }
    .checkout-button {
  padding: 10px 20px;
  background-color: #4CAF50;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  text-decoration: none;
  margin-bottom:210vh;
  margin-left:94vh;
  

  }
  </style>
 
</head>
<body>
<form method="post" action="index2.php">
 <label for="login">Login:</label>
 <input type="text" id="login" name="login" required><br><br>
 <label for="haslo">Obecne hasło:</label>
 <input type="password" id="haslo" name="haslo" required><br><br>
 <label for="nowe_haslo">Nowe hasło:</label>
 <input type="password" id="nowe_haslo" name="nowe_haslo"><br><br>
 <label for="potw_haslo">Potwierdź nowe hasło:</label>
 <input type="password" id="potw_haslo" name="potw_haslo"><br><br>
 <label for="nowy_email">Nowy email:</label>
 <input type="email" id="nowy_email" name="nowy_email"><br><br>
 <label for="potw_haslo2">Potwierdź hasło:</label>
 <input type="password" id="potw_haslo2" name="potw_haslo2" required><br><br>
 <input type="submit" value="Zapisz zmiany">
 </form>
</body>
</html>




<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pizerria";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if(isset($_POST['login']) && isset($_POST['haslo'])) {
  $login = $_POST['login'];
  $haslo = $_POST['haslo'];

  $hashed_haslo = sha1($haslo);

  // sprawdzenie czy użytkownik o podanym loginie i haśle istnieje w bazie
  $query = "SELECT * FROM `user` WHERE login='$login' and haslo='$hashed_haslo'";
  $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
  $count = mysqli_num_rows($result);

  if($count == 1) {
    // użytkownik o podanym loginie i haśle istnieje w bazie - pobieramy jego dane
    $row = mysqli_fetch_assoc($result);
    $email = $row['email'];

    if(isset($_POST['nowe_haslo']) && isset($_POST['potw_haslo']) && isset($_POST['potw_haslo2'])) {
      // użytkownik chce zmienić swoje hasło - sprawdzamy poprawność wpisanego obecnego hasła
      $obecne_haslo = $_POST['potw_haslo2'];
      $hashed_obecne_haslo = sha1($obecne_haslo);

      if($hashed_obecne_haslo == $row['haslo']) {
        // obecne hasło jest poprawne - sprawdzamy czy wpisane nowe hasło zgadza się z potwierdzeniem
        $nowe_haslo = $_POST['nowe_haslo'];
        $potw_haslo = $_POST['potw_haslo'];
        if($nowe_haslo == $potw_haslo) {
          // nowe hasło zgadza się z potwierdzeniem - aktualizujemy hasło w bazie
          $hashed_nowe_haslo = sha1($nowe_haslo);
          $update_query = "UPDATE `user` SET haslo='$hashed_nowe_haslo' WHERE login='$login'";
          mysqli_query($conn, $update_query) or die(mysqli_error($conn));
          echo "Hasło zostało zmienione";
        } else {
          echo "Nowe hasło nie zgadza się z potwierdzeniem";
        }
      } else {
        echo "Podane obecne hasło jest nieprawidłowe";
      }
    } elseif(isset($_POST['nowy_email']) && isset($_POST['potw_haslo2'])) {
      // użytkownik chce edytować swój email - sprawdzamy czy podane hasło jest poprawne
      $potw_haslo2 = $_POST['potw_haslo2'];
      $hashed_potw_haslo2 = sha1($potw_haslo2);

      if($hashed_potw_haslo2 == $row['haslo']) {
        // podane hasło jest poprawne - aktualizujemy email użytkownika w bazie
        $nowy_email = $_POST['nowy_email'];
        $update_query = "UPDATE `user` SET email='$nowy_email' WHERE login='$login'";
        mysqli_query($conn, $update_query) or die(mysqli_error($conn));
        echo "Email został zmieniony";
      } else {
        echo "Podane hasło jest nieprawidłowe";
        }
        } else {
        // użytkownik chce tylko wyświetlić swoje dane - wyświetlamy jego login i email
        echo "Login: $login<br>";
        echo "Email: $email<br>";
        }
        } else {
        echo "Podany użytkownik nie istnieje lub podane hasło jest nieprawidłowe";
        }
        }
        mysqli_close($conn);
       ?>