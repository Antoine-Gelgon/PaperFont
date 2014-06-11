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

	<?php
		$lettre_get = $_GET ['lettre'];

		if (strlen ($lettre_get) == 1){
	?>

	<div class="header_casse">
		<a class="button" href='wind_casse.php' >BACK</a>
		<div class="button" ><?php echo $lettre_get; ?>
		</div>
	</div>

	<?php

		try
		{
			$bdd = new PDO('mysql:host=localhost;dbname=PaperFont', 'root', 'root');
		}
		catch(Exception $e)
		{
     	   die('Erreur : '.$e->getMessage());
		}
	
		$reponse = $bdd->query('SELECT * FROM archive WHERE lettre=\''.$lettre_get.'\' ORDER BY id DESC');

		while ($donnees = $reponse->fetch())
		{
			$donnees['codejs_canvas'] = $donnees['codejs'];
			$donnees['codejs_canvas'] = str_replace("</br>","\r\n",$donnees['codejs_canvas']);
	?>

			<div class="box_archive" >
				<div class="donne_archive" >
					<h4><?php echo $donnees['date']; ?> / 
		  			<?php echo $donnees['nom']; ?> / 
		 			version:<?php echo $donnees['version']; ?></h4>
				</div>
				</br>
				</br>
	
				<div class="donne_code" >
					<code><?php echo $donnees['codejs']; ?></code>
				</div>
	
				<div class="donne_log" >
					<h3><?php echo $donnees['log']; ?></h3>
				</div>
				
				<script style="background:red; width:300px; height:auto; top:900px; left:400px" type="text/paperscript" canvas="canvas <?php echo $donnees['id']; ?>">
					<?php echo $donnees['codejs_canvas']; ?>
				</script>
				<div class="canvas_archive">
					<canvas  id="canvas <?php echo $donnees['id']; ?>" ></canvas>
				</div>
	
			</div>
	<?php
	}
		$reponse->closeCursor();
	} else {
		echo "mauvais parametre get";
	}
	?>

</body></html>