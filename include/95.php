<?php
checksession(); //check if we are called by a valid session
if (!checkrights("Administrator,ViewOwnCharacters,ViewAllCharacters,EditCharacters")) { //"Administrator,ViewOverview"
	global $LANG;
	echo("<h2>{$LANG['NORIGHTS']}</h2>");
	return;
}
$MENUITEM=9; //Panel ID in menu. Used in hyperlinks
$PANELNAME='Characters'; //Panel name (optional)
//standard header ends here

// This used to submit to CCP's legacy XML API (api.eveonline.com) via
// getCharactersXML(). That API is long gone, so rather than firing a
// request that can only fail, redirect back to the notice on 94.php.
?>
            <span class="tytul">
		<?php echo($PANELNAME); ?>
	    </span>
            <img src="<?=getUrl()?>ccp_icons/38_16_208.png" alt="(!) " style="float: left;"/>
            Personal API Keys were retired by CCP and can no longer be used. Please use
            <strong>Settings &rarr; ESI API Tokens</strong> to link characters instead.<br/><br/>
            <form method="get" action="">
		<input type="hidden" name="id" value="9" />
		<input type="hidden" name="id2" value="0" />
		<input type="submit" value="OK" />
            </form>
