<?php if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {die('Access denied ...');} ?>
<nav class="<?php echo $CLASS_NAVBAR; ?>">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <a class="navbar-brand" href="./">
                <i class="glyphicon glyphicon-th-large"></i> <strong>LOGO</strong>
            </a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="<?php echo $CLASS_NAV; ?>">
                <li <?php if (isset($_GET['page']) && $_GET['page'] == "home" || empty($_GET['page'])) {echo 'class="active"';} ?>>
                    <a href="./?page=home"><i class="glyphicon glyphicon-home"></i> Home</a>
                </li>
                <li <?php if (isset($_GET['page']) && $_GET['page'] == "help") {echo 'class="active"';} ?>>
                    <a href="./?page=help"><i class="glyphicon glyphicon-education"></i> Help</a>
                </li>
            </ul>
            <form class="navbar-form navbar-right" role="search" action="./?page=search" enctype="multipart/form-data" method="POST">
            <div class="input-group">
                <input type="text" class="form-control" id="searchwordID" name="searchword" placeholder="Search" >
                <div class="input-group-btn">
                    <button class="btn btn-default" name="search" type="submit">
                        <i class="glyphicon glyphicon-search"></i>
                    </button>
                </div>
            </div>
            </form>
        </div>
    </div>
</nav>
