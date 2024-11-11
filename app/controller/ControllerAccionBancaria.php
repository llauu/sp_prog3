<?php
require_once './models/AccionBancaria.php';
require_once './models/Usuario.php';

class ControllerAccionBancaria extends AccionBancaria {
    public function HacerDeposito($request, $response, $args) {
        $parametros = $request->getParsedBody();
        $files = $request->getUploadedFiles();

        if(isset($parametros['tipoDeCuenta']) && isset($parametros['nroCuenta']) && isset($parametros['importe']) && isset($files['imagenDeposito'])) {
            $tipoDeCuenta = $parametros['tipoDeCuenta'];
            $nroCuenta = $parametros['nroCuenta'];
            $importe = $parametros['importe'];

            $usuario = Cuenta::obtenerCuenta($nroCuenta, $tipoDeCuenta);  

            if(!$usuario) {
                $payload = json_encode(array("error" => "Usuario no encontrado"));
            }
            else {
                if($idDeposito = Cuenta::realizarDeposito($importe, $usuario) !== -1) {
                    $extension = pathinfo($files['imagenDeposito']->getClientFilename(), PATHINFO_EXTENSION);
                    
                    $destino = "./ImagenesDeDepositos/2023/$tipoDeCuenta-$nroCuenta-$idDeposito.$extension";
                    $files['imagenDeposito']->moveTo($destino);

                    $payload = json_encode(array("mensaje" => "Deposito realizado con exito"));
                }
                else {
                    $payload = json_encode(array("error" => "Error al realizar el deposito"));
                }
            }
        }
        else {
            $payload = json_encode(array("error" => "Faltan datos"));
        }
        
        $response->getBody()->write($payload);
    
        return $response
            ->withHeader('Content-Type', 'application/json');
    }


    public function HacerRetiro($request, $response, $args) {
        $parametros = $request->getParsedBody();

        if(isset($parametros['tipoDeCuenta']) && isset($parametros['nroCuenta']) && isset($parametros['importe'])) {
            $tipoDeCuenta = $parametros['tipoDeCuenta'];
            $nroCuenta = $parametros['nroCuenta'];
            $importe = $parametros['importe'];

            $usuario = Cuenta::obtenerCuenta($nroCuenta, $tipoDeCuenta);

            if(!$usuario) {
                $payload = json_encode(array("error" => "Usuario no encontrado"));
            }
            else {
                if($usuario->saldo < $importe) {
                    $payload = json_encode(array("error" => "Saldo insuficiente"));
                }
                else {
                    if(Cuenta::realizarRetiro($importe, $usuario) !== -1) {
                        $payload = json_encode(array("mensaje" => "Retiro realizado con exito"));
                    }
                    else {
                        $payload = json_encode(array("error" => "Error al realizar el retiro"));
                    }
                }
            }
        }
        else {
            $payload = json_encode(array("error" => "Faltan datos"));
        }
        
        $response->getBody()->write($payload);
    
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
}

?>