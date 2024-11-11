<?php 
require_once './utils/Utils.php';
require_once './models/AccionBancaria.php';

class ControllerMovimientos {
    public function ObtenerDepositos($request, $response, $args) {
        $parametros = $request->getQueryParams();

        if(isset($parametros['tipoDeCuenta']) && isset($parametros['nroCuenta'])) {
            $nroCuenta = $parametros['nroCuenta'];
            $tipoDeCuenta = $parametros['tipoDeCuenta'];
            $desdeFecha = $parametros['desdeFecha'];
            $hastaFecha = $parametros['hastaFecha'];

            $Cuenta = Cuenta::obtenerCuentaPorNroCuenta($nroCuenta);

            if(!$Cuenta) {
                $payload = json_encode(array("error" => "Cuenta no encontrado"));
            }
            else {
                if(!isset($parametros['fecha'])) {
                    $fecha = Utils::ObtenerDiaAnterior();
                }
                else {
                    $fecha = $parametros['fecha'];
                }

                $montoPorTipoCuenta = AccionBancaria::obtenerTotalDepositadoPorTipoCuenta($tipoDeCuenta, $fecha);
                $depositosPorCuenta = AccionBancaria::obtenerDepositosPorCuenta($nroCuenta);
                $depositosEntreFechasOrdenados = AccionBancaria::obtenerDepositosEntreFechas($desdeFecha, $hastaFecha);
                $depositosPorTipoCuenta = AccionBancaria::obtenerDepositosPorTipoCuenta($tipoDeCuenta);

                $payload = json_encode(array("montoDepositadoPorTipoCuentaEnLaFechaIngresada" => $montoPorTipoCuenta, "depositosDeUnCuentaEnParticular" => $depositosPorCuenta, "depositosEntreFechasOrdenadosPorNombre" => $depositosEntreFechasOrdenados, "depositosPorTipoCuenta" => $depositosPorTipoCuenta));
            }
        }
        else {
            $payload = json_encode(array("error" => "Faltan datos"));
        }
        
        $response->getBody()->write($payload);
    
        return $response
            ->withHeader('Content-Type', 'application/json');
    }


    public function ObtenerRetiros($request, $response, $args) {
        $parametros = $request->getQueryParams();

        if(isset($parametros['tipoDeCuenta']) && isset($parametros['nroCuenta'])) {
            $nroCuenta = $parametros['nroCuenta'];
            $tipoDeCuenta = $parametros['tipoDeCuenta'];
            $desdeFecha = $parametros['desdeFecha'];
            $hastaFecha = $parametros['hastaFecha'];

            $Cuenta = Cuenta::obtenerCuentaPorNroCuenta($nroCuenta);

            if(!$Cuenta) {
                $payload = json_encode(array("error" => "Cuenta no encontrado"));
            }
            else {
                if(!isset($parametros['fecha'])) {
                    $fecha = Utils::ObtenerDiaAnterior();
                }
                else {
                    $fecha = $parametros['fecha'];
                }

                $montoPorTipoCuenta = AccionBancaria::obtenerTotalRetiradoPorTipoCuenta($tipoDeCuenta, $fecha);
                $retirosEntreFechasOrdenados = AccionBancaria::obtenerRetirosEntreFechas($desdeFecha, $hastaFecha);
                $retirosPorCuenta = AccionBancaria::obtenerRetirosPorCuenta($nroCuenta);
                $retirosPorTipoCuenta = AccionBancaria::obtenerRetirosPorTipoCuenta($tipoDeCuenta);

                $payload = json_encode(array("montoRetiradoPorTipoCuenta" => $montoPorTipoCuenta, "retirosDeUnCuentaEnParticular" => $retirosPorCuenta, "retirosEntreFechasOrdenadosPorNombre" => $retirosEntreFechasOrdenados, "retirosPorTipoCuenta" => $retirosPorTipoCuenta));
            }
        }
        else {
            $payload = json_encode(array("error" => "Faltan datos"));
        }
        
        $response->getBody()->write($payload);
    
        return $response
            ->withHeader('Content-Type', 'application/json');
    }


    public function ObtenerTodos($request, $response, $args) {
        $payload = json_encode(array("data" => AccionBancaria::obtenerTodasLasAccionesPorCuenta()));

        $response->getBody()->write($payload);
    
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
}
?>