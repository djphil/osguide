<h1>Help<i class="glyphicon glyphicon-education pull-right"></i></h1>
Welcome to the <?php echo $osguide." v".$version; ?>

<article>
    <h2>Features</h2>
    SQLite 3 and Mysql compatible<br />
    100 Terminals by Simulator<br />
    Access restiction (host/uuid)<br />
    Security logs<br />
    Agents online counter<br />
    More coming ...
    <h3>Inworld:</h3>
    <a class="btn btn-default btn-success btn-xs" href="inc/destinations.php" target="_blank">
    <i class="glyphicon glyphicon-eye-open"></i> Demo</a>
</article>

<article>
    <h2>Requirment</h2>
    Mysql or Sqlite 3, Php5 with curl actived, Apache<br />
    Ossl enable
</article>

<article>
    <h2>Download</h2>
    <a class="btn btn-default btn-success btn-xs" href="https://github.com/djphil/osguide" target="_blank">
    <i class="glyphicon glyphicon-save"></i> GithHub</a> Source Code
</article>

<article>
    <h2>Install</h2>
    <?php echo $osguide; ?> have a "special" page for viewers (inc/destinations.php)<br />
    <h3>Robust.ini</h3>
    <pre>
    [LoginService]
    ; For V3 destination guide
    ; DestinationGuide = "${Const|BaseURL}/guide"
    DestinationGuide = "http://yourdomain.com/osguide/inc/destinations.php"
    </pre>
    <h3>OpenSim.ini</h3>
    <pre>
    [Network]
    ExternalHostNameForLSL = yourdomain.com
    OutboundDisallowForUserScripts = ""
    shard = "OpenSim"
    user_agent = "OpenSim LSL (Mozilla Compatible)"
    </pre>
    <h3>osslEnable.ini</h3>
    <pre>
    [XEngine]
    AllowOSFunctions = true 
    </pre>
    <p>And you should allow the following ossl functions to the parcel owner/manager</p>
    <pre>
    osKey2Name
    osGetGridName
    osGetMapTexture
    </pre>
</article>

<article id="AddRegion">
    <h2>Add Region</h2>
    Only the region/parcel owner is authorised to add a region/parcel to the OpenSim Destination Guide.
    <h3>Inword:</h3>
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

<article id="RemoveRegion">
    <h2>Remove Region</h2>
    Only the region/parcel owner is authorised to remove a region/parcel from the OpenSim Destination Guide.
    <h3>Inworld:</h3>
    <ol>
        <li>Remove the OpenSim Destination Guide Terminal from your region/parcel.</li>
    </ol>
    <h3>Outworld:</h3>
    <ol>
        <li>Click on the "update" button on your region/parcel details page on the OpenSim Destination Guide.</li>
    </ol>
</article>

<article>
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
                $file = file_get_contents('lsl/OpenSim Destination Guide Terminal v0.3.lsl', true);
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
