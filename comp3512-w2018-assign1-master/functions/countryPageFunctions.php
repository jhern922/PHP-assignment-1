<?php
include 'functions/helperFunctions.php';

function printCountriesWithImages(){
    $sql = "SELECT CountryName, ISO 
            FROM Countries
            INNER JOIN ImageDetails
            ON ImageDetails.CountryCodeISO = Countries.ISO
            GROUP BY 1
            ORDER BY 1 ASC";
    $results = runQuery($sql);
    printResultsInTable('single-country.php?id=',$results,'ISO','CountryName');
    closeDB();
}



//this will return the country's information based on the ISO passed via Query String
function getCountryInfo($ID){
    $sql = "SELECT CountryName, Capital, Area, Population, CurrencyName, CountryDescription
            FROM Countries
            INNER JOIN ImageDetails
            ON ImageDetails.CountryCodeISO = Countries.ISO
            WHERE Countries.ISO=:id";
    $statement = $GLOBALS['pdo']->prepare($sql);
    $statement->bindValue(':id',$ID);
    $statement->execute();
    $row = $statement->fetch(); //should only pull up one country from Countries table
    return $row;
}?>