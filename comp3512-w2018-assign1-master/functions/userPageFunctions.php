<?php
include 'functions/helperFunctions.php';

//this will print the country page based on the id passed via Query String
function printUserInfo($ID){
    $sql = "SELECT UserID, CONCAT(FirstName,' ',LastName) AS FullName, Address, City, Postal, Country, Phone, Email
            FROM Users
            WHERE UserID=:id";
    $statement = $GLOBALS['pdo']->prepare($sql);
    $statement->bindValue(':id',$ID);
    $statement->execute();
    $row = $statement->fetch(); //should only pull up one row from Users table
    return $row;
}


function printUsers(){
    $sql = "SELECT CONCAT(FirstName,' ',LastName) AS fullName, LastName, UserID
            FROM Users 
            ORDER BY 2";
    $results = runQuery($sql);
    printResultsInTable("single-user.php?id=",$results,'UserID','fullName');
    closeDB();
}







?>