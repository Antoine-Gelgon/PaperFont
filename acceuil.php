<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link rel="stylesheet" href="Paper/style.css" type="text/css">
<link href="Paper/font/font.css" type="text/css" rel="stylesheet" media="all"/>
<script src="Paper/jquery.js"></script>

		<title>PaperFont</title>
		
	</head>
	<body>
	
	<!--<iframe id="popup-visu" src="visualisation.php"></iframe>-->
		<header>
			
			<a style="font-size:18px;font-family:'gentium-r';">PaperFont</a>
			<a id="button_info" >INFO</a>
			<div id="presentation" > A la veille du vote sur le programme de stabilité à l'Assemblée nationale, Manuel Valls a défendu, lundi 28 avril, le « pacte » de responsabilité, « nécessaire et indispensable » selon lui. Devant un parterre de préfets, sous-préfets et de directeurs d'administrations, il a tenu à afficher sa vigilance sur l'usage que feront les entreprises des allégements de cotisations. 

</div>

		</header>
<div class="content">
	<iframe id="wind_editor"src="paperfont.php"></iframe>

	<div id="mini-blog">
		<div class="titre-rub">LOG</div>
		<iframe src="notes.php"></iframe>
	</div>
	
	<div id="casse">
		<div class="titre-rub">CASSE</div>
		<iframe src="casse.php"></iframe>
	</div>
	
	<div id="screen-shot">
		<div class="titre-rub">VISUALISATION</div>
		<iframe src="screen.php"></iframe>
	</div>
	
	<div id="comment">
		<div class="titre-rub">CHAT</div>
		<iframe src="chat.php"></iframe>
	</div>
	
</div>
		<script>
		
$( "#presentation" ).hide();
$( "#button_info" ).click(function() {
 $( "#presentation" ).toggle("fast");
});
</script>


	</body>
</html>