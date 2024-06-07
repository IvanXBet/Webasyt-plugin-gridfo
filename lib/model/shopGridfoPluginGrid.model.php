<?php 
    class shopGridfoPluginGridModel extends waModel
    {
        protected $table = "shop_gridfo_grid";

        public function getMaxSort($product_id) 
        {
            $data = $this->query("SELECT  MAX(sort) AS mx FROM ".$this->table." WHERE product_id = ".$product_id)->fetchAll();
           
            if(!count($data)) {return 0;}
            return $data[0]["mx"];
        }

        public function add($data)
        {
            if(array_key_exists("id", $data)) {unset($data['id']);}
            $data['sort'] = $this->getMaxSort($data['product_id']) + 1;
            return $this->insert($data);
        }

        public function sortGrids($arrGrids)
        {
            if(!count($arrGrids)) {return;}
            $sort = 1;
            foreach($arrGrids as $key => $id) {
                $this->updateById($id, array('sort' => $sort));
                $sort++;
            }
            return array(
                'data' => $arrGrids,
                'mas' => 'Готово',
            );
        }
    }