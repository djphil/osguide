<?php if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {die('Access denied ...');} ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo $title." v".$version; ?>">
    <meta name="author" content="Philippe Lemaire (djphil)">
    <link rel="icon" href="img/favicon.ico">
    <link rel="author" href="inc/humans.txt" />
    <title><?php echo $title." v".$version; ?></title>

    <!-- <link href="css/bootstrap.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link href="css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <?php if ($useTheme === TRUE): ?>
    <link rel="stylesheet" href="css/bootstrap-theme.min.css">
    <?php endif ?>

    <link rel="stylesheet" href="css/gh-fork-ribbon.min.css">
    <link rel="stylesheet" href="css/osguide.css">
    <link rel="stylesheet" href="css/modal.css">
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/slate.css">
    <!--[if lt IE 9]><link rel="stylesheet" href="css/gh-fork-ribbon.ie.min.css" /><![endif]-->
    <!--[if lt IE 9]><script src="js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="js/ie-emulation-modes-warning.js"></script>
    <!--[if lt IE 9]>
      <script src="js/html5shiv.min.js"></script>
      <script src="js/respond.min.js"></script>
    <![endif]-->
</head>

<body>
<?php if ($display_ribbon === TRUE): ?>
    <div class="github-fork-ribbon-wrapper left">
        <div class="github-fork-ribbon">
            <a href="<?php echo $github_url; ?>" target="_blank">Fork me on GitHub</a>
        </div>
    </div>
<?php endif ?>

<div class="spacer"></div>

<div class="container">
    <?php include_once("inc/navbar.php"); ?>

    <!-- Fash Message -->
    <?php if(isset($_SESSION['flash'])): ?>
        <?php foreach($_SESSION['flash'] as $type => $message): ?>
            <div class="alert alert-<?php echo $type; ?> alert-anim">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <?php echo $message; ?>
            </div>
        <?php endforeach; ?>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <div class="content">
        <?php echo $content; ?>
    </div>

    <!--BACK TO TOP-->
    <a href="#" class="btn btn-default btn-sm back-to-top btn-fixed-bottom">
        <span class="glyphicon glyphicon-chevron-up"></span>
    </a>

    <footer class="footer <?php echo $CLASS_NAVBAR; ?>">
        <div class="container-fluid">
            <div class="text-muted">
                <div class="pull-right">
                    <?php echo $title.' v'.$version; ?> by djphil 
                    <a href="<?php echo $lisense_url; ?>" class="label label-default" target="_blank"><?php echo $lisense; ?></a>
                </div>
                &copy; 2015 - <?php $date = date('Y'); echo $date; ?> Digital Concepts - All rights reserved
            </div>
        </div>
    </footer>
</div>

<script src="js/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/jquery.min.js"><\/script>')</script>
<script src="js/bootstrap.min.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<!--<script src="js/ie10-viewport-bug-workaround.js"></script>-->
<script src="js/showup.js"></script>

<!--YES/NO BUTTONS-->
<script>
$(document).ready(function(){
    // Use only for V1
    $('#radioBtn').on('click', function(){
        var sel = $(this).data('value');
        var tog = $(this).data('toggle');
        $('#'+tog).val(sel);
        // You can change these lines to change classes (Ex. btn-default to btn-danger)
        $('span[data-toggle="'+tog+'"]').not('[data-value="'+sel+'"]').removeClass('active btn-primary').addClass('notActive btn-default');
        $('span[data-toggle="'+tog+'"][data-value="'+sel+'"]').removeClass('notActive btn-default').addClass('active btn-primary');
    });

    // Use only for V2
    $('#radioBtnV2 span').on('click', function(){
        var sel = $(this).data('value');
        var tog = $(this).data('toggle');
        var active = $(this).data('active');
        var classes = "btn-default btn-primary btn-success btn-info btn-warning btn-danger btn-link";
        var notactive = $(this).data('notactive');
        $('#'+tog).val(sel);
        $('span[data-toggle="'+tog+'"]').not('[data-value="'+sel+'"]').removeClass('active '+classes).addClass('notActive '+ notactive);
        $('span[data-toggle="'+tog+'"][data-value="'+sel+'"]').removeClass('notActive '+classes).addClass('active '+ active);
    });
});
</script>

<!--BACK TO TAB-->
<script>
$('a').click(function (e) {localStorage.setItem('selectedTab', null);});

$('a[data-toggle="tab"]').click(function (e) {
    e.preventDefault();
    $(this).tab('show');
});

$('a[data-toggle="tab"]').on("shown.bs.tab", function (e) {
    var id = $(e.target).attr("href");
    localStorage.setItem('selectedTab', id);
});

var selectedTab = localStorage.getItem('selectedTab');
if (selectedTab != null) {$('a[data-toggle="tab"][href="' + selectedTab + '"]').tab('show');} 
</script>

<!-- ALERT ANIM -->
<script>
$(document).ready(function() {
    $(".alert-anim").fadeTo(2000, 500).slideUp(500, function() {
        $(".alert-anim").alert('close');
    });
});
</script>

<!-- TOOLTIPS -->
<script>$(document).ready(function(){$('[data-toggle="tooltip"]').tooltip();});</script>
<!-- POPOVER -->
<script>$(document).ready(function(){$('[data-toggle="popover"]').popover();});</script>
<!-- FADE-IN -->
<script>$(document).ready(function(){$('.fade-in').hide().fadeIn();});</script>
<!--<script>.modal.in .modal-dialog {transform: none;}</script>-->

<!--ANIMATED COUNTER SWING -->
<script>
$('.animated-counter').each(function () {
    $(this).prop('Counter', 0).animate({
        Counter: $(this).text()
    }, {
        duration: 1000,
        easing: 'linear', 
        step: function (now) {
            $(this).text(Math.ceil(now));
        }
    });
});
</script>

</body>
</html>
