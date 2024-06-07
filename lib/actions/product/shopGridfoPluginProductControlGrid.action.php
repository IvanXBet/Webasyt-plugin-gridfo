<?php


class shopGridfoPluginProductControlGridAction extends waViewAction
{
	public function execute()
	{
		$product_id = waRequest::get('id', 0, 'int');

		$collection = new shopGridfoPluginGridCollection();
		$collection->addWhere('T.product_id = '.$product_id);
		$collection->setOrderBy("T.sort ASC");
		$girds = $collection->getItems('T.*');
		
		$this->view->assign('product_id', $product_id); 
		$this->view->assign('girds', $girds);
		
	}
}
