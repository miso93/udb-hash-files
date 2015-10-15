<?php

require_once(__DIR__.'/encryption.php');

if($_GET['file']){
    $uploaddir = __DIR__.'/files/hash/';
    $uploadfile = $uploaddir . basename($uploaddir.$_GET['file'].'.encrypt');

    if(file_exists($uploadfile)){
        $key = "asdf46as5df4as5d";
        $crypt = new Encryption($key);
        $decrypted_string = $crypt->decrypt(file_get_contents($uploadfile));
        echo $decrypted_string;
    }

} else {
    echo 'Write \'file\' argument please.';
}
