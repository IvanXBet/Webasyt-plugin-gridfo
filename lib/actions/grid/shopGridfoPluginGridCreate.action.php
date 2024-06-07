<?php


class shopGridfoPluginGridCreateAction extends waViewAction
{

	public function execute()
	{
		$product_id = waRequest::post('product_id', null, 'int');
		$this->view->assign('product_id', $product_id);	
	}
}