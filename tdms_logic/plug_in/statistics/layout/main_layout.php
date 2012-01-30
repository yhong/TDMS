<html>
<head>
	<title><?=PAGE_TITLE?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
	<script language="javascript" src="/<?=JS_PATH?>sbh.js"></script>
	<link rel="stylesheet" href="/<?=CSS_PATH?>basic.generic.css" type="text/css">
</head>

<body bgcolor="#ffffff" text="#000000" link="#669966" alink="#993333" vlink="#669966">
<div id="wrap">

    <div id="header">
		<?=GET_HTML_BLOCK("main/topmenu")?>
	</div>

    <div class="sidebar">

		<?
		if(!empty($_SESSION['auth_username']) && !empty($_SESSION['auth_password']))
			echo GET_HTML_BLOCK("session/logout");
		else
			echo GET_HTML_BLOCK("session/login");
		?>

    </div>

    <div id="content">

      <?=$MAIN_CONTENT?>

    </div>

    <div style="clear:both;"></div>

</div>
	<div id="footer">
		<?=GET_HTML_BLOCK("main/foot")?>
	</div>
</body>
</html>


