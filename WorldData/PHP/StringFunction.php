<?php
class StringFunction
{
    public static function ToUpperSpecialChar($string)
    {
        return str_replace(array("é", "ï"), array("É", "Ï"), strtoupper($string));
    }

    public static function ToLowerSpecialChar($string)
    {
        return str_replace(array("É", "Ï"), array("é", "ï"), strtolower($string));
    }
}