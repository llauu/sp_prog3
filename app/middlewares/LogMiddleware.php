<?php
require_once './db/AccesoDatos.php';
require_once './models/LogConsulta.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class LogMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {   
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);

        $data = AutentificadorJWT::ObtenerData($token);

        $log = new LogConsulta();
        $log->email = $data->email;
        $log->accion = $request->getMethod();
        $log->url = $request->getUri();
        $log->hora = date("Y-m-d H:i:s");
        $log->crearLog();

        $response = $handler->handle($request);
        return $response->withHeader('Content-Type', 'application/json');
    }
}