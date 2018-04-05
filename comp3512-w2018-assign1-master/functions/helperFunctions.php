<?php
require_once 'config.php';
$pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//checks if the passed ID exists. This funciton is compatible to any table that has the ID as the primary key(unique)
//$id = the id value that will be used in the sQL where clause
//$table = the name of the table where the id value will be looked for
//$colName = the name of the column where the id value will be looked against
function IDExists($id,$table,$colName){
    $exists = false;
    $sql = "SELECT ".$colName." FROM ".$table." WHERE ".$colName."='".$id."'";
    $result = runQuery($sql);
    $row = $result->fetch(); //this should only pick up one row since ID is uniquely identified( ID value passed in is from a primary key)
    if($row){
        $exists = true;
    }
    return $exists;
}

function displayContinentPanelList(){
        $sql = "SELECT ContinentName, ContinentCode
            FROM Continents
            ORDER BY 1 ASC";
    $statement = $GLOBALS['pdo']->prepare($sql);
    $statement->execute();
    while($row = $statement->fetch()){
       echo "<li class='list-group-item'><a href='browse-images.php?Continent=".$row['ContinentCode']."'>".$row['ContinentName']."</a></li>"; 
    }
}


function displayCountriesPanelList(){
   $sql = "SELECT CountryName, ISO 
            FROM Countries
            INNER JOIN ImageDetails
            ON ImageDetails.CountryCodeISO = Countries.ISO
            GROUP BY 1
            ORDER BY 1 ASC";
    $statement = $GLOBALS['pdo']->prepare($sql);
    $statement->execute();
    while($row = $statement->fetch()){
      echo "<li class='list-group-item'><a href='browse-images.php?Country=".$row['ISO']."'>".$row['CountryName']."</a></li>"; 
    }
}




//print images related to either country or user
function printRelatedImages($ID,$tableCol){
      $sql = "SELECT ImageID, Title, Path
            FROM ImageDetails
            WHERE ".$tableCol."=:id
            ORDER BY 1 ASC";
    $statement = $GLOBALS['pdo']->prepare($sql);
    $statement->bindValue(':id',$ID);
    $statement->execute();
    while($row = $statement->fetch()){
         printSmallImage($row['Path'],$row['Title'],$row['ImageID']);
    }
     closeDB();
}


//there would be no default value in this switch statement because the headers would take care of the error handling first before the program reaches this area of the code
function formSQLQuery($filterType,$id){
    switch($filterType){
        case 'All':             $sql = "SELECT ImageID, Title, Path
                                FROM ImageDetails
                                ORDER BY 1 ASC";
                                break;
        case 'Continent':       $sql = "SELECT ImageID, Title, Path
                                FROM ImageDetails
                                INNER JOIN Continents
                                ON Continents.ContinentCode = ImageDetails.ContinentCode
                                WHERE ImageDetails.ContinentCode='$id'
                                ORDER BY 1 ASC";
                                break;
        case 'Country':         $sql = "SELECT ImageID, Title, Path
                                FROM ImageDetails
                                INNER JOIN Countries
                                ON Countries.ISO = ImageDetails.CountryCodeISO
                                WHERE ImageDetails.CountryCodeISO='$id'
                                ORDER BY 1 ASC";
                                break;
        case 'City':            $sql = "SELECT ImageID, Title, Path
                                FROM ImageDetails
                                INNER JOIN Cities
                                ON Cities.CityCode = ImageDetails.CityCode
                                WHERE ImageDetails.CityCode='$id'
                                ORDER BY 1 ASC";
                                break;
        case 'Text':            $sql = "SELECT ImageID, Title, Path
                                FROM ImageDetails
                                WHERE ImageDetails.Title LIKE '%$id%'
                                ORDER BY 1 ASC";
                                break;
        case 'ContinentList':   $sql = "SELECT * 
                                FROM Continents";
                                break;
        case 'CountryList':     $sql = "SELECT *
                                FROM Countries
                                INNER JOIN ImageDetails
                                ON Countries.ISO =ImageDetails.CountryCodeISO
                                GROUP BY Countries.CountryName
                                ORDER BY Countries.CountryName";
                                break;
        case 'CityList':        $sql = "SELECT *
                                FROM Cities
                                INNER JOIN ImageDetails
                                ON Cities.CityCode =ImageDetails.CityCode
                                GROUP BY Cities.AsciiName
                                ORDER BY Cities.AsciiName";
                                break;
    }
    return $sql;
}


//returns the statement based on the query that is passed in. I understand that this could have been in the formSQLQuery() function above but this function makes code more readable in browse-images.php
function getStatement($sql){
    $statement = $GLOBALS['pdo']->prepare($sql);
    $statement->execute();
    return $statement; 
}

function printSmallImage($path,$title,$id)
{
   echo "<a href='single-image.php?id=$id'><img src='images/square-small/$path' title='$title' alt='$title' class='img-thumbnail'></a>";
}
//This method will work for both browse-countries and browse-user link table
//Parameters: $Results - the result of the query execution
//            $idName - the id value that will be passed through the URL
//            $label - the name of the link
function printResultsInTable($page, $results,$idName,$label)
{
    $counter = 0; //ensures that there are only 4 links per row
    while($row = $results->fetch())
    {
        if($counter == 0){
            echo "<tr>";
        }
        if($counter == 4){
            echo "</tr>";
            $counter = 0;
        }
        echo "<td class='removeBorder'><a href=".$page.$row[$idName].">".$row[$label]."</a></td>"; 
        $counter++;
    }
}



//runs whatever SQL query is sent and returns the resulting rows of the executed query
function runQuery($sql){
    $results = $GLOBALS['pdo']->query($sql);
    return $results;
}


//closes the PDO connection
function closeDB(){
    $GLOBALS['pdo'] = null;
}




?>