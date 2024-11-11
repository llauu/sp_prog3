<?php
require_once './utils/AutentificadorJWT.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class AuthMiddleware
{
    /**
     * Example middleware invokable class
     *
     * @param  ServerRequest  $request PSR-7 request
     * @param  RequestHandler $handler PSR-15 request handler
     *
     * @return Response
     */
    public function __invoke(Request $request, RequestHandler $handler): Response
    {   
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);
        
        try {
            AutentificadorJWT::VerificarToken($token);
            $response = $handler->handle($request);
        } catch (Exception $e) {
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'ERROR: Hubo un error con el TOKEN'));
            $response->getBody()->write($payload);
        }
        
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function VerificarToken(Request $request, RequestHandler $handler): Response
    {
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);

        try {
            AutentificadorJWT::VerificarToken($token);
            $response = $handler->handle($request);
        } catch (Exception $e) {
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'ERROR: Hubo un error con el TOKEN'));
            $response->getBody()->write($payload);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function VerificarRolSupervisor(Request $request, RequestHandler $handler): Response
    {
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);

        try {
            $data = AutentificadorJWT::ObtenerData($token);

            if ($data->rol == 'supervisor') {
                $request = $request->withAttribute('rol', $data->rol);
                $request = $request->withAttribute('email', $data->email);
                $response = $handler->handle($request);
            }
            else {
                throw new Exception("No es supervisor");
            }
        } catch (Exception $e) {
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'Hubo un error con el TOKEN: '. $e->getMessage()));
            $response->getBody()->write($payload);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }
    
    public static function VerificarRolCajero(Request $request, RequestHandler $handler): Response
    {
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);

        try {
            $data = AutentificadorJWT::ObtenerData($token);

            if ($data->rol == 'cajero') {
                $request = $request->withAttribute('rol', $data->rol);
                $request = $request->withAttribute('email', $data->email);
                $response = $handler->handle($request);
            }
            else {
                throw new Exception("No es cajero");
            }
        } catch (Exception $e) {
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'Hubo un error con el TOKEN: '. $e->getMessage()));
            $response->getBody()->write($payload);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }
    
    public static function VerificarRolOperador(Request $request, RequestHandler $handler): Response
    {
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);

        try {
            $data = AutentificadorJWT::ObtenerData($token);

            if ($data->rol == 'operador') {
                $request = $request->withAttribute('rol', $data->rol);
                $request = $request->withAttribute('email', $data->email);
                $response = $handler->handle($request);
            }
            else {
                throw new Exception("No es operador");
            }
        } catch (Exception $e) {
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'Hubo un error con el TOKEN: '. $e->getMessage()));
            $response->getBody()->write($payload);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }
}