<!DOCTYPE html>
<html>
<head>
	<title>BEAN</title>
	<link rel="stylesheet" type="text/css" href="bootstrap.css"  media="all">
</head>
<body>
	
	<div id="back">
		<div id="input">
			<form  method='post'>
				<div class="col-md-2" style="text-align: left;">
					<?php
						$cmduser= exec('hostname');
                    				echo "<h4 style=' font-family:Noto Sans Mono CJK TC Bold'><b style='color:red; margin-top:0.5%;'>$cmduser</b></h4>";
                			?>
           		 	</div>
            			<div class="col-md-10" style="left: 2%;">
                 			<input type='text' name='cmd' id="inp" placeholder=">>>"> 
            			</div>
        		</form>
			<?php

			    require_once 'classe/fluxdossier.php';
			    $a = new flux;
			    $courant= getcwd();
			    $compteur =0;
			    $tabextensionsfile = array('jpg','png','jpeg','JPG','PNG','JPEG' );
			    $cmd=strip_tags($_POST['cmd']);

			    if ($cmd != null AND $a->command_your_os($cmd) AND !empty($cmd) AND isset($cmd)) {

				$boldcmd= $a->command_your_os($cmd);

				if (!is_file($boldcmd)) {

					echo "<h3 style='left:5%;'><b></b></h3>"."<b><h4 style='font-family:Noto Sans Mono CJK TC Bold'>$boldcmd</h4></b><span></span>";
				}
    			
			   }elseif(is_file($cmd)) {
				    
				$file= $courant.'/'.$cmd;
				    
				if (!file_exists($file)){

					echo "<h3 style='font-family:Noto Sans Mono CJK TC Bold'><b> File no find...<img src='stop.gif' width='2%'></h3>";

				}elseif(file_exists($file)){

					$contenu = file($file);
					$extension = substr(strrchr($file,'.'),1);
					if (in_array($extension, $tabextensionsfile)) {
					    ?>
					    <img src ="<?php echo $cmd;?>" width ='50%'/>
					    <?Php
                        		}else{

                            			for($i=0;$contenu[$i];$i++){

                                    			echo "<h3 style='left:5%;'><b></b></h3>"."<b><h4 style='font-family:Noto Sans Mono CJK TC Bold'>$contenu[$i]</h4></b>";
                                    			$compteur++;
                                
                            			}
                            
                        		}
					$format = 'j/m/Y';
					$date_creation = date($format,filectime($file));
					$date_modification = date($format,filemtime($file));
					$date_dernier_acces = date($format,fileatime($file));
					$taillefile = $a->size_file($file);
					$identifiant_hdd = fileinode($file);
					$type = filetype($file);
					if ($compteur == 0) {
						echo "<div style='position:absolute; margin-bottom: 400%;left:75%; color: white;'>
						<img src ='text.png' width ='20%'/><b><h4 style='font-family:Noto Sans Mono CJK TC Bold'>Vous avez aucun contenu dans $file</h4></b>
						<ul style='font-family:Noto Sans Mono CJK TC Bold'>
						<li>date creation : le $date_creation</li>
						<li>date modifictaion : le $date_modification</li>
						<li>date d'acces: le $date_dernier_acces</li>
						<li>Taille : $taillefile Octets</li>
						<li>Identifiant : $identifiant_hdd</li>
						</ul>
						</div>";

					}else{

						echo"<div style='position:absolute; margin-bottom: 10%;left:75%; color: white;'>
						<img src ='text.png' width ='20%'/><b><h4 style='font-family:Noto Sans Mono CJK TC Bold'>$compteur lignes dans $file</h4></b>
						<ul style='font-family:Noto Sans Mono CJK TC Bold'>
						<li>date creation : le $date_creation</li>
						<li>date modifictaion : le $date_modification</li>
						<li>date d'acces: le $date_dernier_acces</li>
						<li>Taille : $taillefile Octets</li>
						<li>Identifiant : $identifiant_hdd</li>
						</ul>
						</div>";

					}
    			
    				}elseif( $cmd != null AND !$a->command_your_os($cmd)) {
					
    					echo "<h3 style='font-family:Noto Sans Mono CJK TC Bold'><b> Votre commande est introuvable...<img src='ressources/images/stop.gif' width='2%'></h3>";
    				
				}elseif(empty($cmd) OR !isset($cmd)){
                		
					echo false;
            		}
    		?>
	</div>
	</div>
</body>
</html>


