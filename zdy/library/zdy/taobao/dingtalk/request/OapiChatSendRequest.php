<?php
/**
 * dingtalk API: dingtalk.oapi.chat.send request
 * 
 * @author auto create
 * @since 1.0, 2018.05.09
 */
class OapiChatSendRequest
{
	/** 
	 * actionCard消息
	 **/
	private $actionCard;
	
	/** 
	 * 群会话id
	 **/
	private $chatid;
	
	/** 
	 * 文件消息
	 **/
	private $file;
	
	/** 
	 * 图片消息
	 **/
	private $image;
	
	/** 
	 * 链接消息
	 **/
	private $link;
	
	/** 
	 * markdown消息
	 **/
	private $markdown;
	
	/** 
	 * 消息类型
	 **/
	private $msgtype;
	
	/** 
	 * OA消息
	 **/
	private $oa;
	
	/** 
	 * 文本消息
	 **/
	private $text;
	
	/** 
	 * 语音消息
	 **/
	private $voice;
	
	private $apiParas = array();
	
	public function setActionCard($actionCard)
	{
		$this->actionCard = $actionCard;
		$this->apiParas["action_card"] = $actionCard;
	}

	public function getActionCard()
	{
		return $this->actionCard;
	}

	public function setChatid($chatid)
	{
		$this->chatid = $chatid;
		$this->apiParas["chatid"] = $chatid;
	}

	public function getChatid()
	{
		return $this->chatid;
	}

	public function setFile($file)
	{
		$this->file = $file;
		$this->apiParas["file"] = $file;
	}

	public function getFile()
	{
		return $this->file;
	}

	public function setImage($image)
	{
		$this->image = $image;
		$this->apiParas["image"] = $image;
	}

	public function getImage()
	{
		return $this->image;
	}

	public function setLink($link)
	{
		$this->link = $link;
		$this->apiParas["link"] = $link;
	}

	public function getLink()
	{
		return $this->link;
	}

	public function setMarkdown($markdown)
	{
		$this->markdown = $markdown;
		$this->apiParas["markdown"] = $markdown;
	}

	public function getMarkdown()
	{
		return $this->markdown;
	}

	public function setMsgtype($msgtype)
	{
		$this->msgtype = $msgtype;
		$this->apiParas["msgtype"] = $msgtype;
	}

	public function getMsgtype()
	{
		return $this->msgtype;
	}

	public function setOa($oa)
	{
		$this->oa = $oa;
		$this->apiParas["oa"] = $oa;
	}

	public function getOa()
	{
		return $this->oa;
	}

	public function setText($text)
	{
		$this->text = $text;
		$this->apiParas["text"] = $text;
	}

	public function getText()
	{
		return $this->text;
	}

	public function setVoice($voice)
	{
		$this->voice = $voice;
		$this->apiParas["voice"] = $voice;
	}

	public function getVoice()
	{
		return $this->voice;
	}

	public function getApiMethodName()
	{
		return "dingtalk.oapi.chat.send";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}