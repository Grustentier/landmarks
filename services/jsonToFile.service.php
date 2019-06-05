<?php 

if (!isset ($_SESSION)) {
	session_start();
}

$json = null;
if (isset ($_REQUEST['json'])) {
    $json = $_REQUEST['json'];
} else
	if (isset ($_POST['json'])) {
	    $json = $_POST['json'];
	} else {

	} 

if($json != null){
    
    //file_put_contents("../data/".time().".json", $json);
	$myfile = fopen("../data/".session_id()."_".microtime(true).".json", "w") or die("Unable to open file!"); 
    fwrite($myfile, $json); 
    fclose($myfile);
    
    echo true;
}

?>