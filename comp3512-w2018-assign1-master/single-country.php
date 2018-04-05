<?php
    include 'functions/countryPageFunctions.php';
     
    if(!isset($_GET['id']) || empty($_GET['id']))
    {
      header('Location:error.php');
    }
    else if((isset($_GET['id']) && !empty($_GET['id'])) && !IDExists($_GET['id'],'Countries','ISO'))
    {
       header('Location:error.php?error=invalidID'); 
    }
    else
    {
       $row = getCountryInfo($_GET['id']);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Information about <?php echo $row['CountryName']; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href='http://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/captions.css" />
    <link rel="stylesheet" href="css/bootstrap-theme.css" />    
    <link rel="stylesheet" href="css/myown.css"/>
</head>

<body>
    <?php include 'includes/header.inc.php';?>
    <!-- Page Content -->
    <main class="container-fluid">
        <div class='col-md-8 col-md-offset-2'>
            <div class=" jumbotron">
                <h2> <?php echo $row['CountryName']; ?></h2>
                <p>Capital: <b> <?php echo $row['Capital']; ?></b></p>
                <p>Area: <b> <?php echo number_format($row['Area']); ?> </b> sq km.</p>
                <p>Population: <b><?php echo number_format($row['Population']); ?></b></p>
                <p>Currency Name: <b><?php echo $row['CurrencyName'] ?></b></p>
                <p><?php echo $row['CountryDescription']; ?></p>
            </div>
            <div class="panel panel-info">
                <div class='panel-heading'><h3>Images from <?php echo $row['CountryName']; ?></h3></div>
                    <?php printRelatedImages($_GET['id'],"CountryCodeISO"); 
                          closeDB();?>
            </div>
        </div>
    </main>
    
    <footer>
        <div class="container-fluid">
                    <div class="row final">
                <p>Copyright &copy; 2017 Creative Commons ShareAlike</p>
                <p><a href="#">Home</a> / <a href="#">About</a> / <a href="#">Contact</a> / <a href="#">Browse</a></p>
            </div>            
        </div>

    </footer>
        <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
</body>

</html>