<?php

class Agence{
    private $name;
    private $adress;
    private $zipCode;
    private $city;
    private $restaurationMode;

    function __construct($name, $adress, $zipCode, $city, $restaurationMode) {
        $this->name = $name;
        $this->adress = $adress;
        $this->zipCode = $zipCode;
        $this->city = $city;
        $this->restaurationMode = $restaurationMode;
    }

    public function GetName() {
        return $this->name;
    }

    public function SetName($adress) {
        $this->adress = $adress;
        return $this;
    }

    public function GetAdress() {
        return $this->adress;
    }
 
    public function SetAdress($adress) {
        $this->adress = $adress;
        return $this;
    }

    public function GetZipCode() {
        return $this->zipCode;
    }

    public function SetZipCode($zipCode) {
        $this->zipCode = $zipCode;
        return $this;
    }

    public function GetCity() {
        return $this->city;
    }
 
    public function SetCity($city) {
        $this->city = $city;
        return $this;
    }

    public function GetRestaurationMode() {
        return $this->restaurationMode;
    }

    public function SetRestaurationMode($restaurationMode) {
        $this->restaurationMode = $restaurationMode;
        return $this;
    }
}
?>