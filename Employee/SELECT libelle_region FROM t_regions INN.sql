SELECT libelle_region FROM t_regions INNER JOIN t_continents ON t_continents.id_continent = t_regions.continent_id WHERE libelle_continent LIKE 'Europe';

SELECT libelle_pays FROM t_pays INNER JOIN t_continents ON t_continents.id_continent = t_pays.continent_id WHERE libelle_continent LIKE 'Europe';

SELECT libelle_pays FROM t_pays INNER JOIN t_regions ON t_pays.region_id = t_regions.id_region WHERE libelle_region LIKE 'Afrique Centrale';

SELECT COUNT(*) AS NbOcéanie FROM t_pays INNER JOIN t_continents ON t_pays.continent_id = t_continents.id_continent WHERE libelle_continent LIKE 'Océanie';

SELECT COUNT(*) AS NbAsie FROM t_regions INNER JOIN t_continents ON t_regions.continent_id = t_continents.id_continent WHERE libelle_continent LIKE 'Asie';

SELECT COUNT(*) AS NbAmériqueSeptentrionale FROM t_regions INNER JOIN t_continents ON t_regions.continent_id = t_continents.id_continent WHERE libelle_continent LIKE 'Amérique Septentrionale';



SELECT libelle_continent As Continent, Sum(population_pays) AS Population FROM t_pays INNER JOIN t_continents ON t_continents.id_continent = t_pays.continent_id GROUP BY Continent ORDER BY Population DESC;

SELECT libelle_region As Region, Sum(population_pays) AS Population FROM t_pays INNER JOIN t_regions ON t_pays.region_id = t_regions.id_region GROUP BY Region ORDER BY Population DESC;

SELECT libelle_continent As Continent, Sum(population_pays) AS Population FROM t_pays INNER JOIN t_continents ON t_continents.id_continent = t_pays.continent_id GROUP BY Continent ORDER BY Population DESC Limit 1;
SELECT libelle_continent As Continent FROM t_pays INNER JOIN t_continents ON t_continents.id_continent = t_pays.continent_id GROUP BY Continent ORDER BY Sum(population_pays) DESC Limit 1;

SELECT libelle_region As Region, Sum(population_pays) AS Population FROM t_pays INNER JOIN t_regions ON t_pays.region_id = t_regions.id_region GROUP BY Region ORDER BY Population DESC Limit 1;

SELECT libelle_continent As Continent, Sum(population_pays) AS Population FROM t_pays INNER JOIN t_continents ON t_continents.id_continent = t_pays.continent_id GROUP BY Continent ORDER BY Population Limit 1;

SELECT libelle_region As Region, Sum(population_pays) AS Population FROM t_pays INNER JOIN t_regions ON t_pays.region_id = t_regions.id_region GROUP BY Region ORDER BY Population Limit 1;

SELECT libelle_pays, esperance_vie_pays FROM t_pays WHERE esperance_vie_pays = (SELECT MAX(esperance_vie_pays) FROM t_pays);

SELECT libelle_pays, taux_mortalite_pays FROM t_pays WHERE taux_mortalite_pays = (SELECT MIN(taux_mortalite_pays) FROM t_pays);

SELECT libelle_pays, taux_natalite_pays FROM t_pays WHERE taux_natalite_pays = (SELECT MAX(taux_natalite_pays) FROM t_pays);

SELECT libelle_pays, nombre_enfants_par_femme_pays FROM t_pays WHERE nombre_enfants_par_femme_pays = (SELECT MAX(nombre_enfants_par_femme_pays) FROM t_pays);

SELECT libelle_pays, population_plus_65_pays FROM t_pays WHERE population_plus_65_pays = (SELECT MAX(population_plus_65_pays) FROM t_pays);

SELECT AVG(esperance_vie_pays) FROM t_pays;

SELECT libelle_region, AVG(esperance_vie_pays) AS EVP FROM t_pays INNER JOIN t_regions ON t_pays.region_id = t_regions.id_region GROUP BY libelle_region ORDER BY EVP DESC;

SELECT libelle_continent, AVG(taux_natalite_pays) AS TNP FROM t_continents INNER JOIN t_pays ON t_continents.id_continent = t_pays.continent_id GROUP BY libelle_continent ORDER BY TNP DESC;

SELECT libelle_region, AVG(taux_mortalite_pays) AS TMP FROM t_pays INNER JOIN t_regions ON t_pays.region_id = t_regions.id_region GROUP BY libelle_region ORDER BY TMP DESC;

SELECT libelle_continent, AVG(taux_mortalite_pays) AS TMP FROM t_continents INNER JOIN t_pays ON t_continents.id_continent = t_pays.continent_id GROUP BY libelle_continent ORDER BY TMP DESC;

SELECT libelle_pays FROM t_pays WHERE libelle_pays LIKE 'a%';

SELECT SUM(population_pays) AS Population_Amérique FROM t_pays INNER JOIN t_continents ON t_pays.continent_id = t_continents.id_continent WHERE libelle_continent = 'Amérique Latine et Caraïbes' || libelle_continent = 'Amérique Septentrionale';

SELECT libelle_pays, population_plus_65_pays / population_pays * 100 AS Percentage , population_pays , population_plus_65_pays FROM t_pays;

SELECT SUM(population_pays) AS Population_Corée FROM t_pays WHERE libelle_pays = 'Corée du Sud' || libelle_pays = 'Corée du Nord';

SELECT SUM(population_pays) AS Population_France FROM t_pays WHERE libelle_pays = 'France (métropolitaine)' || libelle_pays = 'Guadeloupe' || libelle_pays = 'Mayotte' || libelle_pays = 'Martinique' || libelle_pays = 'Guyane (française)' || libelle_pays = 'Réunion';

SELECT libelle_pays, taux_natalite_pays FROM t_pays WHERE taux_natalite_pays >= 17 ORDER BY taux_natalite_pays DESC

SELECT libelle_pays, population_pays FROM t_pays WHERE population_pays > 20000 ORDER BY population_pays DESC;

SELECT libelle_pays, taux_croissance_pays FROM t_pays WHERE taux_croissance_pays < 0 ORDER BY taux_croissance_pays ASC;

SELECT SUM(population_pays) AS Population_Chine FROM t_pays WHERE libelle_pays Like 'Chine%';

CREATE VIEW Population_chine As SELECT SUM(population_pays) AS Population_Chine FROM t_pays WHERE libelle_pays Like 'Chine%';
CREATE VIEW Population_mondiale AS SELECT SUM(population_pays) AS Population_Mondiale FROM t_pays;
SELECT ((SELECT * FROM population_chine) / (SELECT * FROM population_mondiale) * 100) AS Percentage_population_chine;

Select Sum(population_pays) AS Population_Europe From t_pays INNER join t_regions ON t_pays.region_id = t_regions.id_region WHERE libelle_region like 'Europe%';
SELECT (SELECT * FROM Population_Europe) + (SELECT population_pays FROM t_pays WHERE libelle_pays = 'Turquie') AS Population_Europe_Plus_Turquie;

