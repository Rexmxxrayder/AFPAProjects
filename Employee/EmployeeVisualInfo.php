<?php
require_once('Employee.php');
require_once('Agence.php');
class EmployeeVisualInfo{

    public static function GetAgenceStringData($agence){
        $stringData = "Name : " . $agence->GetName() . " / ";
        $stringData .= "Adress : " . $agence->GetAdress() . " / ";
        $stringData .= "ZipCode : " .$agence->GetZipCode() . " / ";
        $stringData .= "City : " . $agence->GetCity() . " / ";
        $stringData .= "Restauration Mode : " . $agence->GetRestaurationMode();
        return  $stringData;
    }

    public static function GetEmployeeStringData($employee){
        $stringData = "Name : " . $employee->GetName() ." / ";
        $stringData .= "Surname : " . $employee->GetSurname() ." / ";
        $stringData .= "HiringDate : " . EmployeeVisualInfo::GetHiringDateInString($employee) . " / ";
        $stringData .= "Role : " . $employee->GetJobRole() ." / ";
        $stringData .= "Salary (in K) : " . $employee->GetSalaryInK() ." / ";
        $stringData .= "Service : " . $employee->GetService() ." / ";
        $stringData .= "Agence : (" . EmployeeVisualInfo::GetAgenceStringData($employee->GetAgence()) . ")";
        $children = $employee->GetChildren();
        if(count($children) > 0){
            $stringData .= "Children Age : ";
            foreach($children as $age){
                $stringData .= $age . " ";
            }
        }
        return  $stringData;
    }

    public static function GetHiringDateInString($employee){
        return $employee->GetHiringDate()->format('l jS \o\f F Y');
    }

    
    public static function PrintTotalCost($employee){
        print "The total cost of the employees is ". $employee->GetTotalCost() . "€\n";
    }

    public static function PrintChristmasChecks($employee){
        $ChristmasChecks = $employee->GetChristmasChecks();
        $stringData = "";
        foreach($ChristmasChecks as $typeCheck => $number){
            if($number == 0){
                continue;
            }
            $stringData .= $number . " Check Of " . $typeCheck . "€ for Christmas ";
        }
    }

    public static function PrintDataEmployees($employeeArr){
        foreach($employeeArr as $employee){
            print EmployeeVisualInfo::GetEmployeeStringData($employee) . "\n";
        }
    }
    
    public static function PrintEmployeesNumber($employeeInfo){
        $isMoreThanOneEmployee =  $employeeInfo->GetEmployeesNumber() > 1;
        print "There " . ($isMoreThanOneEmployee ? "are " : "is ") . $employeeInfo->GetEmployeesNumber() . " Employee" . ($isMoreThanOneEmployee ? "s" : "") . "\n";
    }

    public static function PrintBonus($employee){
        print "the bonus of " . $employee->GetName() . " " . $employee->GetSurname() . " is " . $employee->GetBonus() . "\n";
    }

    public static function PrintCanBenefitFromHolidayChecks($employee){
        print $employee->GetName() . " " . $employee->GetSurname() . " " . ($employee->CanBenefitFromHolidayChecks() ? "Can Benefit From Holiday Checks" :  "Cannot Benefit From Holiday Checks") . "\n";
    }
}
?>

