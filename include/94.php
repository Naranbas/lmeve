<?php
//standard header for each included file
checksession(); //check if we are called by a valid session
if (!checkrights("Administrator,ViewOwnCharacters,ViewAllCharacters,EditCharacters")) { //"Administrator,ViewOverview"
	global $LANG;
	echo("<h2>{$LANG['NORIGHTS']}</h2>");
	return;
}
$MENUITEM=9; //Panel ID in menu. Used in hyperlinks
$PANELNAME='Characters'; //Panel name (optional)
//standard header ends here

// This page used to collect a "personal API Key" (keyID/vCode) and call
// CCP's legacy XML API (api.eveonline.com) to look up characters on it.
// That API was retired by CCP years ago and no longer responds, so the old
// form here would just hang or fail. Character/corp data is linked through
// ESI tokens now (see Settings -> ESI API Tokens), so this page just points
// people there instead of pretending the old flow still works.
?>	    <div class="tytul">
		<?php echo($PANELNAME); ?><br>
	    </div>
	    <img src="<?=getUrl()?>ccp_icons/2_64_16.png"  alt="Characters" style="float: left;"/><h2>Personal API Keys are no longer available</h2>
            <img src="<?=getUrl()?>ccp_icons/38_16_208.png" alt="(!) " style="float: left;"/>
            CCP retired the legacy API key system (keyID/vCode) this page used to use. Character and corporation
            data is now linked through EVE SSO / ESI tokens instead.<br/><br/>
            Go to <strong>Settings &rarr; ESI API Tokens</strong> to add or manage tokens for your corporation.
            Characters covered by an active corp ESI token will show up automatically.<br/><br/>
            <form method="get" action="">
		<input type="hidden" name="id" value="9" />
		<input type="hidden" name="id2" value="0" />
		<input type="submit" value="OK" />
            </form>
