-- Очистка даних
DELETE FROM Report;
DELETE FROM ShipmentDetails;
DELETE FROM Shipment;
DELETE FROM Orders;
DELETE FROM Unit;
DELETE FROM Commander;
DELETE FROM Product;

-- Дані для Product
INSERT INTO Product (name, price) VALUES
('Tank T-90', 5000000.00),
('APC BTR-82', 3200000.00),
('Helicopter Mi-24', 12500000.00);

-- Дані для Commander
INSERT INTO Commander (name, commander_rank) VALUES
('Ivan Petrov', 'Colonel'),
('Serhiy Bondar', 'Major'),
('Oleh Kozlov', 'Captain');

-- Дані для Unit
INSERT INTO Unit (unit_name, type, commander_id) VALUES
('1st Armored Division', 'Tank', 1),
('2nd Mechanized Division', 'Infantry', 2),
('3rd Air Assault Brigade', 'Airborne', 3);

-- Дані для Orders
INSERT INTO Orders (customer_name, customer_address, contract_number, contract_date, product_id, planned_quantity) VALUES
('Ministry of Defense', 'Kyiv, Ukraine', 'C-001', '2025-10-01', 1, 10),
('Allied Forces', 'Warsaw, Poland', 'C-002', '2025-10-05', 2, 15);

-- Дані для Shipment
INSERT INTO Shipment (order_id, shipment_base) VALUES
(1, 'Kyiv Base'),
(2, 'Lviv Base');

-- Дані для ShipmentDetails
INSERT INTO ShipmentDetails (shipment_id, unit_id, quantity) VALUES
(1, 1, 5),
(1, 2, 5),
(2, 3, 10);

-- Дані для Report
INSERT INTO Report (unit_id, order_id, text) VALUES
(1, 1, 'Shipment completed successfully.'),
(2, 1, 'Delivered on time.'),
(3, 2, 'Partial delivery due to weather conditions.');
