<!DOCTYPE html>
<html>

<head>
    <title>WorldData</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <?php
    require_once('PHP\DAO.php');
    require_once('PHP\WorldQueries.php');
    require_once('PHP\StringFunction.php');

    enum TableMode
    {
        case Continent;
        case Region;
        case Country;
    }

    enum EditMode
    {
        case None;
        case Add;
        case Modify;
        case Delete;
    }

    function GetTableMode()
    {
        if (IsContinentMonde()) {
            return TableMode::Continent;
        }

        if (ContinentContainsRegion() && IsNoRegion()) {
            return TableMode::Region;
        }

        return TableMode::Country;
    }

    function GetEditMode()
    {
        if (GetTableMode() != TableMode::Country || !IsPostVariable('EditMode')) {
            return EditMode::None;
        }

        switch ($_POST['EditMode']) {
            case 'Add':
                return EditMode::Add;
            case 'Modify':
                return EditMode::Modify;
            case 'Delete':
                return EditMode::Delete;
            case 'None':
            default:
                return EditMode::None;
        }
    }

    function IsContinentMonde()
    {
        return $GLOBALS['currentContinent'] === $GLOBALS['world'];
    }

    function IsNoRegion()
    {
        return IsContinentMonde() || $GLOBALS['currentRegion'] === $GLOBALS['NoRegion'];
    }

    function ContinentContainsRegion()
    {
        return !$GLOBALS['regionsList'] == [];
    }

    function ContinentContainsRegionModify()
    {
        return !$GLOBALS['regionsModifyList'] == [];
    }

    function IsPostVariable($variable)
    {
        return isset($_POST[$variable]) && $_POST[$variable] != null;
    }

    function GetTableModeString()
    {
        switch (GetTableMode()) {
            case TableMode::Continent:
                return "Continents";
            case TableMode::Region:
                return "Régions";
            case TableMode::Country:
                if (ContinentContainsRegion()) {
                }
                return "Pays";
        }
    }

    function GetTableData()
    {
        $dao = $GLOBALS['DAO'];
        $data = [];
        switch (GetTableMode()) {
            case TableMode::Continent:
                $data = $dao->Query(WorldQueries::GetContinentsData());
                break;
            case TableMode::Region:
                $data = $dao->Query(WorldQueries::GetRegionsData($GLOBALS['currentContinent']));
                break;
            case TableMode::Country:
                if (IsNoRegion()) {
                    $data = $dao->Query(WorldQueries::GetCountryOfContinentData($GLOBALS['currentContinent']));
                } else {
                    $data = $dao->Query(WorldQueries::GetCountryOfRegionData($GLOBALS['currentRegion']));
                }
                break;
        }

        $tableData = [];
        foreach ($data as $row) {
            $rowData = [];
            foreach (WorldQueries::$ColumnsReturn as $columnName) {
                $rowData[] = str_replace(array("_"), array(""), $row[$columnName]); // retirer le '_' utiliser pour avoir la dernière ligne bien en dernier dans la query SQL
            }
            if (GetTableMode() == TableMode::Country) {
                $rowData[] = $row['ID'];
            }
            $tableData[] = $rowData;
        }
        //print $query;
        return $tableData;
    }


    function HtmlOption($optionValue, $optionName, $selected)
    {
        print "<option value=\"" . $optionValue . "\" ";
        if ($optionValue == $selected) {
            print "selected";
        }
        print "><b>" . $optionName . "</b></option>";
    }


    function IsCountryReady()
    {
        foreach (WorldQueries::$ColumnGeneralSQL as $column) {
            if ($column == 'region_id') {
                continue;
            }


            if (!IsPostVariable($column)) {
                return false;
            }
        }

        return true;
    }

    function IsCountryAdded()
    {
        return IsCountryReady() && IsPostVariable('AddCountry');
    }

    function VerifyCountryAdded()
    {
        if (IsCountryAdded()) {
            $countryData = [];
            foreach (WorldQueries::GetCountryColumns() as $data) {
                switch ($data) {
                    case 'continent_id':
                        $countryData[$data] = $GLOBALS['DAO']->Query(WorldQueries::GetContinentId($GLOBALS['currentContinent']))[0]['id_continent'];
                        break;
                    case 'region_id':
                        if (IsNoRegion()) {
                            $countryData[$data] = "";
                        } else {
                            $countryData[$data] = $GLOBALS['DAO']->Query(WorldQueries::GetRegionId($GLOBALS['currentRegion']))[0]['id_region'];
                        }
                        break;
                    case 'population_plus_65_pays':
                        $countryData[$data] =  $_POST['population_pays']  / 100 * $_POST['population_plus_65_pays'];
                        break;
                    default:
                        $countryData[$data] = $_POST[$data];
                        break;
                }
            }


            //print WorldQueries::AddCountry($countryData);
            $GLOBALS['DAO']->Query(WorldQueries::AddCountry($countryData));
            GoToSameParameterPage();
        }
    }

    function ShouldDeleteCountry()
    {
        if (IsPostVariable('DeleteCountry') && IsPostVariable('Confirmation') && $_POST['Confirmation'] == 'true') {
            $query = WorldQueries::DeleteCountry($_POST['DeleteCountry']);
            //print $query;
            $GLOBALS['DAO']->Query($query);
            GoToSameParameterPage();
        }
    }

    function ShouldModifyCountry()
    {
        if (IsPostVariable('ModifyCountry') && IsPostVariable('Confirmation') && $_POST['Confirmation'] == 'true') {
            $countryData = [];
            foreach (WorldQueries::GetCountryColumns() as $data) {
                switch ($data) {
                    case 'continent_id':         
                            $countryData[$data] = $GLOBALS['DAO']->Query(WorldQueries::GetContinentId($_POST['continent_id']))[0]['id_continent']; 
                        break;
                    case 'region_id':
                        if (IsPostVariable('region_id') && $_POST['region_id'] != $GLOBALS['NoRegion']) {
                            $countryData[$data] = $GLOBALS['DAO']->Query(WorldQueries::GetRegionId($_POST['region_id']))[0]['id_region'];
                        } else {
                            $countryData[$data] = "";
                        }
                        break;
                    case 'population_plus_65_pays':
                        $countryData[$data] =  (float)$_POST['population_pays']  / 100 * (float)$_POST['population_plus_65_pays'];
                        break;
                    default:
                        $countryData[$data] = $_POST[$data];
                        break;
                }
            }

            print WorldQueries::ModifyCountry($_POST['ModifyCountry'], $countryData);
            $GLOBALS['DAO']->Query(WorldQueries::ModifyCountry($_POST['ModifyCountry'], $countryData));
            GoToParameterPage($_POST['continent_id'], $_POST['region_id']);
        }
    }

    function GoToSameParameterPage()
    {
        $link = "Location: index.php";
        $link .= "?currentContinent=" . str_replace(" ", "+", $_GET['currentContinent']);
        if (isset($_GET['currentRegion'])) {
            $link .= "&currentRegion=" . str_replace(" ", "+", $_GET['currentRegion']);
        }
        header($link);
    }

    function GoToParameterPage($c, $r)
    {
        $link = "Location: index.php";
        $link .= "?currentContinent=" . str_replace(" ", "+", $c);
        if ($r != "") {
            $link .= "&currentRegion=" . str_replace(" ", "+", $r);
        } else {
            $link .= "&currentRegion=" . str_replace(" ", "+", $GLOBALS['NoRegion']);
        }
        header($link);
    }

    function GetColumn($table, $rowName)
    {
        $column = [];
        foreach ($table as $row) {
            $column[] = $row[$rowName];
        }

        return $column;
    }

    //keywords
    $world = "MONDE";
    $NoRegion = "NoRegion";


    $DAO = new DAO();
    $DAO->Connect();


    $continentsList = GetColumn($DAO->Query(WorldQueries::GetContinents()), "libelle_continent");
    $continentsListPlusWorld = $continentsList;
    array_unshift($continentsListPlusWorld, $world);
    $currentContinent = isset($_GET["currentContinent"]) ? $_GET["currentContinent"] : $world;
    $regionsList = GetColumn($DAO->Query(WorldQueries::GetRegions($currentContinent)), "libelle_region");
    $currentRegion = ContinentContainsRegion() && isset($_GET["currentRegion"]) ? $_GET["currentRegion"] : $NoRegion;
    $tableData = GetTableData();

    $currentModifyContinent = IsPostVariable('continent_id') ? $_POST['continent_id'] : $currentContinent;
    $regionsModifyList = GetColumn($DAO->Query(WorldQueries::GetRegions($currentModifyContinent)), "libelle_region");
    $currentModifyRegion = IsPostVariable('region_id') && ContinentContainsRegionModify() ? $_POST['region_id'] : $currentRegion;

    $columnsName = array(GetTableModeString(), 'Population totale <br><span style=font-family:OpenSansLight>(en milliers)</span>', 'Taux de natalité', 'Taux de mortalité', 'Espérance de vie', 'Taux de mortalité infantile', 'Nombre d’enfant(s) par femme', 'Taux de croissance', 'Part des 65 ans et plus <br><span style=font-family:OpenSansLight>(%)</span>');
    $columnsMinMax = array(array(0, 30000000), array(0, 1000), array(0, 1000), array(0, 200), array(0, 1000), array(0, 100), array(-1000, 1000), array(0.0, 100.0));
    $columnsHelp = array('', 'Nombre d’habitants', 'Nombre de naissances par an pour 1000 habitants', 'Nombre de décès par an pour 1000 habitants', 'Durée de vie moyenne en années', 'Nombre de décès d’enfants de moins d’un an pour mille naissances', 'Nombre d’enfant par femme', 'Nombre d’habitants en plus ou en moins par an, pour 1000 habitants  (somme des taux d’accroissements naturel et migratoire)', 'Proportion de personnes âgées de 65 ans et plus');

    //print_r($_POST);
    //print_r($tableData);
    // print(GetTableModeString());
    //print(IsPostVariable('EditMode') ? $_POST['EditMode'] : "None");
    ShouldModifyCountry();
    ShouldDeleteCountry();
    VerifyCountryAdded();

    //print(IsCountryReady() ? "True" : "False");
    //print(IsCountryAdded() ? "True" : "False");

    ?>

    <div class="MainDiv">
        <header>
            <h1 id="test">Tous les pays du monde</h1>
        </header>
        <div class="SelectTable">
            <form id="SelectForm" action="index.php">
                <div class="insideForm">
                    <label for="currentContinent"><b>Par continent<b></label><br>
                    <select class="LabelSelect" id="currentContinent" name="currentContinent" onchange="ReloadSelectForm()">
                        <?php foreach ($continentsListPlusWorld as $row) : ?>
                            <?php HtmlOption(StringFunction::ToUpperSpecialChar($row), StringFunction::ToUpperSpecialChar($row), StringFunction::ToUpperSpecialChar($currentContinent)); ?>
                        <?php endforeach ?>
                    </select>
                </div>
                <?php if (ContinentContainsRegion()) { ?>
                    <div class="insideForm">
                        <label class for="currentRegion">Par region</label><br>
                        <select class="LabelSelect" id="currentRegion" name="currentRegion" onchange="ReloadSelectForm()">
                            <?php HtmlOption($NoRegion, "--", $currentRegion); ?>
                            <?php foreach ($regionsList as $row) : ?>
                                <?php HtmlOption(StringFunction::ToLowerSpecialChar($row), $row, StringFunction::ToLowerSpecialChar($currentRegion)); ?>
                            <?php endforeach ?>
                        </select>
                    </div>
                <?php } ?>
            </form>
        </div>

        <div class="TableTitle">
            <h3><span style="color:#e9362f"><?php print "$currentContinent - " ?></span><span style="font-size:90%"><?php print (!IsNoRegion() ? "$currentRegion" . " - " : "") . "Estimations 2024 " ?></span></h3>
        </div>

        <div class="WorldTable">
            <form id="CountryForm" method="post">
                <table>
                    <?php switch (GetTableMode()):
                        case TableMode::Continent: ?>
                        <?php
                        case TableMode::Region: ?>
                            <tr>
                                <?php for ($i = 0; $i < count($columnsName); $i++) : ?>
                                    <th class="FirstChildTable">
                                        <?php if ($i != 0) : ?>
                                            <img class="tip" data-toggle="tooltip" title=<?php print "\"" . $columnsHelp[$i] . "\" " ?> src="Img/HelpIcon.svg" alt=<?php print "\"" . $columnsHelp[$i] . "\" " ?> data-original-title="Nombre d’habitants">
                                        <?php endif ?>
                                        <br><b><?php print ($i != 0) ? $columnsName[$i] : GetTableModeString(); ?></b>
                                    </th>

                                <?php endfor ?>
                            </tr>
                            <?php foreach ($tableData as $row) : ?>
                                <tr>
                                    <?php foreach ($row as $rowValue) : ?>
                                        <?php $i++ ?>
                                        <td><b><?php print((floatval($rowValue) != 0 && floatval($rowValue) < 1000) ? number_format((float)$rowValue, 2, '.', '') : $rowValue); ?></b></td>
                                    <?php endforeach ?>
                                </tr>
                            <?php endforeach ?>
                            <?php break; ?>
                        <?php
                        case TableMode::Country: ?>
                            <tr>
                                <th class="HiddenCell">

                                </th>
                                <th class="HiddenCell">
                                </th>
                                <?php for ($i = 0; $i < count($columnsName); $i++) : ?>
                                    <th class="FirstChildTable">
                                        <?php if ($i != 0) : ?>
                                            <img class="tip" data-toggle="tooltip" title=<?php print "\"" . $columnsHelp[$i] . "\" " ?> src="Img/HelpIcon.svg" alt=<?php print "\"" . $columnsHelp[$i] . "\" " ?> data-original-title="Nombre d’habitants">
                                        <?php endif ?>
                                        <br><b><?php print ($i != 0) ? $columnsName[$i] : GetTableModeString(); ?></b>
                                    </th>
                                <?php endfor ?>
                                <?php if (GetTableMode() == TableMode::Country) : ?>
                                    <th class="HiddenCell">
                                    </th>
                                    <th class="HiddenCell">
                                    </th>
                                <?php endif ?>
                            </tr>
                            <?php foreach ($tableData as $row) : ?>
                                <tr>
                                    <?php if (GetEditMode() == EditMode::Modify && $row[9] == $_POST['ModifyCountry'] && $row != $tableData[count($tableData) - 1]) : ?>
                                        <td class="HiddenCell">
                                            <select class="LabelSelect" id="Modify_continent_id" name="Modify_continent_id" onchange="<?php print "ModifyCountryFunc(" . $row[9] . ",false)" ?>">
                                                <?php foreach ($continentsList as $rowa) : ?>
                                                    <?php HtmlOption(StringFunction::ToUpperSpecialChar($rowa), StringFunction::ToUpperSpecialChar($rowa), $currentModifyContinent); ?>
                                                <?php endforeach ?>
                                            </select>
                                        </td>
                                        <td class="HiddenCell">
                                        <?php if (ContinentContainsRegionModify()) : ?>
                                            <select class="LabelSelect" id="Modify_region_id" name="Modify_region_id" onchange="<?php print "ModifyCountryFunc(" . $row[9] . ",false)" ?>">
                                                <?php foreach ($regionsModifyList as $rowb) : ?>
                                                    <?php HtmlOption($rowb, $rowb, $currentModifyRegion); ?>
                                                <?php endforeach ?>
                                            </select>
                                            <?php else : ?>
                                                <input type='hidden' id="Modify_region_id" name="Modify_region_id" value="">
                                            <?php endif ?>
                                        </td>
                                    <?php else : ?>
                                        <td class="HiddenCell">
                                        </td>
                                        <td class="HiddenCell">
                                        </td>
                                    <?php endif ?>
                                    <?php $i = -1; ?>
                                    <?php foreach ($row as $rowValue) : ?>
                                        <?php $i++ ?>
                                        <?php if ($i == 9) : ?>
                                            <?php continue; ?>
                                        <?php endif; ?>
                                        <td>
                                            <?php if (GetEditMode() == EditMode::Modify && $row[9] == $_POST['ModifyCountry'] && $row != $tableData[count($tableData) - 1]) : ?>
                                                <?php if ($row[0] == $rowValue) : ?>
                                                    <span>
                                                        <input style="text-align: right" type="text" id="Modify_libelle_pays" name="Modify_libelle_pays" value="<?php print $rowValue; ?>">
                                                    <?php else : ?>
                                                        <input style="text-align: right" type="number" id="<?php print "Modify_" . WorldQueries::$ColumnGeneralSQL[$i - 1] ?>" name="<?php print "Modify_" . WorldQueries::$ColumnGeneralSQL[$i - 1] ?>" min="<?php print $columnsMinMax[$i - 3][0] ?>" max="<?php print $columnsMinMax[$i - 3][1] ?>" value="<?php print $rowValue; ?>" step="any">
                                                    </span>
                                                <?php endif ?>
                                            <?php else : ?>
                                                <b><?php print((floatval($rowValue) != 0 && floatval($rowValue) < 1000) ? number_format((float)$rowValue, 2, '.', '') : $rowValue); ?></b>
                                            <?php endif ?>
                                        </td>
                                    <?php endforeach ?>
                                    <?php if ($row != $tableData[count($tableData) - 1]) : ?>
                                        <?php if (GetEditMode() == EditMode::Modify && $row[9] == $_POST['ModifyCountry']) : ?>
                                            <td class="HiddenCell">
                                                <button id="ModifyButton" onclick="SelectEditForm(<?php print 'None'; ?>)">Annuler Modification</button>
                                            </td>
                                            <td class="HiddenCell">
                                                <button style="background-color: red" onclick="<?php print "ModifyCountryFunc(" . $row[9] . ",true)" ?>">Confirmation Modification</button>
                                            </td>
                                        <?php elseif (GetEditMode() == EditMode::Delete && $row[9] == $_POST['DeleteCountry']) : ?>
                                            <td class="HiddenCell">
                                                <button id="ModifyButton" onclick="SelectEditForm(<?php print 'None'; ?>)">Annuler Suppression</button>
                                            </td>
                                            <td class="HiddenCell">
                                                <button style="background-color: red" onclick="<?php print "DeleteCountryFunc(" . $row[9] . ",true)" ?>">Confirmation Supression</button>
                                            </td>
                                        <?php else : ?>
                                            <td>
                                                <button onclick="<?php print "StartModifyCountryFunc(" . $row[9] . ",false)" ?>">Modifier le pays</button>
                                            </td>
                                            <td>
                                                <button onclick="<?php print "StartDeleteCountryFunc(" . $row[9] . ",false)" ?>">Supprimer le pays</button>
                                            </td>
                                        <?php endif ?>
                                    <?php else : ?>
                                        <td></td>
                                        <td></td>
                                    <?php endif ?>
                                </tr>
                            <?php endforeach ?>
                            <?php break; ?>
                    <?php endswitch ?>
                </table>
                <?php if (GetTableMode() == TableMode::Country) : ?>
                    <?php switch (GetEditMode()):
                        case EditMode::None: ?>
                            <button onclick=<?php print 'SelectEditForm("Add")' ?>>Ajouter un pays</button>
                            <?php break; ?>
                        <?php
                        case EditMode::Add: ?>
                            <table>
                                <tr>
                                    <td>
                                        <span>
                                            <input type="text" id="<?php print "Add_libelle_pays" ?>" name="<?php print "Add_libelle_pays" ?>" <?php if (IsPostVariable("libelle_pays")) {
                                                                                                                                                    print "value=\"" . $_POST["libelle_pays"] . "\"";
                                                                                                                                                }; ?>>
                                        </span>
                                    </td>
                                    <?php for ($i = 0; $i < count(WorldQueries::$ColumnGeneralSQL); $i++) : ?>
                                        <td>
                                            <span>
                                                <input type="number" id="<?php print "Add_" . WorldQueries::$ColumnGeneralSQL[$i] ?>" name="<?php print "Add_" . WorldQueries::$ColumnGeneralSQL[$i] ?>" min="<?php print $columnsMinMax[$i][0] ?>" max="<?php print $columnsMinMax[$i][1] ?>" <?php if (IsPostVariable(WorldQueries::$ColumnGeneralSQL[$i])) {
                                                                                                                                                                                                                                                                                                    print "value=\"" . $_POST[WorldQueries::$ColumnGeneralSQL[$i]] . "\"";
                                                                                                                                                                                                                                                                                                };  ?>>
                                            </span>
                                        </td>
                                    <?php endfor ?>
                                    <td class="HiddenCell">
                                        <button id="SubmitButton" onclick="AddCountryFunc()">Ajouter le pays</button>
                                    </td>
                                </tr>
                            </table>
                            <?php break; ?>
                    <?php endswitch; ?>
                    <input type='hidden' id="EditMode" name="EditMode" value="">
                    <input type='hidden' id="AddCountry" name="AddCountry" value="">
                    <input type='hidden' id="DeleteCountry" name="DeleteCountry" value="">
                    <input type='hidden' id="ModifyCountry" name="ModifyCountry" value="">
                    <input type='hidden' id="Confirmation" name="Confirmation" value="">
                    <?php foreach (WorldQueries::GetCountryColumns() as $column) : ?>
                        <input type='hidden' id="<?php print $column ?>" name="<?php print $column ?>" value="">
                    <?php endforeach; ?>
                <?php endif; ?>
            </form>
        </div>
    </div>
    <footer></footer>

    <script>
        function ReloadSelectForm() {
            document.getElementById("SelectForm").submit();
        }

        function SelectEditForm(type) {
            document.getElementById("EditMode").value = type;
            document.getElementById("CountryForm").submit();
        }

        function AddCountryFunc() {
            document.getElementById("AddCountry").value = true;
            <?php foreach (WorldQueries::GetCountryColumns() as $column) : ?>
                console.log("<?php print $column; ?>");
                <?php if ($column == "continent_id"): ?>
                    document.getElementById("<?php print $column; ?>").value = "<?php print $currentContinent; ?>";
                    <?php continue; ?>
                <?php endif; ?>
                <?php if ($column == "region_id") : ?>
                    document.getElementById("<?php print $column; ?>").value = "<?php print $currentRegion; ?>";
                    <?php continue; ?>
                <?php endif; ?>
                document.getElementById("<?php print $column; ?>").value = document.getElementById("<?php print "Add_" . $column; ?>").value;
            <?php endforeach; ?>
            SelectEditForm("Add");
        }

        function StartDeleteCountryFunc(idCountry) {
            document.getElementById("DeleteCountry").value = idCountry;
            SelectEditForm("Delete");
        }

        function DeleteCountryFunc(idCountry, confirmation) {
            document.getElementById("DeleteCountry").value = idCountry;
            document.getElementById("Confirmation").value = confirmation;
            document.getElementById("CountryForm").submit();
            SelectEditForm("Delete");
        }

        function StartModifyCountryFunc(idCountry) {
            document.getElementById("ModifyCountry").value = idCountry;
            SelectEditForm("Modify");
        } ///////////////////////////////////////////////////////////////////////////////////////////

        function ModifyCountryFunc(idCountry, confirmation) {
            document.getElementById("ModifyCountry").value = idCountry;
            document.getElementById("Confirmation").value = confirmation;
            <?php foreach (WorldQueries::GetCountryColumns() as $column) : ?>
                document.getElementById("<?php print $column; ?>").value = document.getElementById("<?php print "Modify_" . $column; ?>").value;
            <?php endforeach; ?>
            SelectEditForm("Modify");
        }
        
    </script>

</body>

</html>