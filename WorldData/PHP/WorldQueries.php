<?php
class WorldQueries
{
    public static $ColumnGeneralSQL = ['population_pays', 'taux_natalite_pays', 'taux_mortalite_pays', 'esperance_vie_pays', 'taux_mortalite_infantile_pays', 'nombre_enfants_par_femme_pays', 'taux_croissance_pays', 'population_plus_65_pays'];
    public static $ColumnsReturn = ['libelle', 'population_totale', 'taux_natalite', 'taux_mortalite', 'esperance_vie', 'taux_mortalite_infantile', 'nombre_enfants_par_femme', 'taux_croissance', 'population_plus_65_pays'];
    public static function GetCountryColumns(){
        $countryData = WorldQueries::$ColumnGeneralSQL;
        array_unshift($countryData, 'continent_id', 'region_id', 'libelle_pays');
        return $countryData;
    }
    public static function GetContinents(){
        $query = "SELECT * FROM t_continents";
        return $query;
    }

    public static function GetRegions($continent){
        $query = "SELECT libelle_region FROM t_regions INNER JOIN t_continents ON t_regions.continent_id = t_continents.id_continent WHERE libelle_continent = '" . addslashes($continent) . "'";
        return $query;
    }

    public static function GetContinentsData($totalName = "Monde")
    {
        $query = WorldQueries::GetPrequeryAVG('libelle_continent');
        $query .= " FROM t_pays JOIN t_continents ON t_pays.continent_id = t_continents.id_continent GROUP BY libelle) ";
        $query .= " UNION ";
        $query .= WorldQueries::GetPrequeryAVG("'_" . $totalName . "'");
        $query .= " FROM t_pays)";
        $query .= " ORDER BY libelle";
        return $query;
    }

    public static function GetRegionsData($continent, $totalName = "Total")
    {
        $query = WorldQueries::GetPrequeryAVG('libelle_region');
        $query .= " FROM t_regions INNER JOIN t_pays ON t_pays.region_id = t_regions.id_region INNER JOIN t_continents On t_pays.continent_id = t_continents.id_continent WHERE libelle_continent = '" . addslashes($continent) . "' GROUP BY libelle_region ORDER BY libelle";
        $query .= ") UNION ";
        $query .= WorldQueries::GetPrequeryAVG("'_" . $totalName . "'");
        $query .= " FROM t_regions INNER JOIN t_pays ON t_pays.region_id = t_regions.id_region INNER JOIN t_continents On t_pays.continent_id = t_continents.id_continent WHERE libelle_continent = '" . addslashes($continent) . "')";
        $query .= " ORDER BY libelle";
        return $query;
    }

    public static function GetCountryOfRegionData($region, $totalName = "Total")
    {
        $query = WorldQueries::GetPrequery("libelle_pays");
        $query .= " FROM t_pays INNER JOIN t_regions ON t_pays.region_id = t_regions.id_region WHERE libelle_region = '" . addslashes($region) . "'";
        $query .= ") UNION ";
        $query .= WorldQueries::GetPrequeryAVG("'_" . $totalName . "'");
        $query .= ", id_pays AS ID";
        $query .= " FROM t_pays INNER JOIN t_regions ON t_pays.region_id = t_regions.id_region WHERE libelle_region = '" . addslashes($region) . "')";
        $query .= " ORDER BY libelle";
        return $query;
    }

    public static function GetCountryOfContinentData($continent, $totalName = "Total")
    {
        $query = WorldQueries::GetPrequery("libelle_pays");
        $query .= " FROM t_pays INNER JOIN t_continents ON t_pays.continent_id = t_continents.id_continent WHERE libelle_continent = '" . addslashes($continent) . "'";
        $query .= ") UNION ";
        $query .= WorldQueries::GetPrequeryAVG("'_" . $totalName . "'");
        $query .= ", id_pays AS ID";
        $query .= " FROM t_pays INNER JOIN t_continents ON t_pays.continent_id = t_continents.id_continent WHERE libelle_continent = '" . addslashes($continent) . "')";
        $query .= " ORDER BY libelle";
        return $query;
    }

    public static function GetPrequery($libelle)
    {
        $prequery = "(SELECT ";
        $size = count(WorldQueries::$ColumnsReturn);
        for ($i = 0; $i < $size; $i++) {
            $column = WorldQueries::$ColumnsReturn[$i];
            switch ($column) {
                case "libelle":
                    $prequery .= "$libelle AS ";
                    $prequery .= WorldQueries::$ColumnsReturn[$i];
                    $prequery .= ", ";
                    break;
                case "population_plus_65_pays":
                    $prequery .= '(population_plus_65_pays / population_pays * 100) AS population_plus_65_pays, ';
                    break;
                case "population_totale":
                    $prequery .= WorldQueries::$ColumnGeneralSQL[$i - 1] . " AS ";
                    $prequery .= WorldQueries::$ColumnsReturn[$i];
                    $prequery .= ", ";
                    break;
                default:
                    $prequery .= WorldQueries::$ColumnGeneralSQL[$i - 1] . " AS ";
                    $prequery .= WorldQueries::$ColumnsReturn[$i];
                    $prequery .= ", ";
                    break;
            }
        }

        $prequery .= "id_pays AS ID";
        return $prequery;
    }

    public static function GetPrequeryAVG($libelle)
    {
        $prequery = "(SELECT ";
        $size = count(WorldQueries::$ColumnsReturn);
        for ($i = 0; $i < $size; $i++) {
            $column = WorldQueries::$ColumnsReturn[$i];
            switch ($column) {
                case "libelle":
                    $prequery .= "$libelle AS ";
                    $prequery .= WorldQueries::$ColumnsReturn[$i];
                    $prequery .= ", ";
                    break;
                case "population_plus_65_pays":
                    $prequery .= 'AVG(population_plus_65_pays / population_pays * 100) AS population_plus_65_pays ';
                    break;
                case "population_totale":
                    $prequery .= "SUM(" . WorldQueries::$ColumnGeneralSQL[$i - 1] . ") AS ";
                    $prequery .= WorldQueries::$ColumnsReturn[$i];
                    $prequery .= ", ";
                    break;
                default:
                    $prequery .= "AVG(" . WorldQueries::$ColumnGeneralSQL[$i - 1] . ") AS ";
                    $prequery .= WorldQueries::$ColumnsReturn[$i];
                    $prequery .= ", ";
                    break;
            }
        }
        return $prequery;
    }

    public static function AddCountry($countryData){
        $query = "INSERT INTO t_pays (";
        foreach (WorldQueries::GetCountryColumns() as $columnNameCountry) {
            $query .= $columnNameCountry;
            $query .= ", ";
        }

        $query = substr($query, 0, -2); // retirer la derniere virgule
        $query .= ") VALUES (";
        foreach (WorldQueries::GetCountryColumns() as $columnNameCountry) {
            $query .= "'" . $countryData[$columnNameCountry] . "'";
            $query .= ", ";
        }

        $query = substr($query, 0, -2); // retirer la derniere virgule
        $query .= ")";
        return $query;
    }

    public static function DeleteCountry($countryId){
        $query = "DELETE FROM t_pays WHERE id_pays = " . $countryId;
        return $query;
    }

    public static function ModifyCountry($countryID, $countryData){
        $query = "UPDATE t_pays SET ";
        foreach (WorldQueries::GetCountryColumns() as $columnNameCountry) {
            if ($columnNameCountry == "libelle_pays") {
                $query .= $columnNameCountry;
                $query .= " = '";
                $query .= addslashes($countryData[$columnNameCountry]);
                $query .= "', ";
                continue;
            }

            if ($columnNameCountry == 'region_id' && $countryData[$columnNameCountry] == "") {
                continue;
            }

            $query .= $columnNameCountry;
            $query .= " = ";
            $query .= $countryData[$columnNameCountry];
            $query .= ", ";
        }

        $query = substr($query, 0, -2); // retirer la derniere virgule
        $query .= " WHERE id_pays = " . $countryID;
        return $query;
    }



    public static function GetContinentId($continent) {
        return 'SELECT id_continent FROM t_continents WHERE libelle_continent = \'' .  addslashes($continent) . '\';';
    }

    public static function GetRegionId($region) {
        return 'SELECT id_region FROM t_regions WHERE libelle_region = \'' . addslashes($region)  . '\';';
    }

}
