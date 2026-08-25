<?php

function getCharacterName($characterID) {
    if (!is_numeric($characterID)) return FALSE;
    $c = db_asocquery("SELECT * FROM  `apicorpmembers` WHERE `characterID` = $characterID");
    if (is_array($c) && count($c) > 0) {
        $c = $c[0];
        return $c['name'];
    }
}

// The three functions below (getCharacterInfoXML, getCharInfoXML,
// getCharactersXML) called CCP's legacy XML API (api.eveonline.com /
// {custom}/eve/CharacterInfo.xml.aspx), which CCP shut down years ago.
// They're kept only so nothing that still references them fatals; none of
// them are called from anywhere in the app anymore (character linking now
// goes through ESI tokens - see Settings -> ESI API Tokens, include/94.php,
// include/95.php). They intentionally no longer make network calls.

function getCharacterInfoXML($id) {
	return FALSE; // retired CCP XML API, see comment above
}

function getCharInfoXML($id) {
	return FALSE; // retired CCP XML API, see comment above
}

function getCharactersXML($keyid,$verification) {
	return FALSE; // retired CCP XML API, see comment above
}

function getCharacters() {
	return db_asocquery("SELECT apc.* "
                . "FROM `lmchars` lmc "
                . "JOIN `apicorpmembers` apc ON lmc.`charID` = apc.`characterID`"
                . "WHERE `userID` = " . $_SESSION['granted']);
}

function getCharactersDropdown() {
    if (getConfigItem('only_linked_chars', 'disabled') == 'enabled') {
        $chars=db_asocquery("SELECT apc.`characterID` , apc.`name`
        FROM `apicorpmembers` apc
        JOIN `lmchars` lmc ON apc.`characterID` = lmc.`charID`
        ORDER BY name;");
    } else {
        $chars=db_asocquery("SELECT characterID, name FROM `apicorpmembers` ORDER BY name;");
    }
    return $chars;
}
/* moved to db.php
function getCharacterPortrait($characterID, $size = 32) {
    if (!is_numeric($characterID)) $characterID=0;
    if (!is_numeric($size) || ($size!=32 && $size!=64 && $size!=256 && $size!=512)) $size=32;
    $icon="https://imageserver.eveonline.com/character/{$characterID}_{$size}.jpg";
    return($icon);
}
*/
function isValidCorp($corporationID) {
    $count=db_count("SELECT `corporationID` FROM `apicorps` WHERE `corporationID`=$corporationID;");
    if ($count==1) return TRUE; else return FALSE;
}

function isInMembers($characterID) {
    $count=db_count("SELECT `characterID` FROM `apicorpmembers` WHERE `characterID`=$characterID;");
    if ($count==1) return TRUE; else return FALSE;
}

function filterByCorps($chars) {
    foreach($chars as $key => $toon) {
        $attrs=$toon->attributes();
        if (isValidCorp($attrs->corporationID)) $ret[$i++]=$toon;
    }
    return $ret;
}

function filterByMembersApi($chars) {
    foreach($chars as $key => $toon) {
        $attrs=$toon->attributes();
        if (isInMembers($attrs->characterID)) $ret[$i++]=$toon;;
    }
    return $ret;
}

function displayCharacters($chars) {
    if (count($chars)>0) {
        foreach($chars as $toon) {
            $attrs=$toon->attributes();
            ?>
            <img src="<?=getCharacterPortrait($attrs->characterID, 64)?>" alt="<?php echo($attrs->name); ?>" title="<?php echo($attrs->name); ?>" /> <img src="<?= getCorporationLogo($attrs->corporationID, 64)?>" alt="<?php echo($attrs->corporationName); ?>" title="<?php echo($attrs->corporationName); ?>" /><br />
            <?php
        }   
    } else {
        echo('<strong>No characters!</strong>');
    }
}

function connectCharacters($chars) {
    $i=0;
    if (count($chars)>0) {
        foreach($chars as $toon) {
            $attrs=$toon->attributes(); 
            $sql="INSERT IGNORE INTO `lmchars` VALUES (
			".$attrs->characterID.",
			".$_SESSION['granted']."
			);";
            db_uquery($sql);
            $i++;
        }    
    }
    return $i;
}


?>
