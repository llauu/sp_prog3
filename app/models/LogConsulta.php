<?php
require_once './db/AccesoDatos.php';

class LogConsulta {
    public $id;
    public $email;
    public $accion;
    public $url;
    public $hora;

    public function crearLog() {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO log_consultas (email, accion, url, hora) VALUES (:email, :accion, :url, :hora)");
        $consulta->bindValue(':email', $this->email, PDO::PARAM_STR);
        $consulta->bindValue(':accion', $this->accion, PDO::PARAM_STR);
        $consulta->bindValue(':url', $this->url, PDO::PARAM_STR);
        $fecha = new DateTime(date("Y-m-d H:i:s"));
        $consulta->bindValue(':hora', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();


        return $objAccesoDatos->obtenerUltimoId();
    }
}

?>