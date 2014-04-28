<html>
	<head>
		<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="UTF-8">
<title>FontEditor</title>
<link rel="stylesheet" href="Paper/style.css" type="text/css">
<link href="Paper/font/font.css" type="text/css" rel="stylesheet" media="all"/>
<script src="./Paper/jquery.js"></script>

	</head>
	<body>

<div class="button" id="toggle_news" style="position:fixed;" >WRITE</div>

<div id="write_notes">

<form id="form_news" action="notes.php" method="post"  enctype="multipart/form-data" >
	<h2 class="form-titre" >Name:</h2>
	<input style="float: left; width:50px;" type="text" name="nom"/>
	<h2 class="form-titre" >Title:</h2>
	<input style="float: left; width:180px;	font-family:'gentium-r';" type="text" name="titre"/>
	
	</br>
	</br>
	<h2>Text:</h2>
	<textarea type="text" name="texte"  ></textarea>
	
	<input id="toggle_news" type="submit" value="send" />

</form>

</div>
<div class="cont-news">


</div>
<?php
$date= date("F j, Y, g:i a");
$nom= $_POST['nom'];
$titre= $_POST['titre'];
$texte= $_POST['texte'];

$texte = str_replace("\r\n","</br>",$texte);




try
{
	$bdd = new PDO('mysql:host=localhost;dbname=PaperFont', 'root', 'root');
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}


$req = $bdd->prepare('INSERT INTO notes( date, nom, titre, texte) VALUES(:date, :nom, :titre, :texte)');
$req->execute(array(
	'date' => $date,
	'nom' => $nom,
	'titre' => $titre,
	'texte' => $texte,
	));

$reponse = $bdd->query('SELECT * FROM notes ORDER BY id DESC');

while ($donnees = $reponse->fetch())
{
?>
		<h4 style="margin-top:10px;font-size:12px;"><?php echo $donnees['date']; ?> / <?php echo $donnees['nom']; ?></h4></br>
		<h1><?php echo $donnees['titre']; ?></br></h1>
		<h2><?php echo $donnees['texte']; ?></br></h2>

<?php
}

$reponse->closeCursor();

?>


					</div>
		<script>
		
$( "#write_notes" ).hide();
$( "#toggle_news" ).click(function() {
 $( "#write_notes" ).toggle("fast");
});
</script>
					
						</body>
</html>