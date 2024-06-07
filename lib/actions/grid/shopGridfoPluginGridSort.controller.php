<?php
class shopGridfoPluginGridSortController extends waJsonController
{
	public function execute()
	{
		$grids = waRequest::post('grids', '', 'string');
		if(!mb_strlen($grids)) {$this->response = array('result' => 0, 'message' => 'Системная ошибка #NOARR'); return;}
        $grids = str_replace("sets= ", "", $grids);
        $arrGrids = explode(',', $grids);
            
		$gridfo = waSystem::getInstance('shop')->getPlugin('gridfo');
		$this->response = $gridfo->sortGrid($arrGrids);
	}
}