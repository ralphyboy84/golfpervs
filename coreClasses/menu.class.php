<?php

require_once ("menuController.class.php");

class menu
{
	private function _getMainMenuItems()
	{
		$mc = new menuController();
		$menuItems = $mc->returnMainMenuItems();
		
		if ($menuItems['res']) {
			return $menuItems['res'];
		}
	}
	
	
	private function _getSubMenuItems($menuid)
	{
		$mc = new menuController();
		$subItems = $mc->returnSubMenuItems($menuid);
		
		if ($subItems['res']) {
			return $subItems['res'];
		}
	}


	public function generateMenu()
	{
		$menuItems = $this->_getMainMenuItems();
		
		if ($menuItems) {
			$menu[] = "<ul class='sidebar-menu'>";
			
			foreach ($menuItems as $item) {
				$subItems = $this->_getSubMenuItems($item['menuid']);
				
				if ($subItems) {
					$menu[] = "<li class='treeview'>";
					$menu[] = "<a href='#'><i class='".$item['image']."'></i> <span>".$item['name']."</span> <i class='fa fa-angle-left pull-right'></i></a>";
					$menu[] = "<ul class='treeview-menu'>";
					
					if ($subItems) {
						foreach ($subItems as $sub) {
							$menu[] = "<li class='menuli' id='".$sub['id']."' data-directory='".$sub['directory']."'><a href='#'>".$sub['name']."</a></li>";
						}
					}
					
					$menu[] = "</ul>";
					$menu[] = "</li>";
				} else {
					$menu[] = "<li class='menuli' id='".$item['id']."'><a href='#'><i class='".$item['image']."'></i>".$item['name']."</a></li>";
				}
			}
			
			$menu[] = "</ul>";
			
			return implode($menu);
		}
	}
}

?>