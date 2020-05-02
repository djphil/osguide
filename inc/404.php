<?php if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {die('Access denied ...');} ?>
<style>
.error404 {
    margin: 0 auto;
    text-align: center;
    max-width: 100%;   
}
.error404 h1 {
    font: bold 10vw Arial, sans-serif; /* 10vw OU 10em*/
    background-color: #555;
    color: transparent;
    text-shadow: 0px 1px 2px rgba(255, 255, 255, 0.25);
    -webkit-background-clip: text;
       -moz-background-clip: text;
            background-clip: text;
}
</style>

<div class="row">
    <div class="col-md-12">
        <div class="error404">
            <h1>404</h1>
            <p>Oops! Error 404: Page not found ...</p>
        </div>
    </div>
</div>
