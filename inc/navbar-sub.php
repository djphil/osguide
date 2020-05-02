<?php if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {die('Access denied ...');} ?>
<?php $categorie = isset($_GET['categorie']) ? htmlspecialchars($_GET['categorie']) : "All Categories"; ?>
<nav class="<?php echo $CLASS_ORDERBY_NAVBAR; ?>">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-orderby" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#"><i class="glyphicon glyphicon-sort"></i> Order by:</a>
        </div>
        <div id="navbar-orderby" class="collapse navbar-collapse">
            <ul class="<?php echo $CLASS_ORDERBY_NAV; ?>">
                <li><a href="./?page=destinations-wall&categorie=<?php echo $categorie; ?>&orderby=id" class="active">Id</a></li>
                <li><a href="./?page=destinations-wall&categorie=<?php echo $categorie; ?>&orderby=region">Region</a></li>
                <li><a href="./?page=destinations-wall&categorie=<?php echo $categorie; ?>&orderby=owner">Owner</a></li>
                <!--<li><a href="./">Categories</a></li>-->
                <li><a href="./?page=destinations-wall&categorie=<?php echo $categorie; ?>&orderby=agents">Agents</a></li>
                <li><a href="./?page=destinations-wall&categorie=<?php echo $categorie; ?>&orderby=date">Date</a></li>
                <li><a href="./?page=destinations-wall&categorie=<?php echo $categorie; ?>&orderby=random">Random</a></li>
            </ul>
            <form class="navbar-form pull-right" role="help" action="?page=help#AddDestination" enctype="multipart/form-data" method="POST">
                <button id="Submit" name="help" type="submit" class="btn btn-success">
                    <i class="glyphicon glyphicon-plus"></i> Add Destination
                </button>
            </form>
        </div>
    </div>
</nav>
