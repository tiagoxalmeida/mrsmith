<?php
 
$uniq = uniqid('file_',false).".enc";
//opendir("uploads/");
$myfile = fopen("uploads/file2.txt", "w") or die("Unable to open file!");
/*if(!$enc_file){
    $responseObject->success = false;
    echo json_encode($responseObject);
    exit;
}*/
fwrite($myfile,'$file');
fclose($myfile);