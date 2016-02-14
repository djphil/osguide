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
                <form class="navbar-form navbar-right boxer-right" role="help" action="?help#AddRegion" enctype="multipart/form-data" method="POST">
                    <button id="Submit" name="help" type="submit" class="btn btn-default btn-success">
                        <i class="glyphicon glyphicon-plus"></i> Add Region
                    </button>
                </form>
            </div>
        </div>
    </nav>
