
CREATE TABLE orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  pizza_id INT NOT NULL,
  quantity INT NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  date DATETIME NOT NULL
);

INSERT INTO orders (user_id, pizza_id, quantity, price, date)
VALUES ($userId, $pizzaId, $quantity, $price, '$date');