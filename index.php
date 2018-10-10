<?php
require_once './config.php';
require_once './options.php';
session_start();
date_default_timezone_set($option['timezone']);
if(isset($_SESSION['lang'])) {
    if($_SESSION['lang'] != "") {
        if($_SESSION['lang'] == "hi") {
            require_once './lang/hi.php';
        } else if($_SESSION['lang'] == "fr") {
            require_once './lang/fr.php';
        } else if($_SESSION['lang'] == "ch") {
            require_once './lang/ch.php';
        } else if($_SESSION['lang'] == "ar") {
            require_once './lang/ar.php';
        } else if($_SESSION['lang'] == "sp") {
            require_once './lang/sp.php';
        } else if($_SESSION['lang'] == "ru") {
            require_once './lang/ru.php';
        } else if($_SESSION['lang'] == "de") {
            require_once './lang/de.php';
        } else if($_SESSION['lang'] == "pl") {
            require_once './lang/pl.php';
        } else {
            require_once './lang/en.php';
        }
    } else {
        if(isset($option['defaultlanguage']) && $option['defaultlanguage'] != "" && $option['defaultlanguage'] != null) {
            require_once './lang/'.$option['defaultlanguage'].'.php';
            $_SESSION['lang'] = $option['defaultlanguage'];
        } else {
            require_once './lang/en.php';
            $_SESSION['lang'] = "en";
        }
    }
} else {
    if(isset($option['defaultlanguage']) && $option['defaultlanguage'] != "" && $option['defaultlanguage'] != null) {
        require_once './lang/'.$option['defaultlanguage'].'.php';
        $_SESSION['lang'] = $option['defaultlanguage'];
    } else {
        require_once './lang/en.php';
        $_SESSION['lang'] = "en";
    }
}
if(isset($_COOKIE['tmail-emails'])) {
    $emailList = unserialize($_COOKIE['tmail-emails']);
    $_SESSION["emails"] = $emailList;
}
error_reporting(E_ALL);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $config['title']; ?></title>
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400" rel="stylesheet"> 
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="core/signals.js"></script>
    <script src="core/hasher.min.js"></script> 
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</head>
<body onload="setWidth()">
    <audio id="notifyUserSound" src="sound.mp3" preload="auto"></audio>
    <div id="aboutus" class="container">
        <div id="aboutusHeader" class="header">
            <div id="closeIcon" onclick="closeAboutUs()">X</div>
        </div>
        <div class="aboutus"><br><br><?php echo $option['aboutus']; ?></div>
    </div>
    <div id="main" class="container">
        <div class="header">
            <div id="logo" data-placement="left" data-toggle="tooltip" title="<?php echo $config['title']; ?>" onclick="showAboutUs()"><img src="logo.png"></div>
            <select class="setLang" name="lang" onchange="setLang()">
                <option value="en" <?php if ( $_SESSION['lang'] == "en") { echo "selected"; } ?>>English</option>
                <option value="hi" <?php if ( $_SESSION['lang'] == "hi") { echo "selected"; } ?>>हिंदी</option>
                <option value="fr" <?php if ( $_SESSION['lang'] == "fr") { echo "selected"; } ?>>Français</option>
                <option value="ch" <?php if ( $_SESSION['lang'] == "ch") { echo "selected"; } ?>>中文</option>
                <option value="ar" <?php if ( $_SESSION['lang'] == "ar") { echo "selected"; } ?>>عربى</option>
                <option value="sp" <?php if ( $_SESSION['lang'] == "sp") { echo "selected"; } ?>>Español</option>
                <option value="ru" <?php if ( $_SESSION['lang'] == "ru") { echo "selected"; } ?>>русский</option>
                <option value="de" <?php if ( $_SESSION['lang'] == "de") { echo "selected"; } ?>>Deutsch</option>
                <option value="pl" <?php if ( $_SESSION['lang'] == "pl") { echo "selected"; } ?>>Polskie</option>
            </select>
        </div>
        <div id="generateID" align="center">
            <input onKeyPress="checkEnter(event, this)" class="setEmail" type="text" name="email" placeholder="<?php echo $lang['setid'] ?>"><span class="at">@</span>
            <select class="setDomain" name="domain">
                <?php
                foreach ($config['domains'] as $value) {
                    ?><option value="@<?php echo $value; ?>"><?php echo $value; ?></option><?php
                }
                ?>
            </select>
            <a style="color: #000;" data-placement="right" data-toggle="tooltip" title="<?php echo $lang['setid'] ?>" href="#" onclick="setNewID()">
                <span class="glyphicon glyphicon-send icon"></span>
            </a>
            <div style="font-size: 18px;"><?php echo $lang['or'] ?></div>
            <div class="breakicon">
                <a style="color: #000;" data-placement="bottom" data-toggle="tooltip" title="<?php echo $lang['generaterandom'] ?>" href="#" onclick="generateRandomID()">
                    <span class="glyphicon glyphicon-random icon"></span>
                </a>
            </div>
        </div>
        <div id="createline" class="title">Hold tight! We are creating your MailBox :)</div>
        <?php if ( $_SESSION['lang'] == "ar") { ?>
        <div id="createdline" class="title"><?php echo $lang['mailboxset2'] ?><strong><span onclick="copyToClipboard('#address')" id="address" data-toggle="tooltip" title="<?php echo $lang['copyemail'] ?>"></span></strong><?php echo $lang['mailboxset1'] ?></div>
        <?php } else { ?>
        <div id="createdline" class="title"><?php echo $lang['mailboxset1'] ?><strong><span onclick="copyToClipboard('#address')" id="address" data-toggle="tooltip" title="<?php echo $lang['copyemail'] ?>"></span></strong><?php echo $lang['mailboxset2'] ?></div>
        <?php } ?>
        <div id="search-bar-container">
            <input type="search" id="search-bar" placeholder="Search"/>
        </div>
        <div id="data">
        </div>
        <div class="message">
            <?php echo $lang['refresh1'] ?><span id="timer"></span> <?php echo $lang['refresh2'] ?><br><br>
            <br><br>
            <?php echo $option['ads']; ?>
        </div>
        <div class="menu">
            <ul>
                <?php
                $i = 0;
                foreach ($option['linksTitle'] as $linksTitle) {
                ?>
                <a target="_blank" href="<?php echo $option["linksValue"][$i] ?>">
                    <li>
                        <span><?php echo $linksTitle; ?></span>
                    </li>
                </a>
                <?php
                $i++;
                }
                ?>
            </ul>
        </div>
        <br>
    </div>
    <div class="action-switch-email">
		<div class="action-list">
		    <?php if(isset($_SESSION["emails"])) { if(count($_SESSION["emails"]) > 0) { ?>
		    <a onclick="saveEMails()">
    		    <div class="saveEMails action-list-button">
        			<span class="action-info">Save EMail List</span>
        			<i class="fa fa-save"></i>
        		</div>
    		</a>
    		<a onclick="clearEMails()">
    		    <div class="clearEMails action-list-button">
        			<span class="action-info">Clear EMail List</span>
        			<i class="fa fa-trash"></i>
        		</div>
    		</a>
    		<?php } } ?>
            <a href="./">
    		    <div class="addEMail action-list-button">
        			<span class="action-info"><?php echo $lang['getnew']; ?></span>
        			<i class="fa fa-plus"></i>
        		</div>
        	</a>
			<?php if(isset($_SESSION["emails"])) { ?>
			<?php foreach ($_SESSION["emails"] as $value) { ?>
			<a class="<?php echo str_replace(".","",str_replace("@","",$value)); ?>" onclick="switchEmail('<?php echo $value; ?>')">
				<div class="action-list-button">
					<span class="action-info"><?php echo $value; ?></span>
					<i><?php echo substr($value,0,1); ?></i>
				</div>
			</a>
			<?php } } ?>
		</div>
		<div class="action-button">
			<span class="action-info">Your EMail IDs</span>
			<i class="fa fa-th-list"></i>
		</div>
	</div>
    <script src="js/scripts.js"></script>
    <!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-55210797-7"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-55210797-7');
</script>

</body>
</html> 