<?php
include "../include/db.php";
include "../include/general.php";

# External access support (authenticate only if no key provided, or if invalid access key provided)
$k=getvalescaped("k","");if (($k=="") || (!check_access_key(getvalescaped("ref","",true),$k))) {include "../include/authenticate.php";}

$ref=getval("ref","");
$size=getval("size","");
$ext=getval("ext","");
if(!preg_match('/^[a-zA-Z0-9]+$/', $ext)){$ext="jpg";} # Mitigate path injection
$alternative=getval("alternative",-1);
$search=getvalescaped("search","");
$usage=getval("usage","-1");
$usagecomment=getval("usagecomment","");


if ($download_usage && getval("usage","")=="" && !$direct_download)
	{
	redirect($baseurl_short."pages/download_usage.php?ref=" . urlencode($ref)  . "&size=" . urlencode($size) . "&ext=" . urlencode($ext) . "&k=" . urlencode($k) . "&alternative=" . urlencode($alternative));
	}

if (!($url=hook("getdownloadurl", "", array($ref, $size, $ext, 1, $alternative)))) // used in remotedownload-plugin
	{
	$url=$baseurl."/pages/download.php?ref=" . urlencode($ref)  . "&size=" . urlencode($size) . "&ext=" . urlencode($ext) . "&k=" . urlencode($k) . "&alternative=" . urlencode($alternative) . "&usage=" . urlencode($usage) . "&usagecomment=" . urlencode($usagecomment);
	}

# For Opera and Internet Explorer 7 - redirected downloads are always blocked, so use the '$save_as' config option
# to present a link instead.
if (!$direct_download_allow_opera &&  strpos(strtolower($_SERVER["HTTP_USER_AGENT"]),"opera")!==false) {$save_as=true;}
if (!$direct_download_allow_ie7 &&  strpos(strtolower($_SERVER["HTTP_USER_AGENT"]),"msie 7.")!==false) {$save_as=true;}
if (!$direct_download_allow_ie8 &&  strpos(strtolower($_SERVER["HTTP_USER_AGENT"]),"msie 8.")!==false) {$save_as=true;}

include "../include/header.php";

if (!$save_as)
	{
	?>
	<script type="text/javascript">
	window.setTimeout("document.location='<?php echo $url?>'",1000);
	</script>
	<?php
	}
?>

<div class="BasicsBox">

    
	<?php if ($save_as) { 
	# $save_as set or Opera browser? Provide a download link instead. Opera blocks any attempt to send it a download (meta/js redirect)	?>
    <h1><?php echo $lang["downloadresource"]?></h1>
    <p style="font-weight:bold;">&gt;&nbsp;<a href="<?php echo $url?>"><?php echo $lang["rightclicktodownload"]?></a></p>
	<?php } else { 
	# Any other browser - standard 'your download will start shortly' text.
	?>
    <h1><?php echo $lang["downloadinprogress"]?></h1>
    <p><?php echo text("introtext")?></p>
	<?php } ?>
    <p><a onClick="return CentralSpaceLoad(this,true);" href="<?php echo $baseurl_short?>pages/view.php?ref=<?php echo urlencode($ref) ?>&k=<?php echo urlencode($k) ?>&search=<?php echo urlencode(getval("search",""))?>&offset=<?php echo urlencode(getval("offset",""))?>&order_by=<?php echo urlencode(getval("order_by",""))?>&sort=<?php echo urlencode(getval("sort",""))?>&archive=<?php echo urlencode(getval("archive",""))?>">&lt;&nbsp;<?php echo $lang["backtoresourceview"]?></a></p>
    <p><a onClick="return CentralSpaceLoad(this,true);" href="<?php echo $baseurl_short?>pages/search.php?k=<?php echo urlencode($k) ?>&search=<?php echo urlencode(getval("search",""))?>&offset=<?php echo urlencode(getval("offset",""))?>&order_by=<?php echo urlencode(getval("order_by",""))?>&sort=<?php echo urlencode(getval("sort",""))?>&archive=<?php echo urlencode(getval("archive",""))?>">&lt;&nbsp;<?php echo $lang["backtoresults"]?></a></p>
    
    <?php if ($k=="") { ?>
    <p><a onClick="return CentralSpaceLoad(this,true);" href="<?php echo $baseurl_short?>pages/home.php">&lt;&nbsp;<?php echo $lang["backtohome"]?></a></p>
    <?php } ?>
</div>

<?php
include "../include/footer.php";
?>
