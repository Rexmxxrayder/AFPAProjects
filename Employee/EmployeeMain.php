<?php
require_once('Employee.php');
require_once('EmployeesInfo.php');
require_once('EmployeeVisualInfo.php');
require_once('CEO.php');

$americAgence = new Agence("Unity", "489 State Street", "205205", "CostaSandra", "Luncheon_Voucher");
$employees = new EmployeesInfo();
$employees->AddEmployee(new Employee("Peter", "Parker", "2024-01-05", "WebDesigner", 20, "WebDeveloppement", $americAgence));
$employees->AddEmployee(new Employee("Bruce", "Banner", "2018-07-14", "Scientist", 35, "Research", $americAgence, array(12,19)));
$employees->AddEmployee(new CEO("Tony", "Stark", "2000-03-15", "CEO", 200, "CEO", $americAgence, array(5)));
$employees->AddEmployee(new Employee("Natasha", "Roumanof", "2017-06-18", "Unknown", 38, "Restricted", $americAgence));
$employees->AddEmployee(new Employee("Steve", "Rogers", "2016-10-10", "Manager", 50, "Management", $americAgence , array(18, 11, 14, 40)));


// $dateTimeNow = new DateTime('now');
// foreach($employees->GetEmployees() as $employee){
//     EmployeeVisualInfo::PrintBonus($employee);
//     TransfertBonus($employee, new DateTime("2021-03-15"));
//     EmployeeVisualInfo::PrintChristmasChecks($employee);
//     EmployeeVisualInfo::PrintCanBenefitFromHolidayChecks($employee);
//     if($dateTimeNow->format('d') === "30" && $dateTimeNow->format('m') === "11"){
//         TransfertBonus($employee, $dateTimeNow);
//     }
// }

//EmployeeVisualInfo::PrintEmployeesNumber($employees);
//EmployeeVisualInfo::PrintDataEmployees($employees->GetSortEmployeesByNameSurname());
//EmployeeVisualInfo::PrintDataEmployees($employees->GetSortEmployeesByServiceNameSurname());?>
<strong><?php
//EmployeeVisualInfo::PrintTotalCost($employees);
?>
</strong><?php
function TransfertBonus($employee, $transfertDate){
    print("The bonus of . " . $employee->GetName() . $employee->GetSurname() . "worth of " . $employee->GetBonus() . "€ will be send to the bank the " . $transfertDate->format('l jS \o\f F Y') . ".\n");
}

?>

