<?php
require_once('Agence.php');
class Employee{
    private $name;
    private $surname;
    private $hiringDate; //DateTime
    private $jobRole;
    private $salaryInK;
    private $service;
    private $agence;
    private $children;

    public function GetName() {
        return $this->name;
    }

    public function SetName($name) {
        $this->name = $name;
        return $this;
    }

    public function GetSurname() {
        return $this->surname;
    }

    public function SetSurname($surname) {
        $this->surname = $surname;
        return $this;
    }

    public function GetHiringDate() {
        return $this->hiringDate;
    }

    public function SetHiringDate($hiringDate) {
        $this->hiringDate = $hiringDate;
        return $this;
    }

    public function GetJobRole() {
        return $this->jobRole;
    }

    public function SetJobRole($jobRole) {
        $this->jobRole = $jobRole;
        return $this;
    }

    public function GetSalaryInK() {
        return $this->salaryInK;
    }

    public function SetSalaryInK($salaryInK) {
        $this->salaryInK = $salaryInK;

        return $this;
    }
    
    function GetService() {
        return $this->service;
    }

    public function SetService($service) {
        $this->service = $service;

        return $this;
    }

    public function GetAgence() {
        return $this->agence;
    }

    public function SetAgence($agence) {
        $this->agence = $agence;

        return $this;
    }

    public function GetChildren() {
        return $this->children;
    }

    public function SetChildren($children) {
        $this->children = $children;

        return $this;
    }

    function __construct($name, $surname, $hiringDate, $jobRole, $salaryInK, $service, $agence, $children = NULL) {
        $this->name = $name;
        $this->surname = $surname;
        $this->hiringDate = date_create($hiringDate);
        $this->jobRole = $jobRole;
        $this->salaryInK = $salaryInK;
        $this->service = $service;
        $this->agence = $agence;
        if($children == NULL){
            $this->children = array();
        }else{
            $this->children = $children;
        }
        
    }

    static function SortByNameSurname($a, $b){
        $result = strcmp($a->name,$b->name);
        if($result == 0){
            $result = strcmp($a->surname,$b->surname);
        }

        return $result;
    }

    static function SortByServiceNameSurname($a, $b){
        $result = strcmp($a->service,$b->service);
        if($result == 0){
            $result = strcmp($a->name,$b->name);
            if($result == 0){
                $result = strcmp($a->surname,$b->surname);
            }
        }

        return $result;
    }


    function YearActivity(){
        $yearActivity = date_diff(new DateTime('now'),$this->hiringDate);
        $interval = $yearActivity->format('%Y') . ".\n";
        return $interval; 
    }

    function GetBonus(){
        return ($this->YearActivity() * $this->salaryInK * 2+ $this->salaryInK * 5) * 10; //multiply by 10 cause salary is in K
    }

    function CanBenefitFromHolidayChecks(){
        return $this->YearActivity() > 1;
    }

    function CanBenefitFromChristmasChecks(){
        if(count($this->children) < 0){
            return false;
        }

        foreach($this->children as $age){
            if($age <= 18)
            return true;
        }

        return false;
    }

    function GetChristmasChecks(){
        $ChristmasChecks = array("10" => 0, "15" => 0, "18" => 0,);
        if($this->CanBenefitFromChristmasChecks()){      
            foreach($this->children as $age){
                switch(true){
                    case $age <= 10 :
                        $ChristmasChecks["10"]++;
                    break;
                    case $age <= 15 :
                        $ChristmasChecks["15"]++;
                    break;
                    case $age <= 18 :
                        $ChristmasChecks["18"]++;
                    break;
                }
            }

        }
        return $ChristmasChecks;
    }

}

?>