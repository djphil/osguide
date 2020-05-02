<?php if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {die('Access denied ...');} ?>
<h1>Help<i class="glyphicon glyphicon-education pull-right"></i></h1>
<div class="clearfix"></div>

<section>
<article>
    Welcome to the <?php echo $title." v".$version; ?>
</article>

<article>
    <h2>Features</h2>
    SQLite 3 and Mysql compatible <i class="glyphicon glyphicon-ok text-success"></i><br />
    Terminals restiction (by simulator) <i class="glyphicon glyphicon-ok text-success"></i><br />
    Access restiction (by host/uuid) <i class="glyphicon glyphicon-ok text-success"></i><br />
    Agents online counter <i class="glyphicon glyphicon-ok text-success"></i><br />
    Security logs <i class="glyphicon glyphicon-ok text-success"></i><br />
    More coming ...
    <h3>Inworld:</h3>
    <a class="btn btn-success btn-xs" href="inc/destinations-inworld.php" target="_blank">
    <i class="glyphicon glyphicon-eye-open"></i> Demo</a> <?php echo $title; ?>
</article>

<article>
    <h2>Requirement</h2>
    Mysql or Sqlite 3, Php5 with curl actived, Apache<br />
    Ossl enable
</article>

<article>
    <h2>Download</h2>
    <a class="btn btn-success btn-xs" href="<?php echo $github_url; ?>" target="_blank">
    <i class="glyphicon glyphicon-save"></i> Github</a> Source Code
</article>

<article>
    <h2>Install</h2>
    <?php echo $title; ?> have a "special" page for viewers (inc/destinations-inworld.php)<br />
    <h3>Robust.ini</h3>
    <pre>
[LoginService]
    ; For V3 destination guide
    ; DestinationGuide = "${Const|BaseURL}/osguide/inc/destinations-inworld.php"
    DestinationGuide = "http://yourdomain.com/osguide/inc/destinations-inworld.php"</pre>
    <h3>OpenSim.ini</h3>
    <pre>
[Network]
    ExternalHostNameForLSL = yourdomain.com
    OutboundDisallowForUserScripts = ""
    shard = "OpenSim"
    user_agent = "OpenSim LSL (Mozilla Compatible)"</pre>
    <pre>
[LL-Functions]
    max_external_urls_per_simulator = 100</pre>

    <h3>osslEnable.ini</h3>
    <pre>
[XEngine]
    AllowOSFunctions = true </pre>
    <p>And you should allow the following ossl functions to the parcel owner/manager</p>
    <pre>
    osKey2Name
    osGetGridName
    osGetMapTexture</pre>
</article>

<article id="AddDestination">
    <h2>Add Destination</h2>
    Only the region/parcel owner is authorised to add a destination (region/parcel) to the OpenSim Destination Guide.
    <h3>Inworld:</h3>
    <ol>
        <li>Download the OpenSim Destination Guide Terminal script (LSL).</li>
        <li>Copy the OpenSim Destination Guide Terminal script into a prim, configure and compile it.<br />
            (configurable variables: targetUrl, terminal_name, categorie_name, update_rate).
        </li>
        <li>Click on the prim to update your region/parcel informations in the Opensim Destination Guide.</li>
    </ol>
    <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#terminal">
    <i class="glyphicon glyphicon-save"></i> Download</button> OpenSim Destination Guide Terminal
</article>

<article id="EditDestination">
    <h2>Edit Destination</h2>
    Only the region/parcel owner is authorised to edit a destination (region/parcel) to the OpenSim Destination Guide.
    <h3>Inworld:</h3>
    <ol>
        <li>Reconfigure the OpenSim Destination Guide Terminal script and recompile it.<br />
            (configurable variables: targetUrl, terminal_name, categorie_name, update_rate).
        </li>
        <li>Click on the prim to update your region/parcel informations in the Opensim Destination Guide.</li>
    </ol>
</article>

<article id="DeleteDestination">
    <h2>Delete Destination</h2>
    Only the region/parcel owner is authorised to delete a destination (region/parcel) to the OpenSim Destination Guide.
    <h3>Inworld:</h3>
    <ol>
        <li>Remove the OpenSim Destination Guide Terminal from your region/parcel.</li>
    </ol>
    <h3>Outworld:</h3>
    <ol>
        <li>Refresh the destination and it will be automatically removed from the guide and database.</li>
    </ol>
</article>

<article id="InstallingImages">
    <h2>Installing Images</h2>
    Only the owner of the OpenSim Destination Guide is authorised to installing images.
    <h3>Outworld:</h3>
    <ol>
        <li>You have to create them yourself and place them in the "img" folder.</li>
        <li>The recommended dimension is the same as default.jpg (700px * 400px).</li>
        <li>The names of category images are the exact names of the categories in the guide (min/maj/space must be respected).</li>
        <li>The names of regions images are the exact names of the regions in the guide (min/maj/space must be respected).</li>
    </ol>
</article>

<article id="License">
    <h2>License</h2>
    GNU/GPL General Public License v3.0<br />
</article>

<article>
    <h2>Credit</h2>
    Philippe Lemaire (djphil)
</article>

<article>
    <h2>Donation</h2>
    <p><?php include_once("inc/paypal.php"); ?></p>
</article>

<div class="modal fade" id="terminal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">OpenSim Destination Guide Terminal v0.3.lsl</h4>
            </div>
            <div class="modal-body">
                <?php
                $file = file_get_contents($script_url, true);
                echo '<pre>'.$file.'</pre>';
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
</section>
