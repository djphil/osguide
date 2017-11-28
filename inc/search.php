<h1>Search<i class="glyphicon glyphicon-search pull-right"></i></h1>

<!-- TODO
<form class="form-inline spacer" role="search" action="" enctype="multipart/form-data" method="POST">
    <div class="form-group">
        <label for="searchwordFOR">Search Word :</label>
        <input type="text" class="form-control" id="searchwordID" name="searchword" placeholder="Terminal">
    </div>
    <button id="Submit" name="submit" type="submit" class="btn btn-default">
        <i class="glyphicon glyphicon-search"></i> Search
    </button>
</form>
-->

<!-- TODO
<article>
    <h2>Search by Region</h2>
    Region Word : <input type="text" name="regionname" maxlength="64"><br />
    Region UUID : <input type="text" name="regionuuid" maxlength="36"><br />
</article>

<article>
    <h2>Search by Owner</h2>
    Owner Name : <input type="text" name="ownername" maxlength="64"><br />
    Owner UUID : <input type="text" name="owneruuid" maxlength="36"><br />
</article>

<article>
    <h2>Search by Category</h2>
    <input type="checkbox" name="OfficialLocation" value="OfficialLocation">Official location<br />
    <input type="checkbox" name="ArtAndCulture" value="ArtAndCulture">Art and culture<br />
    <input type="checkbox" name="Business" value="Business">Business<br />
    <input type="checkbox" name="Educationnal" value="Educationnal">Educationnal<br />
    <input type="checkbox" name="Gaming" value="Gaming">Gaming<br />
    <input type="checkbox" name="Hangout" value="Hangout">Hangout<br />
    <input type="checkbox" name="NewcomerFriendly" value="NewcomerFriendly">Newcomer Friendly<br />
    <input type="checkbox" name="ParkAndNature" value="ParkAndNature">Park and Nature<br />
    <input type="checkbox" name="Residential" value="Residential">Residential<br />
    <input type="checkbox" name="Shopping" value="Shopping">Shopping<br />
    <input type="checkbox" name="Other" value="Other">Other<br />
    <input type="checkbox" name="Rental" value="Rental">Rental<br />
</article>

<article>
    <input id="Submit" name="submit" type="submit" value="Submit" /> 
    <input id="Reset" name="reset" type="reset" value="Reset" /><br />
</article>
-->

<?php
if (isset($_POST['search']))
{
    if (!empty($_POST['searchword']))
    {
        $search_word  = htmlspecialchars($_POST['searchword']);
        $query = ('
            SELECT * 
            FROM '.$tbname.' 
            WHERE region_name LIKE ?
            OR owner_name LIKE ?
            OR owner_uuid LIKE ? 
            OR object_name LIKE ? 
            OR object_uuid LIKE ? 
            OR categorie_name LIKE ? 
        ');
        $query = $db->prepare($query);

        $value = "%{$search_word}%";
        $query->bindValue(1, $value, PDO::PARAM_STR);
        $query->bindValue(2, $value, PDO::PARAM_STR);
        $query->bindValue(3, $value, PDO::PARAM_STR);
        $query->bindValue(4, $value, PDO::PARAM_STR);
        $query->bindValue(5, $value, PDO::PARAM_STR);
        $query->bindValue(6, $value, PDO::PARAM_STR);

        $query->execute();

        if ($useSQLite == TRUE) $row = $query->rowCount() >= 0;
        else $row = $query->rowCount() != 0;
        
        echo "<h3>Result(s):</h3>\n";

        if ($row)
        {
            $counter = 0;

            echo "<p>";

            while ($result = $query->fetch()) 
            {
                $region_name    = $result['region_name'];
                $owner_name     = $result['owner_name'];
                // $owner_uuid  = $result['owner_uuid'];
                $object_name    = $result['object_name'];
                // $object_uuid = $result['object_uuid'];
                $categorie_name = $result['categorie_name'];
                echo "\n<span class='badge'>".++$counter.
                     "</span> ".$region_name.
                     " (".$owner_name.
                     ") from ".$object_name.
                     " in categorie ".$categorie_name.
                     "<br />\n";
            }

            echo "</p>";
        }

        else 
        {
            echo 'Nothing found';
        }
    }

    else 
    {
        // $_SESSION[flash][danger] = "Please enter a search query";
        echo '<p class="alert alert-danger alert-anim">';
        echo '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
        echo 'Please enter a search query</p>';
    }
}

else
{
    echo '<p class="alert alert-danger alert-anim">';
    echo '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
    echo 'Please enter a search query</p>';
}

unset($region_name);
unset($owner_name);
// unset($owner_uuid);
unset($object_name);
// unset($object_uuid);
unset($categorie_name);
$query = null;
?>
