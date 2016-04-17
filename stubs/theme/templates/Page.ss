<!DOCTYPE html>

<!--[if !IE]><!-->
<html lang="$ContentLocale">
<!--<![endif]-->
<!--[if IE 6 ]><html lang="$ContentLocale" class="ie ie6"><![endif]-->
<!--[if IE 7 ]><html lang="$ContentLocale" class="ie ie7"><![endif]-->
<!--[if IE 8 ]><html lang="$ContentLocale" class="ie ie8"><![endif]-->
<head>
	<% include HtmlHead %>
</head>
<body class="$ClassName">
<% include Header %>
<% include Navigation %>
<div class="main" role="main">
	<div class="inner typography line">
		$Layout
	</div>
</div>
<% include Footer %>
<script type="text/javascript" src="{$ThemeDir}/javascript/app.js"></script>
</body>
</html>
