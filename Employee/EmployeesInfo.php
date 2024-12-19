<?php
require_once('Employee.php');
class EmployeesInfo{
    private $employeesArr = array();

    function GetEmployees(){
        return $this->employeesArr;
    }

    function AddEmployee($employee){
        $this->employeesArr[] = $employee;
    }

    function GetEmployeesNumber(){
        return count($this->employeesArr);
    }

    function GetSortEmployeesByNameSurname(){
        $sortEmployees = $this->employeesArr;
        usort($sortEmployees, array("Employee", "Employee::SortByNameSurname"));
        return $sortEmployees;
    }
    
    function GetSortEmployeesByServiceNameSurname(){
        $sortEmployees = $this->employeesArr;
        usort($sortEmployees, array("Employee", "Employee::SortByServiceNameSurname"));
        return $sortEmployees;
    }

    function GetTotalCost(){
        $totalCost = 0;
        foreach($this->employeesArr as $employee){
            $totalCost += $employee->GetSalaryInK() * 1000; 
            $totalCost += $employee->GetBonus(); 
        }
    
        return $totalCost;
    }
}
?>