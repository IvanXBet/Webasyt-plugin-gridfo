<?php
class shopGridfoPluginGridCreateConfirmController extends waJsonController
{
	public function execute()
	{
		$grid = waRequest::post("grid", null);
		if(!is_array($grid)) {$this->response = array("result"=> 0,"message"=> "Системная ошибка"); return;}
		
		$gridfo = waSystem::getInstance('shop')->getPlugin('gridfo');
		$this->response = $gridfo->addGrid($grid);
	}
}


