<?php

    class EncryptionData {

        // Encrypt Function
        static function mc_encrypt($encrypt, $key){
            $encrypt = serialize($encrypt);

            // create initial vector
            $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC), MCRYPT_DEV_URANDOM);

            //
            $key = pack('H*', $key);

            //calc mac
            $mac = hash_hmac('sha256', $encrypt, substr(bin2hex($key), -32));

            // encrypt data with mac on end
            $passcrypt = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $encrypt.$mac, MCRYPT_MODE_CBC, $iv);
            // make encoded data
            $encoded = base64_encode($passcrypt).'|'.base64_encode($iv);
            return $encoded;
        }

        // Decrypt Function
        static function mc_decrypt($decrypt, $key){
            $decrypt = explode('|', $decrypt.'|');
            $decoded = base64_decode($decrypt[0]);
            $iv = base64_decode($decrypt[1]);
            if(strlen($iv)!==mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC)){
                // initial vector doesn't match, somebody manipulate with file
                return -1;
            }
            $key = pack('H*', $key);
            $decrypted = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $decoded, MCRYPT_MODE_CBC, $iv));

            //get mac from end
            $mac = substr($decrypted, -64);
            $decrypted = substr($decrypted, 0, -64);
            //calc mac from decrypted data
            $calcmac = hash_hmac('sha256', $decrypted, substr(bin2hex($key), -32));
            //check mac if it is the same

            if($calcmac!==$mac){
                // mac is different, somebody manipulate with file
                return -2;
            }
            $decrypted = unserialize($decrypted);
            return $decrypted;
        }

    }

