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
<script src="./Paper/theme-bootstrap.js"></script>
<script src="./Paper/mode-javascript.js"></script>

</head>

<body style="text-align:center" >

<?php

try
{
	$bdd = new PDO('mysql:host=localhost;dbname=PaperFont', 'root', 'root');
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}


$reponse = $bdd->query('SELECT id, codejs FROM archive ORDER BY id DESC ');

while ($donnees = $reponse->fetch())
{
	$donnees['codejs_canvas'] = $donnees['codejs'];
	$donnees['codejs_canvas'] = str_replace("</br>","\r\n",$donnees['codejs_canvas']);
?>
	<script type="text/paperscript" canvas="canvas <?php echo $donnees['id']; ?>">
	<?php echo $donnees['codejs_canvas']; ?>
	</script>
	
	<div class="canvas_screen">
		<canvas  id="canvas <?php echo $donnees['id']; ?>" ></canvas>
	</div>
	
<?php

}


$reponse->closeCursor();

?>
</body></html>