<?php
require_once './db/AccesoDatos.php';

class AccionBancaria {
    public $id;
    public $nroCuenta;
    public $monto;
    public $tipo;
    public $fecha;

    public function crearAccion() {
        $objetoAccesoDato = AccesoDatos::obtenerInstancia(); 
        $consulta = $objetoAccesoDato->prepararConsulta("INSERT into accion (nroCuenta, monto, fecha, tipo) values(:nroCuenta, :monto, :fecha, :tipo)");
        $fecha = new DateTime(date("d-m-Y"));

        $consulta->bindValue(':nroCuenta', $this->nroCuenta, PDO::PARAM_STR);
        $consulta->bindValue(':monto', $this->monto, PDO::PARAM_INT);
        $consulta->bindValue(':fecha', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $consulta->execute();

        return $objetoAccesoDato->obtenerUltimoId();
    }

    public static function obtenerAccion($id) {
        $objetoAccesoDato = AccesoDatos::obtenerInstancia(); 
        $consulta = $objetoAccesoDato->prepararConsulta("SELECT * FROM accion WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('AccionBancaria');
    }

    public static function obtenerTotalDepositadoPorTipoCuenta($tipoCuenta, $fecha) {
        $objetoAccesoDato = AccesoDatos::obtenerInstancia(); 
        $consulta = $objetoAccesoDato->prepararConsulta("SELECT SUM(monto) AS total FROM accion INNER JOIN Cuenta ON accion.nroCuenta = Cuenta.nroCuenta WHERE Cuenta.tipoCuenta = :tipoCuenta AND accion.fecha = :fecha AND accion.tipo = 'deposito'");
        $consulta->bindValue(':tipoCuenta', $tipoCuenta, PDO::PARAM_STR);
        $consulta->bindValue(':fecha', $fecha);
        $consulta->execute();

        $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);

        return $resultados[0]['total'];
    }

    public static function obtenerDepositosPorCuenta($nroCuenta) {
        $objetoAccesoDato = AccesoDatos::obtenerInstancia(); 
        $consulta = $objetoAccesoDato->prepararConsulta("SELECT * FROM accion WHERE nroCuenta = :nroCuenta AND tipo = 'deposito'");
        $consulta->bindValue(':nroCuenta', $nroCuenta, PDO::PARAM_STR);
        $consulta->execute();

        $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);

        return $resultados;
    }

    public static function obtenerDepositosEntreFechas($desdeFecha, $hastaFecha) {
        $objetoAccesoDato = AccesoDatos::obtenerInstancia(); 
        $consulta = $objetoAccesoDato->prepararConsulta("SELECT accion.* FROM accion INNER JOIN Cuenta ON accion.nroCuenta = Cuenta.nroCuenta WHERE accion.fecha BETWEEN :desdeFecha AND :hastaFecha AND accion.tipo = 'deposito' ORDER BY Cuenta.nombre;");
        $consulta->bindValue(':desdeFecha', $desdeFecha);
        $consulta->bindValue(':hastaFecha', $hastaFecha);
        $consulta->execute();

        $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);

        return $resultados;
    }

    public static function obtenerDepositosPorTipoCuenta($tipoCuenta) {
        $objetoAccesoDato = AccesoDatos::obtenerInstancia(); 
        $consulta = $objetoAccesoDato->prepararConsulta("SELECT * FROM accion INNER JOIN Cuenta ON accion.nroCuenta = Cuenta.nroCuenta WHERE Cuenta.tipoCuenta = :tipoCuenta AND accion.tipo = 'deposito'");
        $consulta->bindValue(':tipoCuenta', $tipoCuenta, PDO::PARAM_STR);
        $consulta->execute();

        $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);

        return $resultados;
    } 


    // -------------------------------------------------------------------------------------
    public static function obtenerTotalRetiradoPorTipoCuenta($tipoCuenta, $fecha) {
        $objetoAccesoDato = AccesoDatos::obtenerInstancia(); 
        $consulta = $objetoAccesoDato->prepararConsulta("SELECT SUM(monto) AS total FROM accion INNER JOIN Cuenta ON accion.nroCuenta = Cuenta.nroCuenta WHERE Cuenta.tipoCuenta = :tipoCuenta AND accion.fecha = :fecha AND accion.tipo = 'retiro'");
        $consulta->bindValue(':tipoCuenta', $tipoCuenta, PDO::PARAM_STR);
        $consulta->bindValue(':fecha', $fecha);
        $consulta->execute();

        $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);

        return $resultados[0]['total'];
    }

    public static function obtenerRetirosPorCuenta($nroCuenta) {
        $objetoAccesoDato = AccesoDatos::obtenerInstancia(); 
        $consulta = $objetoAccesoDato->prepararConsulta("SELECT * FROM accion WHERE nroCuenta = :nroCuenta AND tipo = 'retiro'");
        $consulta->bindValue(':nroCuenta', $nroCuenta, PDO::PARAM_STR);
        $consulta->execute();

        $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);

        return $resultados;
    }

    public static function obtenerRetirosEntreFechas($desdeFecha, $hastaFecha) {
        $objetoAccesoDato = AccesoDatos::obtenerInstancia(); 
        $consulta = $objetoAccesoDato->prepararConsulta("SELECT accion.* FROM accion INNER JOIN Cuenta ON accion.nroCuenta = Cuenta.nroCuenta WHERE accion.fecha BETWEEN :desdeFecha AND :hastaFecha AND accion.tipo = 'retiro' ORDER BY Cuenta.nombre;");
        $consulta->bindValue(':desdeFecha', $desdeFecha);
        $consulta->bindValue(':hastaFecha', $hastaFecha);
        $consulta->execute();

        $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);

        return $resultados;
    }

    public static function obtenerRetirosPorTipoCuenta($tipoCuenta) {
        $objetoAccesoDato = AccesoDatos::obtenerInstancia(); 
        $consulta = $objetoAccesoDato->prepararConsulta("SELECT * FROM accion INNER JOIN Cuenta ON accion.nroCuenta = Cuenta.nroCuenta WHERE Cuenta.tipoCuenta = :tipoCuenta AND accion.tipo = 'retiro'");
        $consulta->bindValue(':tipoCuenta', $tipoCuenta, PDO::PARAM_STR);
        $consulta->execute();

        $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);

        return $resultados;
    }

    public static function obtenerTodasLasAccionesPorCuenta() {
        $objetoAccesoDato = AccesoDatos::obtenerInstancia(); 
        $consulta = $objetoAccesoDato->prepararConsulta("
            SELECT Cuenta.nombre, Cuenta.apellido, Cuenta.tipoDocumento, Cuenta.nroDocumento, Cuenta.email, Cuenta.tipoCuenta, Cuenta.saldo, accion.* 
            FROM accion 
            INNER JOIN Cuenta ON accion.nroCuenta = Cuenta.nroCuenta 
            ORDER BY Cuenta.nombre, Cuenta.apellido;
        ");
        $consulta->execute();

        $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);

        return $resultados;
    }
}

?>