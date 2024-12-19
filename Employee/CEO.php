<?php
require_once('Employee.php');
class CEO extends Employee{
    function GetBonus(){
        return ($this->YearActivity() * $this->GetSalaryInK() * 3+ $this->GetSalaryInK() * 7) * 10; //multiply by 10 cause salary is in K
    }
}
?>