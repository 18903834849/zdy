<?php

if (!function_exists('is_tel')) {
    /**
     *  验证是否为手机
     * @param $tel
     * @return bool
     */
    function is_tel($tel)
    {
        return is_numeric($tel) && preg_match('#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#', $tel);
    }
}

if (!function_exists('is_idcard')) {
    /**
     * 是否为身份证号
     * @param integer $vStr
     * @return bool
     */
    function is_idcard($vStr)
    {
        $vCity = array(
            '11', '12', '13', '14', '15', '21', '22',
            '23', '31', '32', '33', '34', '35', '36',
            '37', '41', '42', '43', '44', '45', '46',
            '50', '51', '52', '53', '54', '61', '62',
            '63', '64', '65', '71', '81', '82', '91'
        );
        
        if (!preg_match('/^([\d]{17}[xX\d]|[\d]{15})$/', $vStr)) return false;
        
        if (!in_array(substr($vStr, 0, 2), $vCity)) return false;
        
        $vStr    = preg_replace('/[xX]$/i', 'a', $vStr);
        $vLength = strlen($vStr);
        
        if ($vLength == 18) {
            $vBirthday = substr($vStr, 6, 4) . '-' . substr($vStr, 10, 2) . '-' . substr($vStr, 12, 2);
        } else {
            $vBirthday = '19' . substr($vStr, 6, 2) . '-' . substr($vStr, 8, 2) . '-' . substr($vStr, 10, 2);
        }
        
        if (date('Y-m-d', strtotime($vBirthday)) != $vBirthday) return false;
        if ($vLength == 18) {
            $vSum = 0;
            
            for ($i = 17; $i >= 0; $i--) {
                $vSubStr = substr($vStr, 17 - $i, 1);
                $vSum    += (pow(2, $i) % 11) * (($vSubStr == 'a') ? 10 : intval($vSubStr, 11));
            }
            
            if ($vSum % 11 != 1) return false;
        }
        
        return true;
    }
}

if (!function_exists('is_wap')) {
    /**
     * 是否为手机访问
     * @return boolean
     */
    function is_wap()
    {
        if (isset ($_SERVER ['HTTP_VIA']) && stristr($_SERVER ['HTTP_VIA'], "wap")) {
            return true;
        } elseif (isset ($_SERVER ['HTTP_ACCEPT']) && strpos(strtoupper($_SERVER ['HTTP_ACCEPT']), "VND.WAP.WML")) {
            return true;
        } elseif (isset ($_SERVER ['HTTP_X_WAP_PROFILE']) || isset ($_SERVER ['HTTP_PROFILE'])) {
            return true;
        } elseif (isset ($_SERVER ['HTTP_USER_AGENT']) && preg_match('/(blackberry|configuration\/cldc|hp |hp-|htc |htc_|htc-|iemobile|kindle|midp|mmp|motorola|mobile|nokia|opera mini|opera |Googlebot-Mobile|YahooSeeker\/M1A1-R2D2|android|iphone|ipod|mobi|palm|palmos|pocket|portalmmm|ppc;|smartphone|sonyericsson|sqh|spv|symbian|treo|up.browser|up.link|vodafone|windows ce|xda |xda_)/i', $_SERVER ['HTTP_USER_AGENT'])) {
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('is_weixin')) {
    function is_weixin()
    {
        if (empty ($_SERVER ['HTTP_USER_AGENT']) || strpos($_SERVER ['HTTP_USER_AGENT'], 'MicroMessenger') === false && strpos($_SERVER ['HTTP_USER_AGENT'], 'Windows Phone') === false) {
            return false;
        }
        return true;
    }
}

if (!function_exists('is_ios')) {
    function is_ios()
    {
        if (strpos($_SERVER ['HTTP_USER_AGENT'], 'iPhone') || strpos($_SERVER ['HTTP_USER_AGENT'], 'iPad')) {
            return true;
        }
        return false;
    }
}

if (!function_exists('is_email')) {
    /**
     * 验证是否为邮箱
     * @param string $var
     * @return bool
     */
    function is_email($var)
    {
        return filter_var($var, FILTER_VALIDATE_EMAIL) == true;
    }
}

if (!function_exists('is_base64')) {
    /**
     * 验证码字符串是否为base64编码
     * @param $str
     * @return bool
     */
    function is_base64($str)
    {
        return $str == base64_encode(base64_decode($str));
    }
}

if (!function_exists('is_image_str')) {
    /**
     * 判断字符串是否为图片字符串
     * @param $str
     * @return bool
     */
    function is_image_str($str)
    {
        $strInfo   = @unpack("C2chars", substr($str, 0, 2));
        $typeCode  = intval($strInfo['chars1'] . $strInfo['chars2']);
        $typeCodes = ['255216' /*jpg*/, '7173' /*gif*/, '13780' /*png*/];
        return in_array($typeCode, $typeCodes);
    }
}

if (!function_exists('is_image_file')) {
    /**
     * 判断文件是否为图片
     * @param $filename
     * @return bool
     */
    function is_image_file($filename)
    {
        $file = fopen($filename, "rb");
        $str  = fread($file, 2); // 只读2字节
        fclose($file);
        return is_image_str($str);
    }
}


