<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
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
		
		<!--Editeur text Js-->
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
	
	
			<!--Boutons des outils du canvas-->
			<div class="tools">
				<a class="button tool icon-cursor active" target="_blank">SELECT</a>
				<a class="button tool icon-zoom-in" target="_blank">ZOOM</a>
				<a class="button canvas-export-svg icon-download" target="_blank" title="Export SVG">SVG</a>
				<a class="button canvas-export-json icon-download" target="_blank" title="Export JSON">JSON</a>
				<a class="button canvas-clear icon-trash" title="Clear Canvas">TRASH</a>
				<a class="button btn-grid" title="Clear Canvas">GRID</a>
			</div>
			</br>
			</br>
			<canvas id="canvas"></canvas>
			
			<!--Importation de la grille html-->
			<iframe id="grid" src="grille.html"></iframe>
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
		$( ".btn-grid" ).click(function() {
		$( "#grid" ).toggle('fast');
		});
	</script>
</body>
</html>