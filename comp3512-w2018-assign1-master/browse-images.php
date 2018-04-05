<?php
include 'functions/helperFunctions.php';
   if(((!isset($_GET['Continent'])) && (!isset($_GET['Country'])) && (!isset($_GET['City'])) && (!isset($_GET['Text']))) || (($_GET['Continent'] == '-1') && ($_GET['Country'] == '-1') && ($_GET['City'] == '-1') && (empty($_GET['Title'])))){
        $filterType = 'All';
        $filterValue = 'All';
        $sql = formSQLQuery($filterType, null);
        $statement = getStatement($sql);
   }
   else if(((isset($_GET['Continent'])) && ($_GET['Continent'] != '-1') && (!empty($_GET['Continent'])) && (!IDExists($_GET['Continent'],'Continents','ContinentCode'))) || ((isset($_GET['Country'])) && ($_GET['Country'] != '-1') &&  (!empty($_GET['Country'])) && (!IDExists($_GET['Country'],'Countries','ISO'))) || ((isset($_GET['City']))  && ($_GET['City'] != '-1') && (!empty($_GET['City'])) && (!IDExists($_GET['City'],'Cities','CityCode'))))
   {
        header('Location:error.php?error=invalidID'); 
   }
   else if(isset($_GET['Continent']) && IDExists($_GET['Continent'],'Continents','ContinentCode') && !empty($_GET['Continent']))
   {
       $filterType = 'Continent';
       $filterValue = $filterType."=".$_GET['Continent'];
       $sql = formSQLQuery($filterType,$_GET['Continent']);
       $statement = getStatement($sql);
   }
   else if(isset($_GET['Country']) && IDExists($_GET['Country'],'Countries','ISO') && !empty($_GET['Country']))
   {
       $filterType = 'Country';
       $filterValue = $filterType."=".$_GET['Country'];
       $sql = formSQLQuery($filterType,$_GET['Country']);
       $statement = getStatement($sql);
   }
   else if(isset($_GET['City']) && IDExists($_GET['City'],'Cities','CityCode') && !empty($_GET['City']))
   {
       $filterType = 'City';
       $filterValue = $filterType."=".$_GET['City'];
       $sql = formSQLQuery($filterType,$_GET['City']);
       $statement = getStatement($sql);
   }
   else if(isset($_GET['Title']) && !empty($_GET['Title']))
   {
       $filterType = 'Text';
       $filterValue = $filterType."=".$_GET['Title'];
       $sql = formSQLQuery($filterType,$_GET['Title']);
       $statement = getStatement($sql);
   }
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Browse Images</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href='http://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/captions.css" />
    <link rel="stylesheet" href="css/bootstrap-theme.css" />    
    <link rel="stylesheet" href="css/myown.css" /> 
</head>

<body>
    <?php include 'includes/header.inc.php'; ?>
    
    <!-- Page Content -->
    <main class="container">
        <div class="panel panel-default">
          <div class="panel-heading">Filters</div>
          <div class="panel-body">
            <form action="browse-images.php" method="GET" class="form-horizontal">
              <div class="form-inline">
              <select name="Continent" class="form-control">
                <option value="-1">Select Continent</option>
                <?php $sql = formSQLQuery('ContinentList', null);
                $result = getStatement($sql);
                while ($row = $result->fetch()) {
                echo "<option value='".$row["ContinentCode"]. "'>".$row['ContinentName']."</option>"; }?>
              </select>     
              
              <select name="Country" class="form-control">
                <option value="-1">Select Country</option>
                <?php $sql = formSQLQuery('CountryList', null);
                $result = getStatement($sql);
                while ($row = $result->fetch()) {
                echo "<option value='".$row['ISO']."'>".$row['CountryName']."</option>"; } ?>
              </select>  
                            
              <select name="City" class="form-control">
                <option value="-1">Select City</option>
                <?php  $sql = formSQLQuery('CityList', null);
                $result = getStatement($sql);
                while ($row = $result->fetch()) {
                echo "<option value='".$row['CityCode']."'>".$row['AsciiName']."</option>";} ?>
              </select> 
              <input type="Text"  placeholder="Search Title" class="form-control" name=Title>
              <button type="submit" class="btn btn-primary">Filter</button>
              <a href="browse-images.php" class="btn btn-success">Clear</a>
              </div>
            </form>

          </div>
        </div>    
            
                <div class="panel panel-default">
                    <div class="panel-heading">Images [<?php echo $filterValue; ?>]</div>
                     <div class="panel-body">
                     	<ul class="caption-style-2">
                     	    <?php while($row = $statement->fetch()){ ?>
                              <li>
                              <a href="single-image.php?id=<?php echo $row['ImageID'];?>" class='img-responsive'>
                              <img src="images/square-medium/<?php echo $row['Path'];?>" alt='<?php echo $row['Title'];?>'>
                                      <div class='caption'>
                                          <div class="blur"></div>
                                          <div class="caption-text">
                                            <p><?php echo $row['Title'];?></p>
                                          </div>
                                      </div>
                              </a>
                              </li> 
                              <?php } closeDB(); ?>
                        </ul>
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