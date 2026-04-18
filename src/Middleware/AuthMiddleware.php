<?php
namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class AuthMiddleware {
    public function __invoke(Request $request, RequestHandler $handler): Response {
        session_start();
        
        if (!isset($_SESSION['admin_logged_in'])) {
            $response = new Response();
            return $response
                ->withHeader('Location', '/admin/login')
                ->withStatus(302);
        }
        
        return $handler->handle($request);
    }
}
