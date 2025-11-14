-- Очищення таблиць, якщо вони вже існують
DROP TABLE IF EXISTS Report;
DROP TABLE IF EXISTS ShipmentDetails;
DROP TABLE IF EXISTS Shipment;
DROP TABLE IF EXISTS Orders;
DROP TABLE IF EXISTS Unit;
DROP TABLE IF EXISTS Commander;
DROP TABLE IF EXISTS Product;

-- Таблиця продукції
CREATE TABLE Product (
  product_id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(100),
  price DECIMAL(10,2)
);

-- Таблиця командирів
CREATE TABLE Commander (
  commander_id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(100),
  commander_rank VARCHAR(50)
);

-- Таблиця підрозділів
CREATE TABLE Unit (
  unit_id INT PRIMARY KEY AUTO_INCREMENT,
  unit_name VARCHAR(100),
  type VARCHAR(50),
  commander_id INT,
  FOREIGN KEY (commander_id) REFERENCES Commander(commander_id)
);

-- Таблиця замовлень
CREATE TABLE Orders (
  order_id INT PRIMARY KEY AUTO_INCREMENT,
  customer_name VARCHAR(100),
  customer_address VARCHAR(200),
  contract_number VARCHAR(50),
  contract_date DATE,
  product_id INT,
  planned_quantity INT,
  FOREIGN KEY (product_id) REFERENCES Product(product_id)
);

-- Таблиця відправок
CREATE TABLE Shipment (
  shipment_id INT PRIMARY KEY AUTO_INCREMENT,
  order_id INT,
  shipment_base VARCHAR(100),
  FOREIGN KEY (order_id) REFERENCES Orders(order_id)
);

-- Таблиця деталей відправок
CREATE TABLE ShipmentDetails (
  shipment_details_id INT PRIMARY KEY AUTO_INCREMENT,
  shipment_id INT,
  unit_id INT,
  quantity INT,
  FOREIGN KEY (shipment_id) REFERENCES Shipment(shipment_id),
  FOREIGN KEY (unit_id) REFERENCES Unit(unit_id)
);

-- Таблиця звітів
CREATE TABLE Report (
  report_id INT PRIMARY KEY AUTO_INCREMENT,
  unit_id INT,
  order_id INT,
  text TEXT,
  FOREIGN KEY (unit_id) REFERENCES Unit(unit_id),
  FOREIGN KEY (order_id) REFERENCES Orders(order_id)
);
