# repo
https://github.com/s3uz-dev/slimphp-libsql


## turso dev 
❯ turso dev --db-file mi_proyecto.db  

composer require slim/slim

composer require slim/psr7

composer require php-di/php-di
 
composer dump-autoload




db_name : b13_40654599_products_db
username: b13_40654599
password: (Your vPanel Password)
host    : sql312.byethost13.com



/* 

CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  name VARCHAR(255) DEFAULT NULL,
  role VARCHAR(50) DEFAULT 'user',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS refresh_tokens (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  token_hash VARCHAR(255) NOT NULL,
  expires_at DATETIME NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX (token_hash)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



 CREATE TABLE productos (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10, 2) NOT NULL,
    stock INT DEFAULT 0,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
); 


INSERT INTO productos (nombre, descripcion, precio, stock) VALUES
-- Productos 1 - 5 (Tecnología y Hogar)
('Smartphone X50', 'Teléfono de última generación con cámara 108MP y batería de larga duración.', 899.99, 50),
('Smart TV 55"', 'Televisor 4K UHD de 55 pulgadas con funciones inteligentes.', 599.50, 25),
('Cafetera Express', 'Máquina profesional para hacer café espresso y capuchino.', 120.00, 75),
('Auriculares Bluetooth', 'Auriculares inalámbricos con cancelación de ruido activa.', 49.99, 200),
('Monitor Curvo 27"', 'Monitor para gaming con tasa de refresco de 144Hz.', 299.00, 30),

-- Productos 6 - 10 (Libros, Deportes y Ropa)
('Libro: El Viajero', 'Novela de fantasía épica, edición de tapa dura.', 18.75, 150),
('Mancuernas Ajustables', 'Set de mancuernas para ejercicios en casa, peso ajustable de 5 a 50 lbs.', 150.00, 40),
('Sudadera Deportiva', 'Sudadera de algodón con capucha, color gris melange.', 35.90, 90),
('Mouse Ergonómico', 'Mouse inalámbrico diseñado para reducir la tensión en la muñeca.', 25.45, 110),
('Termo de Acero Inoxidable', 'Termo de doble pared que mantiene bebidas frías o calientes por 24 horas.', 15.00, 300);

 */

 


```bash

curl -X POST http://localhost:8000/api/products    -H "Content-Type: application/json" -d '{
    "nombre": "Teclado Matricila",
    "descripcion": "Teclado de alto rendimiento con interruptores táctiles.",
    "precio": 85.50,
    "stock": 45
  }' 


curl -X PUT   http://localhost:8000/api/products/12  -H "Content-Type: application/json"  -d '{
    "nombre": "Teclado Táctil Premium",
    "descripcion": "Teclado mecánico mejorado con switches Gateron, ideal para programación.",
    "precio": 120.99,
    "stock": 30 
  }'


curl -X DELETE  http://localhost:8000/api/products/12





curl -X POST http://localhost:8000/api/auth/login  -H "Content-Type: application/json" -d '{
    "email": "kb81987@gmail.com",
    "password": "masterpass" 
  }'


curl -X GET http://localhost:8000/api/me   -H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3NjU1MTYzNzIsImV4cCI6MTc2NTUxNzI3Miwic3ViIjoxLCJlbWFpbCI6ImtiODE5ODdAZ21haWwuY29tIiwicm9sZSI6InVzZXIifQ.VccF36r-yupTfKk5Tvm6zcn-dAkZIYG2i5SdodrM1l0"


```



 