<html>
	<head>
       <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link href="Paper/style.css" type="text/css" rel="stylesheet" media="all"/>
		<link href="Paper/font/font.css" type="text/css" rel="stylesheet" media="all"/>

	</head>
	<body>


<div class="cont-casse" style="margin-top:20px">
<?php

try
{
	$bdd = new PDO('mysql:host=localhost;dbname=PaperFont', 'root', 'root');
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}


$reponse = $bdd->query('SELECT lettre FROM archive ORDER BY lettre');

$don="";

while ($donnees = $reponse->fetch())
{
if ($donnees['lettre'] !=$don) {
?>
		<div class="cadra"><a href="paperfont-letter.php?lettre=<?php echo $donnees['lettre']; ?>"><?php echo $donnees['lettre']; ?></a></div>


<?php
$don=$donnees['lettre'];
}}

$reponse->closeCursor();

?>

					</div>
					
						</body>
</html>