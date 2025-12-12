<?php
namespace App\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use App\Utils\JwtHelper;

class JwtMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $auth = $request->getHeaderLine('Authorization') ?: $request->getHeaderLine('authorization');
        if (!$auth || stripos($auth, 'Bearer ') !== 0) {
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write(json_encode(['error' => 'Unauthorized']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
        }
        $token = trim(substr($auth, 7));
        $decoded = JwtHelper::verifyAccessToken($token);
        if (!$decoded) {
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write(json_encode(['error' => 'Invalid token']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
        }
        $request = $request->withAttribute('user', $decoded);
        return $handler->handle($request);
    }
}
