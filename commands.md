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


```