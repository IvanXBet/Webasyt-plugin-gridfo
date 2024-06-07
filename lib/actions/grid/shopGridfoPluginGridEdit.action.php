<?php
class shopGridfoPluginGridEditAction extends waViewAction
{

	public function execute()
	{
		$grid_id = waRequest::get('id', 0, 'int');
		$product_id = waRequest::post('product_id', 0, 'int');
		
		$grid_model = new shopGridfoPluginGridModel();
		$grid = $grid_model->getById($grid_id);

		$this->view->assign('product_id', $product_id);
		$this->view->assign('grid', $grid);
	}
}