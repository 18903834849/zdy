<?php

namespace zdy;

/**
 * 实现和c#一致的DES加密解密
 * Class DES
 */
class DES
{
    
    /**
     * 解密字符串
     * @param string $str
     * @param string $key
     * @param int    $iv
     * @return string
     */
    public static function encrypt($str, $key = '', $iv = null)
    {
        //加密，返回大写十六进制字符串
        $size   = mcrypt_get_block_size(MCRYPT_DES, MCRYPT_MODE_CBC);
        $string = static::pkcs5Pad($str, $size);
        $string = mcrypt_encrypt(MCRYPT_DES, $key, $string, MCRYPT_MODE_CBC, $iv ? $iv : $key);
        $string = bin2hex($string);
        $string = strtoupper($string);
        return $string;
    }
    
    /**
     * 解密字符串
     * @param string $str
     * @param string $key
     * @param int    $iv
     * @return string
     */
    public static function decrypt($str, $key, $iv = null)
    {
        $strBin = static::hex2bin(strtolower($str));
        $string = mcrypt_decrypt(MCRYPT_DES, $key, $strBin, MCRYPT_MODE_CBC, $iv ? $iv : $key);
        $string = static::pkcs5Unpad($string);
        return $string;
    }
    
    /**
     * @param $hexData
     * @return string
     */
    private static function hex2bin($hexData)
    {
        $binData = "";
        for ($i = 0; $i < strlen($hexData); $i += 2) {
            $binData .= chr(hexdec(substr($hexData, $i, 2)));
        }
        return $binData;
    }
    
    /**
     * @param $text
     * @param $blockSize
     * @return string
     */
    private static function pkcs5Pad($text, $blockSize)
    {
        $pad = $blockSize - (strlen($text) % $blockSize);
        return $text . str_repeat(chr($pad), $pad);
    }
    
    /**
     * @param $text
     * @return bool|string
     */
    private static function pkcs5Unpad($text)
    {
        $pad = ord($text{strlen($text) - 1});
        if ($pad > strlen($text)) {
            return '';
        }
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) {
            return '';
        }
        return substr($text, 0, -1 * $pad);
    }
    
}
