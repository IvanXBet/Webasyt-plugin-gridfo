<?php

class shopGridfoPlugin  extends shopPlugin

{
	
	/////////////////////////////////////////////////////////////////////////////////////
	// Хуки
	/////////////////////////////////////////////////////////////////////////////////////
	

	public function backendProduct($data)
	{
		$view = wa()->getView();

		$plugin = waRequest::get('plugin', '', 'string');
		$module = waRequest::get('module', '', 'string');
		$action = waRequest::get('action', '', 'string');
		
		$gridfo_core_li_class = 'no-tab';
		if($plugin == 'gridfo' && $module == 'backend' && $action == 'control') {$gridfo_core_li_class = 'selected';}
		$view->assign('gridfo_core_li_class', $gridfo_core_li_class);

		$view->assign('product_id', $data['id']);
		return array('edit_section_li' => $view->fetch(wa()->getAppPath(null, 'shop').'/plugins/gridfo/templates/BackendMenuEditSectionLi.html'));
	}

	public function backendMenu()
	{
		$view = wa()->getView();
		return array(
			'aux_li' => $view->fetch(wa()->getAppPath(null, 'shop').'/plugins/gridfo/templates/BackendMenuAuxLi.html'),
		);
	}

	static public function frontendProductGrids($product_id) 
	{
		$collection = new shopGridfoPluginGridCollection();
		$collection->addWhere("T.product_id = ".$product_id);
		$collection->setOrderBy("T.sort ASC");
		$grids = $collection->getItems('T.id, name');

		$items = array();

		foreach ($grids as $grid_id => $grid) {
			$path = wa()->getDataPath('plugins/gridfo/'.$grid_id.'.html', true, 'shop', true);
			
			if (file_exists($path)) {
				$html_content = file_get_contents($path);
			} else {
				$html_content = '';
			}

			
			$items[] = array(
				'id' => $grid_id,
				'name' => $grid['name'],
				'content' => $html_content
			);
		}
		
		
		$view = wa()->getView();
		$view->assign('gfo_grids', $items);
		return $view->fetch(wa()->getAppPath(null, 'shop') . '/plugins/gridfo/templates/FrontendGridBlock.html');
	}
	
	/////////////////////////////////////////////////////////////////////////////////////
	// Работа с таблицами
	/////////////////////////////////////////////////////////////////////////////////////


	public function addGrid($data) 
	{
		$grid_model = new shopGridfoPluginGridModel();

		if (!isset($data['product_id']) || empty($data['product_id'])) {
			return array('result' => 0, 'message' => 'Продукт не найден', 'data' => $data);
		}

		$name = trim(ifempty($data['name'], ''));
		if (mb_strlen($name) == 0) {
			return array('result' => 0, 'message' => 'Укажите имя');
		}
		$data['name'] = $grid_model->escape($name);
		$data['product_id'] = $grid_model->escape($data['product_id']);

		try {
			$id = $grid_model->add($data);
			return array('result' => 1, 'message' => 'Готово', 'grid' => $grid_model->getById($id));
		} catch (Exception $e) {
			return array('result' => 0, 'message' => 'Ошибка при сохранении сета: ' . $e->getMessage());
		}
	}



	public function updateGridName($data) 
	{
		$id = ifempty($data['id'], 0);
		if(array_key_exists('id', $data)) {unset($data['id']);}
		
		$grid_model = new shopGridfoPluginGridModel();
		$ex = $grid_model->getById($id);
		if(!$ex) {return array('result' => 0, 'message' => 'Список не найден');}
		$grid_model->updateById($id, $data);
		return array('result' => 1, 'message' => 'Имя изменено', 'grid' => $grid_model->getById($id));
	}

	

	public function removeGrid($grid_id) 
	{
		$grid_model = new shopGridfoPluginGridModel();
		
		$path = wa()->getDataPath('plugins/gridfo/', true, 'shop');
		$grid_id = $grid_model->escape($grid_id);

		if(is_numeric($grid_id)) 
		{
			if($grid_model->getById($grid_id)) 
			{
				$grid = $grid_model->getById($grid_id);
				$grid_model->deleteById($grid_id);
				if(file_exists($path.'/'.$grid['id'].'.html')) {
					unlink($path.'/'.$grid['id'].'.html');
				}
				$result[] = array('result' => 1, 'message' => 'Таблица удалена', 'grid' => $grid);
			}
			else
			{
				$result[] = array('result' => 0, 'message' => 'Такой таблицы не существует');
			} 
			
		}
		else
		{
			$result[] = array('result' => 0, 'message' => 'Ошибка удаления', 'grid_id' => $grid_id);
		}
		
		return $result;
	}

	public function sortGrid($grids) 
	{
		if(!count($grids)) {return array('result' => 0, 'message' => 'Не заданн список для сротировки');}
		$grid_model = new shopGridfoPluginGridModel();
		return $grid_model->sortGrids($grids);
	}

	public function updateContent($content, $grid_id)
	{

		$id = ifempty($grid_id, 0);
		$grid_model = new shopGridfoPluginGridModel();

		$result = array();

		if(!is_numeric($grid_id)) 
		{
			$result[] = array('result' => 0, 'message' => 'Ошибка записи таблицы.', 'grid_id' => $grid_id);
			return $result;
		}
		$path = wa()->getDataPath('plugins/gridfo/'.$grid_id.'.html', true, 'shop');
			
		try
		{
			if((file_exists($path) && !is_writable(($path)) || !file_exists($path) && !waFiles::create($path))) 
			{
				$result[] = array('result' => 0, 'message' => 'Ошибка записи таблицы. Проверте права на запись');
			}
			else
			{
				waFiles::write($path, $content);
				$result[] = array('result' => 1, 'message' => 'Таблица сохранена');
			}
		}
		catch (Exception $e)
		{
			$result[] = array('result' => 0, 'message' => $e->getMessage());
		}
			
		
		return $result;
	}
}
