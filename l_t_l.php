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
	<script src="./Paper/theme-bootstrap.js"></script>
	<script src="./Paper/mode-javascript.js"></script>

</head>

<body  >

<?php

try
{
	$bdd = new PDO('mysql:host=localhost;dbname=PaperFont', 'root', 'root');
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}


$reponse = $bdd->query('SELECT * FROM archive');

while ($donnees = $reponse->fetch())
{
	$donnees['codejs_canvas'] = $donnees['codejs'];
	$donnees['codejs_canvas'] = str_replace("</br>","\r\n",$donnees['codejs_canvas']);
?>
	
	<script type="text/paperscript" canvas="canvas <?php echo $donnees['id']; ?>">
					var path = new Path();
path.fillColor = 'black';
path.add(new Point(25, 300));
path.add(new Point(90, 30)); 
path.add(new Point(117, 30)); 
path.add(new Point(55, 300)); 

var path1 = new Path();
path1.fillColor = 'black';
path1.add(new Point(155, 300)); 
path1.add(new Point(93, 30)); 
path1.add(new Point(120, 30)); 
path1.add(new Point(185, 300)); 

var traver = new Path();
traver.fillColor = 'black';
traver.add(new Point(60, 220)); 
traver.add(new Point(60, 190)); 
traver.add(new Point(150, 190)); 
traver.add(new Point(150, 220)); 

				</script>
				<div class="canvas_archive">
					<canvas  id="canvas <?php echo $donnees['id']; ?>" ></canvas>
				</div>

	
<?php

}


$reponse->closeCursor();

?>
</body></html>