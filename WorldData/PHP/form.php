<!DOCTYPE html>
<html>

<head>
    <title>Formulaire</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="style_form.css">
</head>
<header>
    <div class=ComeBackButton>
        <a href="Index.php">Back</a>
    </div>
</header>

<body>
    <div class="MainDiv">
        <?php

        enum DisplayMode
        {
            case Continent;
            case Region;
            case Country;
        }

        $servername = 'localhost';
        $username = 'root';
        $password = '';

        try {
            $conn = new PDO("mysql:host=$servername;dbname=pays", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }

        $columnNameCountryArr = ['libelle_continent', 'libelle_region', 'libelle_pays', 'population_pays', 'taux_natalite_pays', 'taux_mortalite_pays', 'esperance_vie_pays', 'taux_mortalite_infantile_pays', 'nombre_enfants_par_femme_pays', 'taux_croissance_pays', 'Part_65_plus'];
        $columnCountrySQLArr = ['continent_id', 'region_id', 'libelle_pays', 'population_pays', 'taux_natalite_pays', 'taux_mortalite_pays', 'esperance_vie_pays', 'taux_mortalite_infantile_pays', 'nombre_enfants_par_femme_pays', 'taux_croissance_pays', 'population_plus_65_pays'];
        $columnCountrySQLArr2 = ['libelle_pays', 'population_pays', 'taux_natalite_pays', 'taux_mortalite_pays', 'esperance_vie_pays', 'taux_mortalite_infantile_pays', 'nombre_enfants_par_femme_pays', 'taux_croissance_pays', 'population_plus_65_pays'];
        //print_r($_POST);
        //print("\n CountryReady " . (IsCountryReady() ? "True" : "False"));
        $countries = [];
        if (IsCountryAdded()) {
            $query = "INSERT INTO t_pays (";
            foreach ($columnCountrySQLArr as $columnNameCountry) {
                if ($columnNameCountry == "region_id" && !IsPostVariable('libelle_region')) {
                    continue;
                }
                $query .= $columnNameCountry;
                $query .= ", ";
            }

            $query = substr($query, 0, -2); // retirer la derniere virgule
            $query .= ") VALUES (";
            foreach ($columnCountrySQLArr as $columnNameCountry) {
                if ($columnNameCountry == "region_id" && !IsPostVariable('libelle_region')) {
                    continue;
                }

                if ($columnNameCountry == "continent_id") {
                    $request = 'SELECT id_continent FROM t_continents WHERE libelle_continent = \'' . $_POST['libelle_continent'] . '\';';
                    //print $request;
                    $sqlContinentData = $conn->query($request);
                    $query .= "'" . $sqlContinentData->fetch()[0] . "'";
                    $query .= ", ";
                    continue;
                }

                if ($columnNameCountry == "region_id") {
                    $request = 'SELECT id_region FROM t_regions WHERE libelle_region = \'' . $_POST['libelle_region'] . '\';';
                    //print $request;
                    $sqlRegionData = $conn->query($request);
                    $query .= "'" . $sqlRegionData->fetch()[0] . "'";
                    $query .= ", ";
                    continue;
                }

                if ($columnNameCountry == "population_plus_65_pays") {
                    $query .= "'" . ($_POST['population_pays'] / 100 * $_POST['Part_65_plus']) . "'";
                    $query .= ", ";
                    continue;
                }

                $query .= "'" . $_POST[$columnNameCountry] . "'";
                $query .= ", ";
            }
            $query = substr($query, 0, -2); // retirer la derniere virgule
            $query .= ")";
            $conn->query($query);
            $link = "Location: index.php";
            $link .= "?continent=" . str_replace(" ", "+", $_POST['libelle_continent']);
            if (IsPostVariable('libelle_region')) {
                $link .= "&region=" . str_replace(" ", "+", $_POST['libelle_region']);
            }
            //print('' . $link . '');
            header($link);
            exit();
        } else {
            if (IsPostVariable("TryValidate")) {
                $_POST['TryValidate'] = false;
            }
        }

        GetCountries();

        function GetCountries()
        {
            if(!IsPostVariable('libelle_region')){
                return;
            }

            $request = 'SELECT id_region FROM t_regions WHERE libelle_region = \'' . $_POST['libelle_region'] . '\';';
            $region = $GLOBALS['conn']->query($request);
            $sqlRequestCountry = "SELECT t_pays.libelle_pays AS libelle_pays,";
            foreach ($GLOBALS['columnCountrySQLArr2'] as $columnName) {
                if ($columnName === 'libelle_pays') {
                    continue;
                }
                $sqlRequestCountry .= " $columnName,";
            }
            $sqlRequestCountry = substr($sqlRequestCountry, 0, -1); // retirer la derniere virgule

            $sqlRequestCountry.= " FROM t_pays INNER JOIN part_65_plus_pays ON t_pays.libelle_pays = part_65_plus_pays.libelle_pays WHERE region_id = " . $region->fetch()[0];
            //print $sqlRequestCountry; 
            $sqlRequestGetCountryFromRegionData = $GLOBALS['conn']->query($sqlRequestCountry);
            foreach ($sqlRequestGetCountryFromRegionData as $row) {
                $rowData = array();
                foreach ($GLOBALS['columnCountrySQLArr2'] as $columnName) {
                    $rowData[] = $row[$columnName];
                }

                $GLOBALS['countries'][] = $rowData;
            }
        }

        $columnNameHtmlArr = array('Continent', 'Region', 'Pays', 'Population totale <br><span style=font-family:OpenSansLight>(en milliers)</span>', 'Taux de natalité', 'Taux de mortalité', 'Espérance de vie', 'Taux de mortalité infantile', 'Nombre d’enfant(s) par femme', 'Taux de croissance', 'Part des 65 ans et plus <br><span style=font-family:OpenSansLight>(%)</span>');
        $MaxMin = array(array(0, 30000000), array(0, 1000), array(0, 1000), array(0, 200), array(0, 1000), array(0, 100), array(-1000, 1000), array(0, 100));
        $sqlRequestGetContinentData = $conn->query('SELECT * FROM t_continents;');
        $ContinentArr = array();
        foreach ($sqlRequestGetContinentData as $row) {
            $ContinentArr[] = ToUpperSpecialChar($row['libelle_continent']);
        }

        $sqlRequestGetRegionInContinentData;
        $regionArr = array();
        $currentContinent = null;
        $currentRegion = null;
        if (IsPostVariable('libelle_continent')) {
            $currentContinent = $_POST['libelle_continent'];
            if ($currentContinent != null) {
                $sqlRequestGetRegionInContinentData = $conn->query("SELECT libelle_region FROM t_regions INNER JOIN t_continents ON t_regions.continent_id = t_continents.id_continent WHERE libelle_continent = '" . addslashes($currentContinent) . "';");
                foreach ($sqlRequestGetRegionInContinentData as $row) {
                    $regionArr[] = $row['libelle_region'];
                }
            }
        }

        if (IsPostVariable('libelle_region')) {
            $currentRegion = $_POST['libelle_region'];
        }

        function IsPostVariable($variable)
        {
            return isset($_POST[$variable]) && $_POST[$variable] != null;
        }

        function IsCountryReady()
        {
            foreach ($GLOBALS['columnNameCountryArr'] as $columnNameCountry) {
                if ($columnNameCountry == 'libelle_region') {
                    continue;
                }

                if (!IsPostVariable($columnNameCountry)) {
                    return false;
                }
            }

            return true;
        }

        function IsCountryAdded()
        {
            return IsCountryReady() && IsPostVariable('TryValidate');
        }


        function ToUpperSpecialChar($string)
        {
            return str_replace(array("é", "ï"), array("É", "Ï"), strtoupper($string));
        }

        function ToLowerSpecialChar($string)
        {
            return str_replace(array("É", "Ï"), array("é", "ï"), strtolower($string));
        }
        ?>

        <div class="FormTable" id="FormAddTable">
            <form id="NewCountryForm" method="post">
                <table>
                    <tr>
                        <?php for ($i = 0; $i < count($columnNameHtmlArr); $i++) : ?>
                            <?php if (count($regionArr) == 0 && $i == 1) continue; ?>
                            <th>
                                <label for="fname" style="width : <?php print(($i == 0) ? 240 : (($i == 1) ? 200 : 150)) ?>px"><b><?php print $columnNameHtmlArr[$i] ?></b></label>
                            </th>
                        <?php endfor ?>
                    </tr>
                    <tr>
                        <td style="width : 240px">
                            <span>
                                <select id="continentField" name="<?php print $columnNameCountryArr[0] ?>" onchange="FormAddCountry()">
                                    <?php foreach ($ContinentArr as $row) : ?>
                                        <option value="<?php print $row ?>" <?php if ($row == $currentContinent) {
                                                                                print "selected";
                                                                            } ?>><?php print $row ?></option>
                                    <?php endforeach ?>
                                    <?php if (!IsPostVariable("libelle_continent")) : ?>
                                        <option value="<?php print "--" ?>" selected><?php print "--" ?></option>
                                    <?php endif ?>
                                </select>
                            </span>
                        </td>
                        <?php if (count($regionArr) != 0) : ?>
                            <td style="width : 200px">
                                <span>
                                    <select id="region" name="<?php print $columnNameCountryArr[1] ?>">
                                        <?php foreach ($regionArr as $row) : ?>
                                            <option value="<?php print $row ?>" <?php if ($currentRegion == $row) {
                                                                                    print "selected";
                                                                                } ?>><?php print $row ?></option>
                                        <?php endforeach ?>
                                        <option value="<?php print "--" ?>" <?php if ($currentRegion == null) {
                                                                                print "selected";
                                                                            } ?>><?php print "--" ?></option>
                                    </select>
                                </span>
                            </td>
                        <?php endif ?>
                        <td>
                            <span>
                                <input type="text" id="<?php print $columnNameCountryArr[2] ?>" name="<?php print $columnNameCountryArr[2] ?>" <?php if (IsPostVariable('libelle_pays')) {
                                                                                                                                                    print "value=\"" . $_POST['libelle_pays'] . "\"";
                                                                                                                                                };  ?>>
                            </span>
                        </td>
                        <?php for ($i = 3; $i < count($columnNameHtmlArr); $i++) : ?>
                            <td>
                                <span>
                                    <input type="number" id="<?php print $columnNameCountryArr[$i] ?>" name="<?php print $columnNameCountryArr[$i] ?>" min="<?php print $MaxMin[$i - 3][0] ?>" max="<?php print $MaxMin[$i - 3][1] ?>" <?php if (IsPostVariable($columnNameCountryArr[$i])) {
                                                                                                                                                                                                                                            print "value=\"" . $_POST[$columnNameCountryArr[$i]] . "\"";
                                                                                                                                                                                                                                        };  ?>>
                                </span>
                            </td>
                        <?php endfor ?>
                    </tr>
                </table>
        </div>
        

        <button id="SubmitButton" onclick="FormAddCountry(true)">Ajouter un pays</button>
        <input type='hidden' id="TryAddCountry" name="TryAddCountry" value="">

        <div class="FormTable" id="FormAddTable">
            <form id="NewCountryForm" method="post">
                <table>
                    <tr>
                        <?php for ($i = 0; $i < count($columnNameHtmlArr); $i++) : ?>
                            <?php if (count($regionArr) == 0 && $i == 1) continue; ?>
                            <th>
                                <label for="fname" style="width : <?php print(($i == 0) ? 240 : (($i == 1) ? 200 : 150)) ?>px"><b><?php print $columnNameHtmlArr[$i] ?></b></label>
                            </th>
                        <?php endfor ?>
                    </tr>
                    <tr>
                        <td style="width : 240px">
                            <span>
                                <select id="continentField" name="<?php print $columnNameCountryArr[0] ?>" onchange="FormAddCountry()">
                                    <?php foreach ($ContinentArr as $row) : ?>
                                        <option value="<?php print $row ?>" <?php if ($row == $currentContinent) {
                                                                                print "selected";
                                                                            } ?>><?php print $row ?></option>
                                    <?php endforeach ?>
                                    <?php if (!IsPostVariable("libelle_continent")) : ?>
                                        <option value="<?php print "--" ?>" selected><?php print "--" ?></option>
                                    <?php endif ?>
                                </select>
                            </span>
                        </td>
                        <?php if (count($regionArr) != 0) : ?>
                            <td style="width : 200px">
                                <span>
                                    <select id="region" name="<?php print $columnNameCountryArr[1] ?>">
                                        <?php foreach ($regionArr as $row) : ?>
                                            <option value="<?php print $row ?>" <?php if ($currentRegion == $row) {
                                                                                    print "selected";
                                                                                } ?>><?php print $row ?></option>
                                        <?php endforeach ?>
                                        <option value="<?php print "--" ?>" <?php if ($currentRegion == null) {
                                                                                print "selected";
                                                                            } ?>><?php print "--" ?></option>
                                    </select>
                                </span>
                            </td>
                        <?php endif ?>
                        <td>
                            <span>
                                <input type="text" id="<?php print $columnNameCountryArr[2] ?>" name="<?php print $columnNameCountryArr[2] ?>" <?php if (IsPostVariable('libelle_pays')) {
                                                                                                                                                    print "value=\"" . $_POST['libelle_pays'] . "\"";
                                                                                                                                                };  ?>>
                            </span>
                        </td>
                        <?php for ($i = 3; $i < count($columnNameHtmlArr); $i++) : ?>
                            <td>
                                <span>
                                    <input type="number" id="<?php print $columnNameCountryArr[$i] ?>" name="<?php print $columnNameCountryArr[$i] ?>" min="<?php print $MaxMin[$i - 3][0] ?>" max="<?php print $MaxMin[$i - 3][1] ?>" <?php if (IsPostVariable($columnNameCountryArr[$i])) {
                                                                                                                                                                                                                                            print "value=\"" . $_POST[$columnNameCountryArr[$i]] . "\"";
                                                                                                                                                                                                                                        };  ?>>
                                </span>
                            </td>
                        <?php endfor ?>
                    </tr>
                </table>
        </div>
        
        <button id="SubmitButton" onclick="FormModifyCountry()">Modifier le pays</button>
        <input type='hidden' id="TryModifyCountry" name="TryModifyCountry" value="">

        <button id="SubmitButton" onclick="FormRemoveCountry()">Supprimer le pays</button>
        <input type='hidden' id="TryDeleteCountry" name="TryDeleteCountry" value="">

        </form>
        <footer></footer>
    </div>

    <script>
        function FormAddCountry($validate = null) {
            document.getElementById("TryAddCountry").value = $validate;
            document.getElementById("NewCountryForm").submit();
        }

        function FormModifyCountry($validate = null) {
            document.getElementById("TryModifyCountry").value = $validate;
            document.getElementById("ModifyCountryForm").submit();
        }

        function FormRemoveCountry($validate = null) {
            document.getElementById("TryDeleteCountry").value = $validate;
            document.getElementById("RemoveCountryForm").submit();
        }
    </script>
</body>

</html>