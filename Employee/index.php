<?php 
session_start();
    require_once "EmployeeMain.php";
    require_once "EmployeeVisualInfo.php";

?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Employee Management</h1>
    </header>

    <div class="masseSalarial">
        <h3>Masse Salariale(En euros)</h3>
        <p>
            <?php
                print $employees->GetTotalCost();
             ?> 
        Euros</p>
    </div>

    <table>
        <tr>
            <th>Name</th>
            <th>Surname</th>
            <th>Hiring Date</th>
            <th>Salary</th>
            <th>Job role</th>
            <th>Service</th>
            <th>Agence</th>
            <th>Children</th>
        </tr>
            <?php foreach($employees->GetEmployees() as $employee) : ?>
                <tr>
                    <td><?php print $employee->GetName() ?></td>
                    <td><?php print $employee->GetSurname() ?></td>
                    <td><?php print EmployeeVisualInfo::GetHiringDateInString($employee) ?></td>
                    <td><?php print $employee->GetSalaryInK() * 1000 ?></td>
                    <td><?php print $employee->GetJobRole() ?></td>
                    <td><?php print $employee->GetService() ?></td>
                    <td><?php print $employee->GetAgence()->GetName() ?></td>
                    <td><?php foreach($employee->GetChildren() as $children) {
                        print $children . " ";
                    }
                    ?></td>
                </tr>
            <?php endforeach ?>
    </table>








    <footer></footer>
</body>
</html>