<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="stylesheet" href="Paper/style.css" type="text/css">
	<link href="Paper/font/font.css" type="text/css" rel="stylesheet" media="all"/>
	<script src="Paper/jquery.js"></script>
	<title>PaperFont</title>
</head>
<body>
	
<!--Index de Paperfont-->

	<!--Header-->
	<header>
		<a style="font-size:18px;font-family:'gentium-r';">PaperFont</a>
		<a id="button_info" >INFO</a>
		<div id="presentation" >
			<h1> 
			PaperFont est une interface servant à développer des formes typographique à plusieurs.
			Ces formes sont éditées en javascript et utilisent la librairie <a href="http://paperjs.org/">PaperJs</a> développé par Jürg Lehni & Jonathan Puckey.
			Cette interface a également pour but de rendre visible l'évolution du projet, par un système de versionning et de changelog.
			Un chat y est intégré pour faciliter la communication entre les contributeurs. 
			</h1>
			<h2>
			DOCUMENTATION</br>
			-------------</br>
			Le projet sur <a href="https://github.com/Antoine-Gelgon" target="_blank" >Github</a></br>
			Références de <a href="http://paperjs.org/reference/"  target="_blank" >Paperjs</a></br>
			</br>
			CREDITS</br>
			------</br>
			PaperFont est développer par <a href="http://antoine-gelgon.fr/ target="_blank" ">Antoine Gelgon</a></br>.
			Les typographies utilisé : <a href="http://scripts.sil.org/cms/scripts/page.php?site_id=nrsi&id=gentium_download"  target="_blank" >Gentium</a>, <a href="http://www.marksimonson.com/fonts/view/anonymous-pro"  target="_blank" >Anonymous</a></br>
			</h2>
		</div>

	</header>

	<div class="content">
	
		<!--Fenetre d'éditeur et de canvas-->
		<iframe id="wind_editor" src="wind_editor.php"></iframe>
		
		<!--Log du projet-->
		<div id="wind_log">
			<div class="titre-rub">LOG</div>
			<iframe src="wind_log.php"></iframe>
		</div>
		
		<!--Archive de formes-->
		<div id="wind_casse">
			<div class="titre-rub">CASSE</div>
			<iframe src="wind_casse.php"></iframe>
		</div>
		
		<!--Visualisation des lettres-->
		<div id="wind_screen">
			<div class="titre-rub">VISUALISATION</div>
			<iframe src="wind_screen.php"></iframe>
		</div>
		
		<!--Discussion entre contributeurs-->
		<div id="wind_comment">
			<div class="titre-rub">CHAT</div>
			<iframe src="wind_comment.php"></iframe>
		</div>
	
</div>

	<!--Jquery-->
	<script>
	$( "#presentation" ).hide();
	$( "#button_info" ).click(function() {
 	$( "#presentation" ).toggle("fast");
	});
	</script>


	</body>
</html>