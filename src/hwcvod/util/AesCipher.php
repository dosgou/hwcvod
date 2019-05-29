<?php
namespace hwcvod\util;

class AesCipher{
    const OPENSSL_CIPHER_NAME = "aes-128-cbc";

    const CIPHER_KEY_LEN = 16;

    /**
     * Encrypt data using AES Cipher (CBC) with 128 bit key
     *
     * @param $data - 待加密数据
     * @param $key - 密钥
     * @param $hasPoint - 是否有小数点
     * @return string
     */
    static function encrypt($data, $key, $hasPoint) {
        $iv = openssl_random_pseudo_bytes(AesCipher::CIPHER_KEY_LEN);
        if ($hasPoint){
            return base64_encode(openssl_encrypt($data, AesCipher::OPENSSL_CIPHER_NAME, AesCipher::get128BitKey($key), OPENSSL_RAW_DATA, $iv)).'.'.bin2hex($iv);
        } else {
            return base64_encode(openssl_encrypt($data, AesCipher::OPENSSL_CIPHER_NAME, AesCipher::get128BitKey($key), OPENSSL_RAW_DATA, $iv)).bin2hex($iv);
        }
    }

    private static function get128BitKey($key) {
        if (strlen($key) > AesCipher::CIPHER_KEY_LEN) {
            return substr($key, 0, AesCipher::CIPHER_KEY_LEN);
        } else {
            return $key;
        }
    }
}