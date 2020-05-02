<?php if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {die('Access denied ...');} ?>
<nav class="<?php echo $CLASS_ORDERBY_NAVBAR; ?>">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-subbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#"><i class="glyphicon glyphicon-folder-close"></i> Categories:</a>
        </div>
        <div id="navbar-subbar" class="collapse navbar-collapse">
            <form class="navbar-form pull-right" role="help" action="?page=help#AddDestination" enctype="multipart/form-data" method="POST">
                <button id="Submit" name="help" type="submit" class="btn btn-success">
                    <i class="glyphicon glyphicon-plus"></i> Add Destination
                </button>
            </form>
        </div>
    </div>
</nav>