<?php

class Utils {
    public static function GenerarNumero6Digitos() {
        return strval(rand(100000, 999999)); 
    }

    public static function ObtenerDiaAnterior() {
        $fecha_actual = new DateTime(); 
        $fecha_anterior = $fecha_actual->modify('-1 day'); 
        // Resta un dia para obtener la fecha de ayer

        return $fecha_anterior->format('Y-m-d');
    }

    public static function ValidarNumeroPositivo($numero) {
        if(is_numeric($numero) && $numero >= 0) {
            return true;
        }

        return false;
    }

    public static function ValidarTipoDocumento($documento) {
        if($documento == 'DNI' || $documento == 'CI' || $documento == 'Pasaporte') {
            return true;
        }

        return false;
    }

    public static function ValidarEmail($email) {
        if(strpos($email, '@') !== false && strpos($email, '.com') !== false) {
            return true;
        }
        
        return false;
    }

    public static function ValidarTipoCuenta($tipoCuenta) {
        if($tipoCuenta == 'CA$' || $tipoCuenta == 'CAU$S' || $tipoCuenta == 'CC$' || $tipoCuenta == 'CCU$S') {
            return true;
        }

        return false;
    }
}

?>