<?php
class shopGridfoPluginGridDeleteController extends waJsonController
{
	public function execute()
	{
		$grid = waRequest::post('grid', null);
		$gridfo = waSystem::getInstance('shop')->getPlugin('gridfo');
		$this->response = $gridfo->removeGrid($grid['id']);
	}
}