<?php
/**
 * dingtalk API: dingtalk.corp.extcontact.create request
 * 
 * @author auto create
 * @since 1.0, 2018.03.06
 */
class CorpExtcontactCreateRequest
{
	/** 
	 * 外部联系人信息
	 **/
	private $contact;
	
	private $apiParas = array();
	
	public function setContact($contact)
	{
		$this->contact = $contact;
		$this->apiParas["contact"] = $contact;
	}

	public function getContact()
	{
		return $this->contact;
	}

	public function getApiMethodName()
	{
		return "dingtalk.corp.extcontact.create";
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
