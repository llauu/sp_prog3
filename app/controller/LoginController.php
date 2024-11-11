<?php
require_once './models/Usuario.php';

class LoginController {
    public function Login($request, $response, $args) {
        $params = $request->getParsedBody();
        $email = $params['email'];
        $clave = $params['clave'];

        // Valido que exista email y clave
        if (Usuario::validarEmailYClave($email, $clave)) {
            $usr = Usuario::ObtenerUsuarioPorEmail($email);
            
            $datos = array(
                'email' => $email,
                'rol' => $usr->rol 
            );

            $token = AutentificadorJWT::CrearToken($datos);
            $payload = json_encode(array("jwt" => $token));
        } else {
            $payload = json_encode(array("mensaje" => "Email o clave incorrectos"));
        }

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
    
    public function LoginCuenta($request, $response, $args) {
        $params = $request->getParsedBody();
        $email = $params['email'];
        $clave = $params['clave'];

        // Valido que exista email y clave
        if (Cuenta::validarEmailYClave($email, $clave)) {
            // $cuenta = Cuenta::ObtenerCuentaPorEmail($email);
            
            $datos = array(
                'email' => $email,
            );

            $token = AutentificadorJWT::CrearToken($datos);
            $payload = json_encode(array("jwt" => $token));
        } else {
            $payload = json_encode(array("mensaje" => "Email o clave incorrectos"));
        }

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
}