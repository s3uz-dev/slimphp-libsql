<?php

declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Repositories\ProductRepository;
use Valitron\Validator;

final class ProductsController
{
    public function __construct(
        private ProductRepository $repository,
        private Validator $validator
    ) {
      
    }

private function createValidator(array $data): Validator
    {
        $validator = new Validator($data);
        
        // 1. DEFINICIÓN DE REGLAS
        $validator->mapFieldRules('nombre', [
            'required',
            ['lengthMin', 3]
        ]);
        
        $validator->mapFieldRules('precio', [
            'required',
            'numeric',
            ['min', 0]
        ]);
        
        $validator->mapFieldRules('stock', [
            'required',
            'integer',
            ['min', 0]
        ]);
        
        // Opcional: Etiquetas para los campos
        $validator->labels([
            'nombre' => 'Nombre',
            'precio' => 'Precio',
            'stock'  => 'Stock'
        ]);
        
        return $validator;
    }


    /* Listar todos los productos */
    public function all(Request $request, Response $response): Response
    {
        $products = $this->repository->getAllProducts();
        $body = json_encode($products);
        $response->getBody()->write($body);
        return $response;
    }

    /* Obtener por id */
    public function byId(Request $request, Response $response, array $args): Response
    {
        $productId = (int) $args['id'];
        $product =  $this->repository->getProductById($productId);
        $body = json_encode($product);
        $response->getBody()->write($body);
        return $response;
    }

     /* Crear Producto */
    public function create(Request $request, Response $response): Response
    {
        $body = $request->getParsedBody();
        $validator = $this->createValidator($body);
        
        if (!$validator->validate()) {
            $errors = $validator->errors();
            $body = json_encode([
                'errors' => $errors
            ]);
            $response->getBody()->write($body);
            return $response->withStatus(422);
        }
        
        $id = $this->repository->createProduct($body);
        $body = json_encode([
            'message' => 'Producto creado creado exitosamente',
            'id' => $id
        ]);
        $response->getBody()->write($body);
        return $response;
    }

    /* editar producto */
    public function update(Request $request, Response $response, array $args): Response
    {
        $productId = (int) $args['id'];
        $body = $request->getParsedBody();
        $validator = $this->createValidator($body);
        
        if (!$validator->validate()) {
            $errors = $validator->errors();
            $body = json_encode([
                'errors' => $errors
            ]);
            $response->getBody()->write($body);
            return $response->withStatus(422);
        }
        
        $this->repository->updateProduct($productId, $body);
        $body = json_encode([
            'message' => 'Producto actualizado exitosamente'
        ]);
        $response->getBody()->write($body);
        return $response;
    }

    /* eliminar producto */
    public function delete(Request $request, Response $response, array $args): Response
    {
        $productId = (int) $args['id'];
        $this->repository->deleteProduct($productId);
        $body = json_encode([
            'message' => 'Producto eliminado exitosamente'
        ]);
        $response->getBody()->write($body);
        return $response;   
    }
}
