<?php
	$rootdir =getcwd();
	$imagedir = "./images";

	if ( ! is_dir($rootdir) )
	{
		echo "Unable to get access to $rootdir, contact your web administrator.";
		die();
	}
	
	$currentdir = $_GET['path'];

	if ( substr($currentdir,0,1) == "/" )
	{
		$currentdir = substr($currentdir,1,strlen($currentdir) - 1);
	}
	
	if ( substr($currentdir, strlen($currentdir) - 2, 2) == ".." )
	{
		// strip last /..
		$currentdir = substr($currentdir, 0, strlen($currentdir) - 3);
		
		$currentdir = substr($currentdir, 0, strrpos($currentdir,"/"));
	}
	
	if ( substr($currentdir, strlen($currentdir) - 2, 2) == "/." )
	{
		$currentdir = substr($currentdir, 0,strlen($currentdir) - 2);
	}
	
	$currentdir = str_replace("..", "", $currentdir);
	$action = $_GET['action'];
	switch($action)
	{
		case "mkdir":
			if ( isset($_GET['arg'] ) )
			{
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
			if ( ( isset($_GET['confirmation']) || isset($_GET['infirmation']) ) && ! isset($_GET['file']) )
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
	<title></></title>
	<link rel="stylesheet" type="text/css" href="ressources/css/bootstrap.css">
</title>
</head>
<body style=" background-image: url(ressources/images/explore_back.jpeg); background-repeat: no-repeat; background-size:50%; background-position:top;">
	<div id="head">
		<div class="navbar">
			<div class="container-fluid">
				<ul class="nav navbar-nav">
					<li>
						<a href="principalconsole.php">Console</a>
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
	</div><br><br><br><br><br><br><br><br><br>
<h3>U-Explorer /<?php echo $currentdir; ?></h3>

<table border=1 width=100% >
<tr><td colspan=2>
<table width="100%" style="color:black;">
<tr><td>
<a href="<? echo $rootdir?>" style="color:black;">Racine</a> | 
<a href="<? echo $_self . "?action=mkdir&path=" . urlencode($currentdir); ?>" style="color:black;">Creer Repertoire</a> |  
<a href="<? echo $_self . "?action=upload&path=" . urlencode($currentdir); ?>" style="color:black;">Modifier</a>
</td></tr>
</table>
</td></tr>
<tr>
<td valign=top width=20%>
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
				if( $currentdir == "" && $dir != ".." || $currentdir != "")
				{
					echo "<tr><td width=30 height=30>";
					echo "<img src='ressources/images/dossier.jpeg' width='110%'> ";
					echo "</td><td width=80%>";
					echo "<a href=\"?path=" . urlencode($currentdir) . "/" . urlencode($dir) . "\"><h3 style ='width:40%;color:black;'>" . $dir . "</h3></a>";
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
	<table border=0 width=100% height=100%>
	<tr><td colspan=3>
		<table border=1 width=100%>
		<tr>
		<td width=75%><b>Noms</b></td>
		<td width=25% align=left><b>Taille</b></td>
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
				$ext = strtolower(substr($file,strrpos($file,".") + 1,strlen($file) - strrpos($file,".")));
				echo "</td><td>";
				echo "<br>";
				echo "<img src='ressources/images/fichier.png' width='2.5%'> ";
				echo "      "."<a href=\"" . $rootdir . "/" . $currentdir . "/" . $file . "\" style='color:black'>" . $file . "</a>";
				echo "</td><td align=right width=15%>";
				echo "<h5 style='text-align:center;'>".filesize($rootdir . "/" . $currentdir . "/" . $file )." octets</h5>";
				//echo "&nbsp;&nbsp;<h5><a href=\"" . "?action=rm&path=" . urlencode($currentdir) . "&file=" . urlencode($file) . "\" style='color:black; text-decoration:none;'>Supp</a></h5>";
				echo "</td></tr>";
				
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
<script>
var modal = document.getElementById('myModal');
var btn = document.getElementById("myBtn");
var span = document.getElementsByClassName("close")[0];

btn.onclick = function() {
    modal.style.display = "block";
}
span.onclick = function() {
    modal.style.display = "none";
}
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

</script>
<script type="text/javascript">
	var modal = document.getElementById('myModal2');
var btn = document.getElementById("myBtn");
var span = document.getElementsByClassName("close")[0];

btn.onclick = function() {
    modal.style.display = "block";
}
span.onclick = function() {
    modal.style.display = "none";
}
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</html>

