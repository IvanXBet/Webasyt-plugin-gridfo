<?php
class shopGridfoPluginGridGetContentController extends waJsonController
{
	public function execute()
	{
		$grid_id = waRequest::get('id', null, 'int');
		$path = wa()->getDataPath('plugins/gridfo/'.$grid_id.'.html', true, 'shop');
		if (file_exists($path)) 
		{
        	$grid = file_get_contents($path);
        	$this->response = array('result' => 1, 'content' => $grid);
		} 
		else 
		{
			$this->response = array('result' => 0, 'content' => false);
		}
		
	}
}