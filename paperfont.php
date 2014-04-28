<!DOCTYPE html>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="UTF-8">
<title>FontEditor</title>
<link rel="stylesheet" href="Paper/style.css" type="text/css">
<link href="Paper/font/font.css" type="text/css" rel="stylesheet" media="all"/>


<script src="./Paper/paper.js"></script><style type="text/css"></style>
<script src="./Paper/jquery.js"></script>
<script src="./Paper/ace.js"></script>
<script src="./Paper/rawinflate.js"></script>
<script src="./Paper/rawdeflate.js"></script>
<script src="./Paper/editor.js"></script>
<script src="./Paper/theme-bootstrap.js"></script><script src="./Paper/mode-javascript.js"></script>

</head>

<body>

<div class="paperscript">
	<!--code source-->
	    <div id="code">
		<div class="titre-rub">CODE JS</div>

		<div  class="source" >
			<a class="button script-run icon-play" title="PLAY">PLAY</a>
			<a class="button script-download icon-download" target="_blank" title="Download">DL JS</a>
			</br>
			</br>
			<div class="editor">
	
			</div>
		</div>
	</div>
	
	<!--canvas-->
	<div id="exe">
		<div class="titre-rub">CANVAS</div>

		<div class="tools">
			<a class="button tool icon-cursor active" target="_blank">SELECT</a>
			<a class="button tool icon-zoom-in" target="_blank">ZOOM</a>
			<a class="button canvas-export-svg icon-download" target="_blank" title="Export SVG">SVG</a>
			<a class="button canvas-export-json icon-download" target="_blank" title="Export JSON">JSON</a>
			<a class="button canvas-clear icon-trash" title="Clear Canvas">TRASH</a>
			<a class="button btn-grille" title="Clear Canvas">GRID</a>
		</div>
		</br>
		</br>
		
		
		
		<canvas id="canvas">x</canvas>
		<iframe id="grille" src="grille.html"></iframe>
	</div>
	
	<!--sauver-->
	<div id="sauver">
		<div class="titre-rub">SAVE</div>
<form action="paperfont.php" method="post"  enctype="multipart/form-data">
	
	<h2 class="form-titre" >Name:</h2>
	<input style="float: left; width:40px;" type="text" name="nom" />

<h2 class="form-titre" >Letter:</h2>
<input style="float: left; width:20px;" type="text" name="lettre" />
 			
<h2 class="form-titre" >Version:</h2>
<input style="float: left; width:20px;" type="text" name="version" />
				
</br></br>
				
<h2>Code:</h2>
<textarea style="font-family:'anonymous';" id="code-form" type="text" name="codejs" ></textarea>

<h2>Comment:</h2>
<textarea id="log-form" type="text" name="log" ></textarea>

<input type="submit" value="send" />

</form>



	</div>

</div> 

<?php
$date= date("F j, Y, g:i a");
$lettre =$_POST['lettre'];
$nom= $_POST['nom'];
$version= $_POST['version'];
$codejs= $_POST['codejs'];
$log= $_POST['log'];

$log = str_replace("\r\n","</br>",$log);
$codejs = str_replace("\r\n","</br>",$codejs);


try
{
	$bdd = new PDO('mysql:host=localhost;dbname=PaperFont', 'root', 'root');
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}




$req = $bdd->prepare('INSERT INTO archive(date, lettre, nom, version, codejs, log) VALUES(:date, :lettre, :nom, :version, :codejs, :log)');
$req->execute(array(
	'date' => $date,
	'lettre' => $lettre,
	'nom' => $nom,
	'version' => $version,
	'codejs' => $codejs,
	'log' => $log,
	));

$reponse = $bdd->query('SELECT * FROM archive WHERE lettre=\'N\' ORDER BY id DESC');

while ($donnees = $reponse->fetch())
{
?>


<?php
}

$reponse->closeCursor();

?>


<script>
$( ".btn-grille" ).click(function() {
$( "#grille" ).toggle('fast');
});
</script>
</body></html>