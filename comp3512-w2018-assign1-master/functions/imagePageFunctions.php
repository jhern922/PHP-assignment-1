<?php
include 'functions/helperFunctions.php';


function getImageInfo($ID){         
$sql = "SELECT *, ImageDetails.Title As ImageTitle, ImageDetails.Description AS descrip 
        FROM ImageDetails
        INNER JOIN Countries ON ImageDetails.CountryCodeISO = Countries.ISO
        INNER JOIN Cities ON ImageDetails.CityCode = Cities.CityCode
        INNER JOIN Users ON ImageDetails.UserID = Users.UserID
        WHERE ImageDetails.ImageID =:id";
    $statement = $GLOBALS['pdo']->prepare($sql);
    $statement->bindValue(':id',$ID);
    $statement->execute();
    $row = $statement->fetch(); //should only pull up one country from Countries table
    return $row;
}
?>