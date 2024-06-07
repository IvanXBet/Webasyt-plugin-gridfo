<?php
return array
	(
		'name' => 'gridfo',
		'version' => '1.0.0',
		'vendor' => 995002,
		'description' => 'Позволяет добавлять таблицы к товарам',
		'img' => 'img/grid.svg',
		'handlers' => array
						(
							'backend_product' => 'backendProduct',
							'backend_menu' => 'backendMenu',
							'frontend_product' => 'frontendProduct',
						),
	);