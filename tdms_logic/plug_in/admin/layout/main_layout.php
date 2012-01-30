<html>
<head>
	<title><?=PAGE_TITLE?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
	<script language="javascript" src="/<?=JS_PATH?>sbh.js"></script>
	<link rel="stylesheet" href="/<?=CSS_PATH?>basic.generic.css" type="text/css">
</head>

<body bgcolor="#ffffff" text="#000000" link="#669966" alink="#993333" vlink="#669966">
<div id="wrap">

    <div id="admin_header">
	<table border="0" cellspacing="0" cellpadding="1" width="100%" height="100%">
		<tr>
			<td class="nav" align="left" valign="bottom" nowrap="nowrap">
				<div style="margin-left: 5px;font-weight:bold">
				<a href="/ADMIN">홈</a> &middot;
				<a href="/ADMIN/element/Plist">서비스 관리</a> &middot;
				<a href="/ADMIN/member/Plist">회원 관리</a> &middot;
				<a href="/ADMIN/plugin/Plist">플러그인 관리</a> &middot;
				</div>
			</td>
			<td class="headerdate" align="right" valign="top" nowrap="nowrap">
				<div style="margin-right: 5px">
				</div>
			</td>
		</tr>
		</table>
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
	<div id="footer">
		<?=GET_HTML_BLOCK("main/foot")?>
	</div>

</div>

</body>
</html>


