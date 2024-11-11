<?php
require_once './models/Cuenta.php';
require_once './interfaces/IApiUsable.php';

class ControllerCuenta extends Cuenta implements IApiUsable {
    public function CargarUno($request, $response, $args) {
        $parametros = $request->getParsedBody();
        $files = $request->getUploadedFiles();

        if(isset($parametros['nombre']) && isset($parametros['apellido']) && isset($parametros['tipoDocumento']) && 
        isset($parametros['nroDocumento']) && isset($parametros['email']) && isset($parametros['clave']) && isset($parametros['tipoDeCuenta']) && 
        isset($files['imagenCuenta'])) 
        {
            // Seteo las extensiones de imagen que quiero permitir
            $extensionesValidas = array('jpg', 'jpeg', 'png');
            
            // Obtengo la extension de la imagen subida
            $extension = pathinfo($files['imagenCuenta']->getClientFilename(), PATHINFO_EXTENSION);
        
            if(in_array($extension, $extensionesValidas)) {
                $nombre = $parametros['nombre'];
                $apellido = $parametros['apellido'];
                $tipoDocumento = $parametros['tipoDocumento'];
                $nroDocumento = $parametros['nroDocumento'];
                $email = $parametros['email'];
                $clave = $parametros['clave'];
                $tipoDeCuenta = $parametros['tipoDeCuenta'];
        
                if(isset($parametros['saldo'])) {
                    $saldo = $parametros['saldo'];
                }
                else {
                    $saldo = 0;
                }
                
                if(Cuenta::CuentaValido($nombre, $apellido, $tipoDocumento, $nroDocumento, $email, $tipoDeCuenta, $saldo)) {
                    if(Cuenta::validarCuentaUnica($nroDocumento, $tipoDeCuenta)) {
                        $Cuenta = new Cuenta();

                        $Cuenta->nombre = $nombre;
                        $Cuenta->apellido = $apellido;
                        $Cuenta->tipoDocumento = $tipoDocumento;
                        $Cuenta->nroDocumento = $nroDocumento;
                        $Cuenta->email = $email;
                        $Cuenta->clave = $clave;
                        $Cuenta->tipoCuenta = $tipoDeCuenta;
                        $Cuenta->saldo = floatval($saldo);
    
                        $nroCuenta = $Cuenta->crearCuenta();
    
                        // Muevo la imagen al directorio permanente
                        $destino = "./ImagenesDeCuentas/2023/".$nroCuenta.$tipoDeCuenta.".".$extension;
                        $files['imagenCuenta']->moveTo($destino);
    
                        $payload = json_encode(array("mensaje" => "Cuenta creado con exito. Su numero de cuenta es #$nroCuenta"));
                    }
                    else {
                        $payload = json_encode(array("error" => "Ya existe un Cuenta con el mismo numero de documento y tipo de cuenta"));
                    
                    }
                }
                else {
                    $payload = json_encode(['error' => 'Parametros ingresados no validos.']);
                }
            }
            else {
                $payload = json_encode(['error' => 'La extension de la imagen subida no es valida. Extensiones validas: jpg, jpeg, png']);
            }
        }
        else {
            $payload = json_encode(['error' => 'Faltan parametros']);
        }

        $response->getBody()->write($payload);
    
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args) {
        $nroCuenta = $args['nroCuenta'];
        $tipoDeCuenta = $args['tipoDeCuenta'];

        $Cuenta = Cuenta::obtenerCuenta($nroCuenta, $tipoDeCuenta);

        if(!$Cuenta) {
            $Cuenta = array("error" => "Cuenta no encontrado");
        }
        else {
            $payload = json_encode($Cuenta);
        }


        $response->getBody()->write($payload);
        
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args) {
        $lista = Cuenta::obtenerTodos();
        $payload = json_encode(array("listaCuentas" => $lista));
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerSaldo($request, $response, $args) {
        $params = $request->getQueryParams();

        $tipoDeCuenta = $params['tipoDeCuenta'];
        $nroCuenta = $args['nroCuenta'];
        
        $arr = Cuenta::obtenerSaldo($nroCuenta, $tipoDeCuenta);

        if(!$arr) {
            $payload = json_encode(array("error" => "Cuenta no encontrado"));
        }
        else {
            $payload = json_encode(array("mensaje" => $arr));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args) {
        $parametros = $request->getParsedBody();

        if(isset($parametros['nroCuenta']) && isset($parametros['nombre']) && isset($parametros['apellido']) && isset($parametros['tipoDocumento']) 
            && isset($parametros['nroDocumento']) && isset($parametros['email']) && isset($parametros['clave']) && isset($parametros['tipoDeCuenta']) && isset($parametros['saldo']))
        {
            $nroCuenta = $parametros['nroCuenta'];
            $nombre = $parametros['nombre'];
            $apellido = $parametros['apellido'];
            $tipoDocumento = $parametros['tipoDocumento'];
            $nroDocumento = $parametros['nroDocumento'];
            $email = $parametros['email'];
            $clave = $parametros['clave'];
            $tipoDeCuenta = $parametros['tipoDeCuenta'];
            $saldo = $parametros['saldo'];

            if(Cuenta::obtenerCuentaPorNroCuenta($nroCuenta)) {
                Cuenta::modificarCuenta($nroCuenta, $nombre, $apellido, $tipoDocumento, $nroDocumento, $email, $clave, $tipoDeCuenta, $saldo);

                $payload = json_encode(array("mensaje" => "Cuenta modificado con exito"));
            }
            else {
                $payload = json_encode(array("error" => "El numero de cuenta ingresado no existe"));
            } 
        }
        else {
            $payload = json_encode(array("error" => "Parametros insuficientes"));    
        }

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args) {
        $parametros = $request->getParsedBody();

        if(isset($parametros['nroCuenta']) && isset($parametros['tipoDeCuenta'])) {
            $nroCuenta = $parametros['nroCuenta'];
            $tipoDeCuenta = $parametros['tipoDeCuenta'];

            if(Cuenta::obtenerCuenta($nroCuenta, $tipoDeCuenta)) {
                Cuenta::borrarCuenta($nroCuenta, $tipoDeCuenta);
                Cuenta::MoverImagenCuentaEliminado($nroCuenta, $tipoDeCuenta);
                $payload = json_encode(array("mensaje" => "Cuenta borrado con exito"));
            }
            else {
                $payload = json_encode(array("error" => "El Cuenta ingresado no existe"));
            }
        }
        else {
            $payload = json_encode(array("error" => "Parametros insuficientes"));
        }

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
}