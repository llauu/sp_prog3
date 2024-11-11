<?php
require_once './utils/Utils.php';
require_once './db/AccesoDatos.php';

class Cuenta {
    public $nroCuenta;
    public $nombre;
    public $apellido;
    public $tipoDocumento; 
    public $nroDocumento;
    public $email;
    public $clave;
    public $tipoCuenta;// CA$ CAU$S CC$ CCU$S
    public $saldo;
    

    public static function CuentaValido($nombre, $apellido, $tipoDocumento, $nroDocumento, $email, $tipoCuenta, $saldo) {
        if(Utils::ValidarTipoDocumento($tipoDocumento) && Utils::ValidarNumeroPositivo($nroDocumento) && 
        Utils::ValidarEmail($email) && Utils::ValidarTipoCuenta($tipoCuenta) && Utils::ValidarNumeroPositivo($saldo) &&
        is_string($nombre) && is_string($apellido)) {
            return true;
        }

        return false;
    }
    

    public function crearCuenta() {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO cuenta (nombre, apellido, tipoDocumento, nroDocumento, email, clave, tipoCuenta, saldo) VALUES (:nombre, :apellido, :tipoDocumento, :nroDocumento, :email, :clave, :tipoCuenta, :saldo)");
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':apellido', $this->apellido, PDO::PARAM_STR);
        $consulta->bindValue(':tipoDocumento', $this->tipoDocumento, PDO::PARAM_STR);
        $consulta->bindValue(':nroDocumento', $this->nroDocumento, PDO::PARAM_STR);
        $consulta->bindValue(':email', $this->email, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $this->clave, PDO::PARAM_STR);
        $consulta->bindValue(':tipoCuenta', $this->tipoCuenta, PDO::PARAM_STR);
        $consulta->bindValue(':saldo', $this->saldo, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }


    public static function obtenerTodos() {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT nroCuenta, nombre, apellido, tipoDocumento, nroDocumento, email, clave, tipoCuenta, saldo FROM cuenta WHERE fechaBaja IS NULL");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Cuenta');
    }


    public static function obtenerCuentaPorNroCuenta($nroCuenta) {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT nroCuenta, nombre, apellido, tipoDocumento, nroDocumento, email, clave, tipoCuenta, saldo FROM cuenta WHERE nroCuenta = :nroCuenta AND fechaBaja IS NULL");
        $consulta->bindValue(':nroCuenta', $nroCuenta, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Cuenta');
    }


    public static function obtenerCuenta($nroCuenta, $tipoCuenta) {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT nroCuenta, nombre, apellido, tipoDocumento, nroDocumento, email, clave, tipoCuenta, saldo FROM cuenta WHERE nroCuenta = :nroCuenta AND tipoCuenta = :tipoCuenta AND fechaBaja IS NULL");
        $consulta->bindValue(':nroCuenta', $nroCuenta, PDO::PARAM_INT);
        $consulta->bindValue(':tipoCuenta', $tipoCuenta, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Cuenta');
    }


    public static function obtenerSaldo($nroCuenta, $tipoCuenta) {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT tipoCuenta, saldo FROM cuenta WHERE nroCuenta = :nroCuenta AND tipoCuenta = :tipoCuenta AND fechaBaja IS NULL");
        $consulta->bindValue(':nroCuenta', $nroCuenta, PDO::PARAM_INT);
        $consulta->bindValue(':tipoCuenta', $tipoCuenta, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetch(PDO::FETCH_ASSOC);
    }


    public static function obtenerCuentaPorDocumentoYTipo($nroDocumento, $tipoDocumento) {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT nroCuenta, nombre, apellido, tipoDocumento, nroDocumento, email, clave, tipoCuenta, saldo FROM cuenta WHERE nroDocumento = :nroDocumento AND tipoDocumento = :tipoDocumento");
        $consulta->bindValue(':nroDocumento', $nroDocumento, PDO::PARAM_STR);
        $consulta->bindValue(':tipoDocumento', $tipoDocumento, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Cuenta');
    }


    public static function modificarCuenta($nroCuenta, $nombre, $apellido, $tipoDocumento, $nroDocumento, $email, $clave, $tipoCuenta, $saldo) {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE cuenta SET nombre = :nombre, apellido = :apellido, tipoDocumento = :tipoDocumento, nroDocumento = :nroDocumento, email = :email, clave = :clave, tipoCuenta = :tipoCuenta, saldo = :saldo WHERE nroCuenta = :nroCuenta");
        $consulta->bindValue(':nroCuenta', $nroCuenta, PDO::PARAM_INT);
        $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $consulta->bindValue(':apellido', $apellido, PDO::PARAM_STR);
        $consulta->bindValue(':tipoDocumento', $tipoDocumento, PDO::PARAM_STR);
        $consulta->bindValue(':nroDocumento', $nroDocumento, PDO::PARAM_STR);
        $consulta->bindValue(':email', $email, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $clave, PDO::PARAM_STR);
        $consulta->bindValue(':tipoCuenta', $tipoCuenta, PDO::PARAM_STR);
        $consulta->bindValue(':saldo', $saldo, PDO::PARAM_STR);

        return $consulta->execute();
    }


    public static function borrarCuenta($nroCuenta, $tipoDeCuenta) {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE cuenta SET fechaBaja = :fechaBaja WHERE nroCuenta = :nroCuenta AND tipoCuenta = :tipoDeCuenta");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':tipoDeCuenta', $tipoDeCuenta, PDO::PARAM_STR);
        $consulta->bindValue(':nroCuenta', $nroCuenta, PDO::PARAM_INT);
        $consulta->bindValue(':fechaBaja', date_format($fecha, 'Y-m-d H:i:s'));
        
        $consulta->execute();

        return $consulta->fetch(PDO::FETCH_ASSOC);
    }


    public static function validarCuentaUnica($nroDocumento, $tipoCuenta) {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT nroCuenta FROM cuenta WHERE nroDocumento = :nroDocumento AND tipoCuenta = :tipoCuenta AND fechaBaja IS NULL");
        $consulta->bindValue(':nroDocumento', $nroDocumento, PDO::PARAM_STR);
        $consulta->bindValue(':tipoCuenta', $tipoCuenta, PDO::PARAM_STR);
        $consulta->execute();

        if($consulta->fetch(PDO::FETCH_ASSOC)) {
            return false;
        }

        return true;
    }


    public static function actualizarSaldo($saldo, $nroCuenta, $tipoCuenta) {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE cuenta SET saldo = :saldo WHERE nroCuenta = :nroCuenta AND tipoCuenta = :tipoCuenta");
        $consulta->bindValue(':saldo', $saldo, PDO::PARAM_INT);
        $consulta->bindValue(':nroCuenta', $nroCuenta, PDO::PARAM_INT);
        $consulta->bindValue(':tipoCuenta', $tipoCuenta, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetch(PDO::FETCH_ASSOC);
    }


    public static function realizarDeposito($montoADepositar, $Cuenta) {
        $montoADepositar = floatval($montoADepositar);

        if(Utils::ValidarNumeroPositivo($montoADepositar)) {
            $Cuenta->saldo += $montoADepositar;

            self::actualizarSaldo($Cuenta->saldo, $Cuenta->nroCuenta, $Cuenta->tipoCuenta);
            $accionBancaria = new AccionBancaria();
            $accionBancaria->nroCuenta = $Cuenta->nroCuenta;
            $accionBancaria->monto = $montoADepositar;
            $accionBancaria->tipo = 'deposito';

            $accionBancaria->crearAccion();

            return $accionBancaria->id;
        }

        return -1;
    }


    public static function realizarRetiro($montoARetirar, $Cuenta) {
        $montoARetirar = floatval($montoARetirar);

        if(Utils::ValidarNumeroPositivo($montoARetirar)) {
            $Cuenta->saldo -= $montoARetirar;

            self::actualizarSaldo($Cuenta->saldo, $Cuenta->nroCuenta, $Cuenta->tipoCuenta);
            $accionBancaria = new AccionBancaria();
            $accionBancaria->nroCuenta = $Cuenta->nroCuenta;
            $accionBancaria->monto = $montoARetirar;
            $accionBancaria->tipo = 'retiro';

            $accionBancaria->crearAccion();
            
            return $accionBancaria->id;
        }

        return -1;
    }

    
    public static function realizarAjuste($accion, $motivo) {
        $cuenta = self::obtenerCuentaPorNroCuenta($accion->nroCuenta);
        $monto = floatval($accion->monto);
        
        if($accion->tipo === 'deposito') {
            $cuenta->saldo -= $monto;
        }
        else {
            $cuenta->saldo += $monto;
        }

        self::actualizarSaldo($cuenta->saldo, $cuenta->nroCuenta, $cuenta->tipoCuenta);

        $ajuste = new Ajuste();
        $ajuste->idAccionAjustada = $accion->id;
        $ajuste->motivo = $motivo;

        $ajuste->crearAjuste();

        return true;
    }


    public static function MoverImagenCuentaEliminado($nroCuenta, $tipoCuenta) {
        $extensionesValidas = array('.jpg', '.jpeg', '.png');
        $carpetaOrigen = './ImagenesDeCuentas/2023/';
        $carpetaDestino = './ImagenesBackupCuentas/2023/';

        foreach($extensionesValidas as $extension) {
            $nombreImagen = $nroCuenta.$tipoCuenta.$extension;
            $rutaOrigen = $carpetaOrigen.$nombreImagen;

            if(file_exists($rutaOrigen)) {
                $rutaDestino = $carpetaDestino.$nombreImagen;

                if(rename($rutaOrigen, $rutaDestino)) {
                    return true;
                }
                else {
                    return false;
                }
            }
        }

        return false;
    }
    
    
    public static function validarEmailYClave($email, $clave) {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM cuenta WHERE email = :email AND clave = :clave");
        $consulta->bindValue(':email', $email, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $clave, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->rowCount() > 0;
    }

    
    public static function ObtenerCuentaPorEmail($email) {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM cuenta WHERE email = :email");
        $consulta->bindValue(':email', $email, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Cuenta');
    }
}

?>