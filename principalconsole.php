<?php session_start();?>
<!DOCTYPE html>
<html>
<head>
	<title>BINWEB-CONSOLE</title>
	<link rel="stylesheet" type="text/css" href="bootstrap.css">
</head>
<body>
<h1></h1><hr>
	<div id="head">
		<div class="navbar">
			<div class="container-fluid">
				<ul class="nav navbar-nav">
					<li>
						<a href="#">Fichier</a>
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
					$partition = getcwd();
					$space_total = disk_total_space($partition);
					$space_free = disk_free_space($partition);
					$gb_total= $space_total/1024**3;
					$gb_free= $space_free/1024**3;
					$print_total = ceil($gb_total);
					$print_free = ceil($gb_free);
					$free = $print_total-$print_free;
					echo "<h4><img class='img-circle border-effect1' src='ressources/images/ordi.jpg' width='50px'/> <b> $free/$print_total Go</b></h4>";
					require_once 'fluxdossier.php';
					$a = new flux;
					if (!isset($_POST['choice']) OR empty($_POST['choice'])) {
						$a->theme_nav('$');
					}else{
						$a->theme_nav($_POST['choice']);
					}
				?>
				</div>
			</div>
		</div>
	</div>
	<iframe src="logicalconsole.php" name="iframe_a"  width="100%" height="500px"></iframe>
	<div id="myModal" class="modal">
  		<div class="modal-content">
    		<span class="close">×</span>
    			<h1>AIDES <b style="color: blue;">?</b></h1>
    			<hr>
    			<p>ICI DE L'AIDE</p>
  		</div>
	</div>
	<div id="myModal2" class="modal">
  		<div class="modal-content">
    		<span class="close">×</span>
    			<h1>AIDES <b style="color: blue;">?</b></h1>
    			<hr>
    			<p>ICI DE L'OPTION</p>
    			<form action="principalconsole.php" method="post">
    				<select name ='choice'>
	    				<option value="#">GREEN</option>
	    				<option value="*">BLUE</option>
	    				<option value="$">DEFAUT</option>
	    				<input type="submit" name="">
    				</select>
    			</form>
  		</div>
	</div>
</body>
<script>
//modal
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

</script>
</html>
