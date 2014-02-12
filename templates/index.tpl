{*---------------------------------------------------------------------------*}

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

{*---------------------------------------------------------------------------*}

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

{*---------------------------------------------------------------------------*}

    <title>
        {if isset($g.title)}
			{t s=$g.title m=0}
			{if isset($page)}
				| {t s=$page m=0}
			{else if isset($page_l)}
				| {$page_l}
			{/if}

        {/if}
    </title>

{*---------------------------------------------------------------------------*}

	<meta name="description" content="$g.desc">
	<link rel="stylesheet" href="{$weburl}static/reset.css"   type="text/css" media="all">
	<link rel="stylesheet" href="{$weburl}static/main.css"    type="text/css" media="all">
    <link rel="stylesheet" href="{$weburl}static/ttwform.css" type="text/css" media="screen"/>
    <link rel="stylesheet" href="{$weburl}static/uniform.css" type="text/css" media="screen"/>
    <link rel="stylesheet" href="{$weburl}static/admin.css"   type="text/css" media="all"/>
	<link rel="stylesheet" href="{$weburl}static/print.css"   type="text/css" media="print">
	<script type="text/javascript" src="{$weburl}static/main.js"></script>
	<link rel="shortcut icon" href="http://hci.usask.ca/static/images/favicon.png">
	<!--[if lte IE 6]>
		<link type="text/css" rel="stylesheet" href="/ie.css" media="all" />
	<![endif]-->

{*---------------------------------------------------------------------------*}

    <script type="text/javascript">
        var weburl = '{$weburl}';
    </script>
</head>

{*---------------------------------------------------------------------------*}

<body>
	{include file="templates/error.tpl"}
	<div id="container">

{*---------------------------------------------------------------------------*}

		<div id="header">
			<div id="logo">
				<a href="{$weburl}"><img src="{$weburl}static/images/logo-green.gif" width="261" height="61" alt="the interaction lab" border="0"></a>
			</div>
			<div id="graphic">

			</div>
		</div>

{*---------------------------------------------------------------------------*}

		<ul id="menu">
			{foreach from=$menu item=m}
				<li><a href="{gl url=$m.url}" {if isset($selectedmenu) and $selectedmenu == $m.name} class="selected" {/if}>{t s={$m.name} m=0}</a></li>
			{/foreach}
			<li style="margin-left: 281px"><a href="https://papyrus.usask.ca/trac/hci/">Trac</a></li>
		</ul>

{*---------------------------------------------------------------------------*}

		<div id="content">
			{if isset($template)}
				{include file="templates/$template.tpl"}
			{/if}
		</div>
		<div style="clear: both"></div>

{*---------------------------------------------------------------------------*}

	</div>

{*---------------------------------------------------------------------------*}

	<div id="footer">
		<div id="innerfooter">&copy; {$year} {t s=footer m=0}</div>
	</div>

{*---------------------------------------------------------------------------*}

</body>

</html>

{*---------------------------------------------------------------------------*}