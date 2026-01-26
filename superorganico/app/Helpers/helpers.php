<?php

if (!function_exists('formatearBs')) {
    /**
     * Formatea un monto en bolivianos
     * 
     * @param float $monto
     * @return string
     */
    function formatearBs($monto)
    {
        return 'Bs. ' . number_format($monto, 2, '.', ',');
    }
}

if (!function_exists('formatearFecha')) {
    /**
     * Formatea una fecha en formato boliviano
     * 
     * @param mixed $fecha
     * @return string
     */
    function formatearFecha($fecha)
    {
        if ($fecha instanceof \DateTime) {
            return $fecha->format('d/m/Y');
        }
        
        if (is_string($fecha)) {
            return date('d/m/Y', strtotime($fecha));
        }
        
        return $fecha;
    }
}

if (!function_exists('formatearFechaHora')) {
    /**
     * Formatea una fecha y hora en formato boliviano
     * 
     * @param mixed $fecha
     * @return string
     */
    function formatearFechaHora($fecha)
    {
        if ($fecha instanceof \DateTime) {
            return $fecha->format('d/m/Y H:i');
        }
        
        if (is_string($fecha)) {
            return date('d/m/Y H:i', strtotime($fecha));
        }
        
        return $fecha;
    }
}
