<html>
	<head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link href="Paper/style.css" type="text/css" rel="stylesheet" media="all"/>
		<link href="Paper/font/font.css" type="text/css" rel="stylesheet" media="all"/>

	</head>
		<body>


<div id="write_chat">

<form id="form_news" action="chat.php" method="post"  enctype="multipart/form-data" >
	<h2 class="form-titre" >Name:</h2>
	<input style="float: left; width:50px;" type="text" name="nom"/>
		<input id="toggle_news" type="submit" value="send" />

	</br></br>
	<h2 style="float:left;">Text:</h2>
	<textarea type="text" name="texte"  ></textarea>
	</br></br></br>

</form>

</div>
<div class="cont-chat">

<?php
$date= date("F j, Y, g:i a");
$nom= $_POST['nom'];
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


$req = $bdd->prepare('INSERT INTO chat( date, nom, texte) VALUES(:date,:nom, :texte)');
$req->execute(array(
	'date' => $date,
	'nom' => $nom,
	'texte' => $texte,
	));

$reponse = $bdd->query('SELECT * FROM chat ORDER BY id DESC');

while ($donnees = $reponse->fetch())
{
?>
		<h4 style="margin-top:30px; margin-bottom:-10px;font-size:12px;"><?php echo $donnees['date']; ?> / <?php echo $donnees['nom']; ?></h4></br>
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