<?php
class shopGridfoPluginGridUpdateContentController extends waJsonController
{

	public function execute()
	{
		$content = waRequest::post('content', null);
		$grid_id = waRequest::post('gridId', null, 'int');

		$gridfo = waSystem::getInstance('shop')->getPlugin('gridfo');
		$this->response = $gridfo->updateContent($content, $grid_id);
	}
}