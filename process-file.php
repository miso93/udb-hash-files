<?php
/**
 * Created by PhpStorm.
 * User: Michal
 * Date: 15.10.2015
 * Time: 22:33
 */
require_once(__DIR__.'/encryption.php');

$uploaddir = __DIR__.'/files/hash/';
$uploadfile = $uploaddir . basename($_FILES['file']['name']);

if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
    echo "File is valid, and was successfully uploaded.\n";
} else {
    echo "Possible file upload attack!\n";
}

$key = "asdf46as5df4as5d";
$crypt = new Encryption($key);
$encrypted_string = $crypt->encrypt(file_get_contents($uploadfile));
file_put_contents($uploadfile.'.encrypt', $encrypted_string);

//$decrypted_string = $crypt->decrypt($encrypted_string); // this is a test

//echo hash_file('sha256', $uploadfile);


