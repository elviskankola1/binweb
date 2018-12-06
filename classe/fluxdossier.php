
<?php
class Flux{
    
    private $file_now;
    private $comptor =0;
    private $timeout= 15;
    private $session ='';
    private $virus = array('bat','exe');

    /*public function __construct($session){

        $this->setsession($session);
    }*/
    public function getsession(){

        return $this->session;
    }
    public function setsession(string $session){
        $this->session->$session;
    }
    public function set_timeout (int $time){
        
        return $this->timeout = $time;
    }
    public function get_nbre_files_and_delete_bad( string $falder_path){

        if (!file_exists($falder_path) OR !is_file($falder_path) OR is_link($falder_path)) {
            
            return 'existe pas';

        }else{

            $falder_files= dir($falder_path);

            while ($one_file= $falder_files->read()) {

                $file= $this->file_now = $one_file;

                $extension_file = substr(strrchr($file,'.'),1);

                if (in_array($extension_file,$this->virus)) {
                    
                    delete($file);
                }
                
                $this->comptor++;
            }
            $falder_files->close();
            
            $tab_for_result= array('nbre_file'=>$this->comptor,'name_file'=>$file);

            return $tab_for_result;
            
        }
        
    }

    public function size_file( string $file_path){

        $size =filesize($file_path);
        return $size;
    }
    public function space_total_for_your_partition_hdd(string $file_path){

        $space = disk_total_space($file_path);

        return $space;
    }
    public function space_free_for_your_partition_hdd(string $file_path){

        $space = disk_free_space($file_path);

        return $space;
    }
    public function rewrite_permission_file( string $file_path, int $mode){

        $perimission = chmod($file_path,$mode);

        if ($perimission) {
            
            return $perimission;

        }else {
            return FALSE;
        }

    }
    public function command_your_os(string $cmd){

        $command= shell_exec($cmd);
        $result=utf8_decode($command);

        return nl2br($result);
    }
    public function create_file( string $file_name){

        if (file_exists($file_name)) {
            
            return FALSE;
        }else{
                
            return touch($file_name);
        }

    }
    public function flux_entree(string $command, string $mode){

        $open_flux = popen($command,$mode);

        return nl2br(fread($open_flux,5000));
    }
    public function connexion_socket(string $name_domaine, int $port){

        $connexion = fsockopen($name_domaine, $port);
        $etat = stream_get_meta_data($connexion);
        if ($etat['timed_out']) {
            
            return FALSE;
        }
        if ($etat['blocked']) {

            return FALSE;
            
        }else {
            return TRUE;
        }
        if ($etat['eof']) {
           
            return 'Fin d\' envoi de donnees';
        }
        
    }
    public function theme_nav(string $caracter){

         if ($caracter =='#') {
                        
                echo "<style>
                        #head{ 
                            position:  absolute;
                            width: 100%;
                            margin-top: -3%;
                            height: 9%;
                            font-family:ubuntu;
                            background-color:green;}
                    #back{
                        position:  absolute;
                        width: 100%;
                        margin-top: 0%;
                        height: 300%;
                        background-image: url('ressources/images/1.jpg');
                        background-position: bottom;
                        background-repeat: no-repeat;
                        background-size: 180%;
                        color: white;
                    }
                </style>";
            }
        if ($caracter =='*') {
                        
            echo "<style>
                        #head{ 
                            position:  absolute;
                            width: 100%;
                            margin-top: -3%;
                            height: 9%;
                            font-family:ubuntu;
                            background-color:blue;}
                    #back{
                            position: absolute;
                            width: 100%;
                            margin-top: 0%;
                            height: 300%;
                            background-image: url('ressources/images/back.jpg');
                            background-position: bottom;
                            background-repeat: no-repeat;
                            background-size: 180%;
                            color: white;
                        }
                </style>";
            }
        if ($caracter=='$') {
                        
            echo "<style>
                        #head{
                        position:  absolute;
                        width: 100%;
                        margin-top: -3%;
                        height: 9%;
                        font-family:ubuntu;
                        background-color: rgb(0, 100, 100);
                    }
                    #back{
                        position:  absolute;
                        width: 100%;
                        margin-top: 0%;
                        height: 300%;
                        background-image: url('ressources/images/a.jpg');
                        background-position: bottom;
                        background-repeat: no-repeat;
                        background-size: 180%;
                        color: white;
                    }
                </style>";
        }
                   
    }
    

}
    













?>