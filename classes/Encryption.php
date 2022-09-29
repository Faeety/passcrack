<?php

class Encryption
{
    public function Encrypt(string $message, string $pbkey){

        openssl_public_encrypt($message, $encrypt, $pbkey, OPENSSL_PKCS1_OAEP_PADDING );

        return base64_encode($encrypt);
    }
}