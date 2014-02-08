<?php

require_once "common.php";

dbConnect();

outputHeader(4);

$message = "";

if ($nsid == "cag047" || $nsid == "rlm412" || $nsid=="ssb609")
{

    // process form submissions
    if ($_POST)
    {
        $personId = sanitize($_POST['NAME']);
        $NSID = sanitize($_POST['NSID']);
        
        $saveEdit = (int)($_POST['SAVEEDIT']);
        
        if ($saveEdit == 0)
        {
            $saveEdit = $personId;
        }
        
        if ($NSID && $saveEdit > 0)
        {
            mysql_query("UPDATE `person` SET `NSID`='$NSID', `IsAdmin`='1' WHERE `Id`='$saveEdit' LIMIT 1");
            
            $message = "Your changes have been saved to the database.";
        }
    }

    // process demotions
    $demote = (int)($_GET['demote']);

    if ($demote > 0)
    {
        mysql_query("UPDATE `person` SET `IsAdmin`='0' WHERE `Id`='$demote' LIMIT 1");
        
        $message = "The person has been demoted.";
    }

    // load data for edits
    $edit = false;
    $editId = (int)($_GET['edit']);

    if ($editId > 0)
    {
        $editQuery = mysql_query("SELECT * FROM `person` WHERE `Id`='" . $editId . "' LIMIT 1");
        
        if (mysql_num_rows($editQuery) > 0)
        {
            $editRow = mysql_fetch_assoc($editQuery);
            $edit = true;
        }
    }

    if ($message)
    {
        echo "<div class=\"message\">$message</div>";
    }

    ?>

    <?php

    if ($edit)
    {
        echo "<h2>Edit Admin</h2>";
    }
    else
    {
        echo "<h2>Add an Admin</h2>";
    }

    ?>

    <form method="post" action="privileges.php" enctype="multipart/form-data">
    <table cellspacing="6" width="550">
    <tr>
        <td class="prompt">Name:</td>
        <td><select name="NAME" style="width: 275px">
        <option value=""></option>
        <?php
        
        $query = mysql_query("SELECT * FROM `person` ORDER BY `LastName` ASC");
        
        if (mysql_num_rows($query) > 0)
        {
            while ($row = mysql_fetch_assoc($query))
            {
                echo "<option value=\"" . $row['Id'] . "\">";
                
                if ($row['FirstName'] && $row['LastName'])
                {
                    echo htmlspecialchars(stripslashes($row['LastName'])) . ", " . htmlspecialchars(stripslashes(substr($row['FirstName'], 0, 1))) . ".";
                }
                else
                {
                    echo htmlspecialchars(stripslashes($row['FirstName']));
                }
                
                echo "</option>";
            }
        }
        
        ?></select></td>
    </tr>
    <tr>
        <td class="prompt">NSID:</td>
        <td><input type="text" class="text" style="width: 271px" name="NSID" value="<?php if ($edit) echo stripslashes(htmlspecialchars($editRow['NSID'])); ?>" /></td>
    </tr>
    <tr>
        <td></td>
        <td>
        <input type="hidden" name="SAVEEDIT" value="<?php if ($edit) echo $editId; ?>" />
        <input type="submit" value="Submit" />
        </td>
    </tr>
    </table>
    </form>

    <h2>Admins</h2>

    <table cellspacing="0" cellpadding="2" width="450">
    <?php

    $query = mysql_query("SELECT * FROM `person` WHERE `IsAdmin`='1' ORDER BY `person`.`FirstName`");

    $i = 1;

    if (@mysql_num_rows($query) > 0)
    {
        while ($row = mysql_fetch_assoc($query))
        {
            if ($i % 2)
                echo "<tr class=\"pubrow alternate\">";
            else
                echo "<tr class=\"pubrow\">";
            
            echo "<td>" . htmlspecialchars(stripslashes($row['FirstName'])) . " " . htmlspecialchars(stripslashes($row['LastName'])) . "</td>";
            echo "<td width=\"105\">" . htmlspecialchars(stripslashes($row['NSID'])) . "</td>";
            echo "<td class=\"edit\"><a href=\"privileges.php?edit=" . $row['Id'] . "\">edit</a></td>";
            echo "<td class=\"demote\"><a href=\"privileges.php?demote=" . $row['Id'] . "\">demote</a></td>";
            echo "</tr>";
            
            $i++;
        }
    }

    ?>
    </table>

<?php
}

outputFooter();

?>
