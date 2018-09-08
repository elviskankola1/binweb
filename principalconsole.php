<?php session_start();?>
<!DOCTYPE html>
<html>
<head>
	<title>BINWEB-CONSOLE</title>
	<link rel="stylesheet" type="text/css" href="ressources/css/bootstrap.css">
</head>
<style type="text/css">
	iframe{
		background-image: url('back.jpg');
		background-position: bottom;
		background-repeat: no-repeat;
		background-size: 10000%;
		margin-top: 1.4%;
	}
	#head{
		position:  absolute;
		width: 100%;
		margin-top: -3%;
		height: 9%;
		background-color: rgb(0, 100, 100);
	}
	a{
		text-decoration: none;
		color: white;
		font-family: 'Noto Sans Mono CJK TC Bold';
	}
	#profil{
		position:  absolute;
		margin-top: 0.3%;
		left: 89%;
		width: 10%;
		height: 100%;
		color: white;
		text-align: center;
	}
	#profiltime{
		position:  absolute;
		margin-top: 0.3%;
		left: 84%;
		width: 10%;
		height: 100%;
		color: white;
		text-align: center;
	}
	#hdd{
		position:  absolute;
		margin-top: -0.5%;
		left: 60%;
		width: 20%;
		height: 100%;
		color: white;
		text-align: center;
		font-family:'Noto Sans Mono CJK TC Bold';
	}
	.modal {
    display: none; 
    position: fixed;
    z-index: 1; 
    padding-top: 100px; 
    left: 0;
    top: 0;
    width: 100%; 
    height: 100%; 
    overflow: auto; 
    background-color: rgb(0,0,0);
    background-color: rgba(0,0,0,0.4); 
}

.modal-content {
    background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 50%;
}

.close {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}
</style>
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
