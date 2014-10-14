PaperFont
=========
Licence GNU/GPL. Antoine Gelgon.

PaperFont est une interface servant à développer des formes typographique à plusieurs.
Ces formes sont éditées en javascript et utilisent la librairie (http://paperjs.org/) développé par Jürg Lehni & Jonathan Puckey. Cette interface a également pour but de rendre visible l'évolution du projet, par un système de versionning et de changelog. Un chat y est intégré pour faciliter la communication entre les contributeurs. 
			
PaperFont est développer par Antoine Gelgon.

Les typographies utilisé Gentium (http://scripts.sil.org/cms/scripts/page.php?site_id=nrsi&id=gentium_download)
                        Anonymous (http://www.marksimonson.com/fonts/view/anonymous-pro)
2014


![Demo](https://raw.githubusercontent.com/Antoine-Gelgon/PaperFont/V1/Screen-interface/PaperFont.png)


##LES MOYENS

PaperFont est une articulation entre plusieurs langages, librairies et outils.

HTML/CSS: Permet l'affichage de la structure de l'interface.

PHP: Rend l'interface dynamique en mettant en relation l'interface
avec les données serveur de la base de données.

PhpMyAdmin: système de gestion de base de données MySQL réalisée
en PHP. Permet de trier et organiser les données envoyées par
l'utilisateur au serveur.

Javascript: Rend les pages interactives, il est également employé
pour dessiner les formes typographiques, par le biais d'une
bibliothèque javascript, PaperJs.

PaperJs: Est une bibliothèque javascript pour générer des formes
vectorielles. Elle est ici utilisée pour générer les formes
typographiques dans un canvas HTML.

Ace.Js: Est un éditeur de code en javascript, ici il permet
à l'utilisateur d'entrer directement le code javascript dans
l'interface pour générer les formes typographiques.


##L'ORGANISATION DE L'INTERFACE ET LES FONCTIONNALITÉS

Pour avoir accès rapidement à tout les fonctionnalités, le tout se localise sur une seule
et même page web. Chaque casse correspond à une fonction bien précise, qu'elle soit,
de création, de visualisation, d'enregistrement, de communication ou d'archivage.

Les fonctions de créations de formes typographiques se trouvent dans la partie supérieure de
l'interface et sont divisées en trois

####CODE JS
C'est ici que le code javascript est écrit. Il y a plusieurs boutons pour actionner le code
de différentes manières. Le bouton Play envoie le code javascript dans la partie voisine, dans
le canvas. Le bouton DL JS permet de télécharger le code écrit directement sur l'ordinateur
de l'utilisateur.

####CANVAS
C'est ici qu'il y a la visualisation du code en cours de réalisation, une fois que celui-ci est actionné dans la partie Code Js. Il y a également plusieurs boutons dans cette partie. Le bouton "ZOOM", permet d'agrandir la visualisation des formes. Le bouton "SVG" permet de télécharger la lettre au format SVG sur l'ordinateur de l'utilisateur. Le bouton "JSON" permet de télécharger la lettre au format Json sur l'ordinateur de l'utilisateur. Le bouton "Grid" permet de montrer ou de cacher la grille typographique de la lettre.

####SAVE
Dans cette partie, on a la possibilité d'enregistrer l'avancement de la lettre. On y rentre le nom
de l'utilisateur, la lettre dessiné, la version de la lettre. Les deux autres champs restant servent a
entrer et enregistrer le code javascript ainsi de le commenter.

####Les fonctions d'archivage et d'évolution du projet se trouvent dans la partie centrale. Par un système de log et de casse.

####LOG
Ici on peut commenter l'évolution du projet général, indiquer des problèmes,
mettre des références, etc.

####CASSE
Ici se trouve l'archivage répertorié des lettres. On peut voir tout ce qui a été enregistré. C'est dans cette partie que l'on peut constater l'évolution des lettres et du projet. Sur chaque version de la lettre enregistré, on peut y voir son auteur, la date, une visualisation de la lettre ainsi que les commentaires de l'auteur.

####VISUALISATION
Ici on peut voir les formes typographiques les une en rapport au autre.

####CHAT
Ce chat permet la communication entre les différents contributeurs du projet.

##STRUCTURE DE LA BASE DE DONNÉES

Structure de la table `archive`

CREATE TABLE `archive` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`lettre` varchar(1) CHARACTER SET latin1 NOT NULL,
`date` datetime NOT NULL,
`nom` varchar(255) CHARACTER SET latin1 NOT NULL,
`version` varchar(255) CHARACTER SET latin1 NOT NULL,
`codejs` text CHARACTER SET latin1 NOT NULL,
`log` text CHARACTER SET latin1 NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin7 COLLATE=latin7_general_cs AUTO_INCREMENT=241 ;

Structure de la table `chat`

CREATE TABLE `chat` (
`id` int(100) NOT NULL AUTO_INCREMENT,
`date` varchar(100) COLLATE latin7_general_cs NOT NULL,
`nom` varchar(20) CHARACTER SET latin1 NOT NULL,
`texte` text CHARACTER SET latin1 NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin7 COLLATE=latin7_general_cs AUTO_INCREMENT=6 ;

Structure de la table `notes`

CREATE TABLE `notes` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`date` varchar(100) NOT NULL,
`nom` varchar(40) CHARACTER SET latin1 NOT NULL,
`titre` varchar(100) CHARACTER SET latin1 NOT NULL,
`texte` text CHARACTER SET latin1 NOT NULL,
PRIMARY KEY (`id`),
KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;
