<?php
	$rootdir =getcwd();
	$imagedir = "./images";

	if ( ! is_dir($rootdir) )
	{
		echo "Unable to get access to $rootdir, contact your web administrator.";
		die();
	}
	
	$currentdir = $_GET['path'];
	
	// on tronque le debut si c'est un /
	if ( substr($currentdir,0,1) == "/" )
	{
		$currentdir = substr($currentdir,1,strlen($currentdir) - 1);
	}
	
	// si la fin de $currentdir = .. alors on retourne a la racine de ce dossier
	if ( substr($currentdir, strlen($currentdir) - 2, 2) == ".." )
	{
		// strip last /..
		$currentdir = substr($currentdir, 0, strlen($currentdir) - 3);
		
		// strip last /dirname
		$currentdir = substr($currentdir, 0, strrpos($currentdir,"/"));
	}
	
	// si la fin de $currentdir = /. alors on retourne a la racine de ce dossier
	if ( substr($currentdir, strlen($currentdir) - 2, 2) == "/." )
	{
		$currentdir = substr($currentdir, 0,strlen($currentdir) - 2);
	}
	
	// evite tout probleme de securite MAISempeche les nom de rep avec .. dedans
	$currentdir = str_replace("..", "", $currentdir);

	// on traite les actions spÃ©ciales
	$action = $_GET['action'];
	switch($action)
	{
		case "mkdir":
			if ( isset($_GET['arg'] ) )
			{
				// evite tout probleme de securite MAIS empeche les nom de rep avec .. dedans
				$mkdir = str_replace("..", "", $_GET['arg']);
				umask (0);
				mkdir($rootdir . "/" . $currentdir . "/" . $mkdir);			
			}
			else
			{
				$affiche_creer_formulaire = true;

			}
			break;
		
		case "rm";
			if ( isset($_GET['confirmation'] ) )
			{
				// evite tout probleme de securite MAIS empeche les nom de rep avec .. dedans
				$rm = str_replace("..", "", $_GET['path']);
				
				if ( isset($_GET['file']) )
					$rm = $rm . "/" . str_replace("..","", $_GET['file']) ;
					
				system("rm -r '". $rootdir . "/" . $rm . "'") ;
			}
			else
			{
				if( ! isset($_GET['infirmation']))
					$affiche_supprimer_formulaire=true;
				else
					$affiche_supprimer_formulaire=false;

			}
			// si l'on ne supprimait pas un fichier (donc un rep, on doit retourner a la racine quelque soit la reponse
			if ( ( isset($_GET['confirmation']) || isset($_GET['infirmation']) ) && ! isset($_GET['file']) )
				// strip last /dirname pour retourner au parent du rep en cours
				$currentdir = substr($currentdir, 0, strrpos($currentdir,"/"));					
			break;
			
		case "deconnection":
		
			break;
			
		case "upload":
			if ( ! isset($_FILES['uploadFile']) )
			$affiche_upload_formulaire = true;
			break;

	}
	
	// l'upload se fait en post (l'action)
	if (isset($_POST['action']) && $_POST['action'] == "upload")
	{
		if ( isset($_FILES['uploadFile']) )
		{
			$file_name = $_FILES['uploadFile']['name'];
			
			// strip file_name of slashes
			$file_name = stripslashes($file_name);
			if ($_POST['date']) 
			{
				$file_name = date("Y-m-d-H\hi-") . $file_name;
			}
			
			$uploaddir = $rootdir . "/" .  str_replace("..","",urldecode($_POST['path']));
			
			$file_name = $uploaddir . "/" . str_replace("'","",$file_name);
			$copy = copy($_FILES['uploadFile']['tmp_name'],$file_name);
			// check if successfully copied
			if( ! $copy)
			{
			 	echo basename($file_name) . " | <b>Impossible d'uploader</b>!<br>";
			}				
		}
	}
?>

<html>
<head>
<title>
	<title></></title>
	<link rel="stylesheet" type="text/css" href="ressources/css/bootstrap.css">
</title>
</head>
<body>
<h1></h1><hr>
	<div id="head">
		<div class="navbar">
			<div class="container-fluid">
				<ul class="nav navbar-nav">
					<li>
						<a href="explorer.php">Explorer</a>
					</li>
					<li>
						<a href="#" id="myBtn">Affichage</a>
					</li>
					<li>
						<a href="#">OS</a>
					</li>
					<li>
						<a href="#" id="myBtn">Aide?</a>
					</li>
				</ul>
				<div id="profil">
					<img class="img-circle border-effect1" src="ressources/images/a.jpg" width="50px"/>
				</div>
				<div id="hdd">
					<?php
						require 'classe/fluxdossier.php';
    	    			$partition = getcwd();
    	    			$space_total = disk_total_space($partition);
    	    			$space_free = disk_free_space($partition);
    	    			$gb_total= $space_total/1024**3;
    	    			$gb_free= $space_free/1024**3;
    	    			$print_total = ceil($gb_total);
    	    			$print_free = ceil($gb_free);
    	    			$free = $print_total-$print_free;
    	    			echo "<h4><img class='img-circle border-effect1' src='ressources/images/ordi.jpg' width='50px'/> <b> $free/$print_total Go</b></h4>";
    	    			require_once 'classe/fluxdossier.php';
    	    			$a = new flux;
    	    			if (!isset($_POST['color']) OR empty($_POST['color'])) {
    	    				$a->theme_nav('$');
    	    			}else{
    	    				$a->theme_nav($_POST['color']);
    	    			}
					?>
				</div>
			</div>
		</div>
	</div>
<BIG><BIG>Explorateur - /<?php echo $currentdir; ?></BIG></BIG>

<table border=1 width=100%>
<tr><td colspan=2>

<!-- Toolbar -->
<table width="100%">
<tr><td>
<a href="<? echo $_self . "?path=";  ?>">Racine</a> | 
<a href="<? echo $_self . "?action=mkdir&path=" . urlencode($currentdir); ?>">Creer Repertoire</a> |  
<a href="<? echo $_self . "?action=upload&path=" . urlencode($currentdir); ?>">Uploader</a>
</td><td align=right>
Deconnecter
</td></tr>
</table>
<?php

if ( $affiche_creer_formulaire )
{
	// affichage du formulaire pour creer un repertoire
	?>
	<hr>
	<form method="get">
	<input type="hidden" name="path" value="<? echo $currentdir ?>" />
	<input type="hidden" name="action" value="mkdir" />
	Nom du repertoire : <input type="text" name="arg" value=""/>
	<input type="submit" value="Creer" />
	</form>
	<?php
}

if ( $affiche_supprimer_formulaire ==true)
{
	// affichage du formulaire pour supprimer un repertoire
	?>
	<hr>
	<form method="get">
	<input type="hidden" name="path" value="<? echo $currentdir ?>" />
	<?
	if ( isset($_GET['file']) )
		echo "<input type=\"hidden\" name=\"file\" value=\"" . $_GET['file'] . "\" />";
	?>
	<input type="hidden" name="action" value="rm" />
	Supprimer <? echo $currentdir . "/"; if (isset($_GET['file'])) echo $_GET['file']; ?> ? 
	<input type="submit" name="confirmation" value="Oui" />
	<input type="submit" name="infirmation" value="Non" />
	</form>
	<?php
}else{
	echo '';
}

if ( $affiche_upload_formulaire )
{
	?>
	<hr>
	<form enctype="multipart/form-data" method="post">
	Fichier : <input name="uploadFile" type="file" id="uploadFile" />
	<input type="hidden" name="action" value="upload" />
	<input type="hidden" name="path" value="<? echo urlencode($currentdir);?>">
	<input type="submit" name="submit" value="Uploader" />
	&nbsp;&nbsp;<input type="checkbox" name="date" CHECKED/>Dater le fichier
	</form>
	<?php
}

?>

</td></tr>
<tr>
<td valign=top width=20%>
	<!-- Colonne pour les repertoires -->
	
	<table border=0 width=100% height=100%>
	<tr><td colspan=3>
		<table border=1 width=100%>
		<tr>
		<td width=100%><b>Repertoires</b></td>
		</tr>
		</table>
	</td></tr>	
	<?php
		$directory = opendir( $rootdir . "/" . $currentdir );
		while( $dir = readdir($directory) )	
		{
			if (is_dir( $rootdir . "/" . $currentdir . "/" . $dir) && $dir != "." )
			{
				// on affiche pas le ..  quand on est a la racine
				if( $currentdir == "" && $dir != ".." || $currentdir != "")
				{
					echo "<tr><td width=30 height=30>";
					echo "<img src='ressources/images/dossier.jpeg' width='110%'> ";
					echo "</td><td width=80%>";
					echo "<a href=\"?path=" . urlencode($currentdir) . "/" . urlencode($dir) . "\"><button style ='width:50%;'>" . $dir . "</button></a>";
					echo "</td><td align=right>&nbsp;";
					if ( $dir != ".." )
						echo "<a href=\"" . "?action=rm&path=" . urlencode($currentdir) . "/" . urlencode($dir) . "\">X</a>";
					echo "</td></tr>\n";
				}
			}
		}
		closedir($directory);
	?>
	</table>
</td>
<td valign=top width=80%>
	<!-- Colonne pour les fichiers -->

	<table border=0 width=100% height=100%>
	<tr><td colspan=3>
		<table border=1 width=100%>
		<tr>
		<td width=75%><b>Noms</b></td>
		<td width=25% align=right><b>Taille</b></td>
		</tr>
		</table>
	</td></tr>
	<?php

		$directory = opendir( $rootdir . "/" . $currentdir );
		$foundone = false;
		while( $file = readdir($directory) )	
		{
			if (is_file($rootdir . "/" . $currentdir . "/" . $file) )
			{
				$foundone = true;
				echo "<tr><td width=30 height=35>";
			
				// selon l'extension du fichier
				$ext = strtolower(substr($file,strrpos($file,".") + 1,strlen($file) - strrpos($file,".")));
				switch($ext)
				{
					case "gif":
					case "jpg":
					case "png":
						echo "<img width=30 height=28 src=\"miniature.php?gd=2&maxw=30&src=" . $rootdir . "/" . urlencode($currentdir) . "/" . urlencode($file) . "\"/>";
						break;
					default:
						if ( is_file( $imagedir . "/" . $ext . ".gif" ) )
							echo "<img width=30 height=28 src=\"miniature.php?gd=2&maxw=30&src=" . $imagedir . "/" . $ext . ".gif" . "\"/>";
						else
							echo strtoupper($ext);
						break;
				}
				echo "</td><td>";
				echo "<a href=\"" . $rootdir . "/" . $currentdir . "/" . $file . "\">" . $file . "</a>";
				echo "</td><td align=right width=15%>";
				echo filesize($rootdir . "/" . $currentdir . "/" . $file );
				echo "&nbsp;&nbsp;<a href=\"" . "?action=rm&path=" . urlencode($currentdir) . "&file=" . urlencode($file) . "\">X</a>";
				echo "</td></tr>\n";
			}
		}
		closedir($directory);	
		if ( ! $foundone)
		{
			echo "<tr><td colspan=3 align=center><b>Aucun fichier !</b></td></tr>";
		}
	?>
		
	</table>

</td>
</tr>
</table>
</body>
</html>

