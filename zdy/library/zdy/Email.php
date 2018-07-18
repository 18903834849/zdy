<?php

namespace zdy;

/**
 * 邮箱服务
 * @author zdy
 */
class Email
{
    /**
     * @var null|\PHPMailer
     */
    protected $mail = null;
    
    /**
     * 构造方法
     * Email constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        if (empty($config['host'])) {
            throw new \Exception('host not null');
        }
        if (empty($config['port'])) {
            throw new \Exception('port not null');
        }
        if (empty($config['username'])) {
            throw new \Exception('username not null');
        }
        if (empty($config['password'])) {
            throw new \Exception('password not null');
        }
        $Host     = $config['host'];//SMTP 服务器
        $Port     = $config['port'];//SMTP服务器的端口号
        $Username = $config['username'];// SMTP服务器用户名
        $Password = $config['password'];// SMTP服务器密码
        //////////////////////////发送邮件////////////////////////////////
        require_once dirname(__FILE__) . "/PHPMailer/PHPMailerAutoload.php";
        $mail           = new \PHPMailer();
        $mail->Host     = $Host; // SMTP 服务器
        $mail->Port     = $Port; // SMTP服务器的端口号
        $mail->Username = $Username; // SMTP服务器用户名
        $mail->Password = $Password; // SMTP服务器密码
        $mail->SeetFrom($Username, $Username); //发送人邮箱和名称
        $mail->AddReplyTo($Username, $Username); //回复人名称邮箱
        $mail->SMTPDebug  = isset($config['debug']) ? $config['debug'] : false; // 关闭SMTP调试功能
        $mail->SMTPAuth   = isset($config['auth']) ? $config['auth'] : true; // 启用 SMTP 验证功能
        $mail->SMTPSecure = isset($config['secure']) ? $config['secure'] : 'ssl'; // 使用安全协议
        $mail->CharSet    = isset($config['char_set']) ? $config['char_set'] : 'utf-8'; // 设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
        $mail->Mailer     = isset($config['mailer']) ? $config['mailer'] : 'smtp'; // 设定使用SMTP服务
        $this->mail       = $mail;
    }
    
    /**
     * 初始化
     * @param array $config
     */
    public static function init($config = [])
    {
        /*$default = [
            'host'     => 'smtp.qq.com',
            'port'     => '465',
            'username' => '983656621@qq.com',
            'password' => 'ouojpauogqzcbffg'
        ];
        return new self(array_merge($default, $config));*/
    }
    
    /**
     * 设置接收邮箱地址
     * @param $emails
     * @return $this
     */
    function setTo($emails)
    {
        if (is_array($emails)) {
            foreach ($emails as $email) {
                $this->mail->addAddress($email);
            }
        } else {
            $this->mail->addAddress($emails);
        }
        return $this;
    }
    
    /**
     * 设置邮件主题
     * @param $subject
     * @return $this
     */
    function setSubject($subject)
    {
        $this->mail->Subject = $subject;
        return $this;
    }
    
    /**
     * 设置邮件正文
     * @param $content
     * @return $this
     */
    function setContent($content)
    {
        $this->mail->msgHTML($content);
        return $this;
    }
    
    /**
     * 设置发件人
     * @param string $address
     * @param string $name
     */
    function SetFrom($address, $name = '')
    {
        $this->mail->setFrom($address, $name);
        return $this;
    }
    
    /**
     * 发送邮件
     * @param string|array $email   接收邮箱地址
     * @param string       $subject 邮件主题
     * @param string       $body    邮件内容
     * @param callable     $fun     回调函数
     * @return boolean
     */
    public function send($email, $subject, $body, $callback = '')
    {
        try {
            // 邮件标题
            $this->mail->Subject = $subject;
            // 邮件正文
            $this->mail->MsgHTML($body);
            // 批量添加邮件接受人
            if (is_array($email)) {
                foreach ($email as $v) $this->mail->AddAddress($v);
            } else {
                $this->mail->AddAddress($email);
            }
            if ($this->mail->Send()) {
                if ($callback instanceof \Closure) {
                    $send_info['to_email']  = array_keys($this->mail->getAllRecipientAddresses());
                    $send_info['subject']   = $subject;
                    $send_info['body']      = $body;
                    $send_info['send_time'] = time();
                    $callback($send_info);
                }
                return true;
            } else {
                return $this->mail->ErrorInfo;
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    
    
}