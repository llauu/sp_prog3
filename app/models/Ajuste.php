<?php
include_once('./db/AccesoDatos.php');

class Ajuste {
    public $idAccionAjustada;
    public $motivo;
    
    public function crearAjuste() {
        $objetoAccesoDato = AccesoDatos::obtenerInstancia(); 
        $consulta = $objetoAccesoDato->prepararConsulta("INSERT into ajuste (idAccion, motivo) values(:idAccionAjustada, :motivo)");
        $consulta->bindValue(':idAccionAjustada', $this->idAccionAjustada, PDO::PARAM_INT);
        $consulta->bindValue(':motivo', $this->motivo, PDO::PARAM_STR);
        $consulta->execute();

        return $objetoAccesoDato->obtenerUltimoId();
    }
}

?>