<?php
require_once './models/Ajuste.php';
require_once './models/AccionBancaria.php';

class ControllerAjuste extends Ajuste {
    public function RealizarAjuste($request, $response, $args) {
        $parametros = $request->getParsedBody();
        
        if(isset($parametros['idAccion']) && isset($parametros['motivo'])) {
            $idAccion = $parametros['idAccion'];
            $motivo = $parametros['motivo'];
            
            $accion = AccionBancaria::obtenerAccion($idAccion);
            
            if(!$accion) {
                $payload = json_encode(array("error" => "Accion no encontrada"));
            }
            else {
                if(Cuenta::realizarAjuste($accion, $motivo)) {
                    $payload = json_encode(array("mensaje" => "Ajuste realizado con exito"));
                }
                else {
                    $payload = json_encode(array("error" => "Error al realizar el ajuste"));
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