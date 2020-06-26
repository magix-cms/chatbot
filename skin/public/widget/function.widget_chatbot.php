<?php
function smarty_function_widget_chatbot($params, $template){
	$addNeareoScript = false;
	$get = $params['get'];
    $collection = '';
	//var_dump($get);
	$allowedSections = $params['allowedSections'];
    $allowedWs = $params['allowedWs'];
	if(!empty($get) and isset($get['controller'])) {
		$controller = $get['controller'];
		if(key_exists($controller,$allowedSections)) {
			if (($allowedSections[$controller] === 'root' && !isset($get['id'])) ||
				($allowedSections[$controller] === 'item' && isset($get['id'])) ||
				(is_array($allowedSections[$controller])) && in_array($get['id'], $allowedSections[$controller])) $addNeareoScript = true;
		}

		if($controller == 'catalog'){
            if(isset($get['id_parent']) && isset($get['id'])){
                $controller = 'product';
            }elseif(!isset($get['id_parent']) && isset($get['id'])){
                $controller = 'category';
            }else{
                $controller = 'catalog';
            }
        }
        $collection = $controller;
	}
	elseif((empty($get) || !isset($get['controller'])) && isset($allowedSections['home'])) {
		$addNeareoScript = true;
        $collection = 'home';
	}

	if($allowedWs){
        if(in_array($collection,$allowedWs)) {
            $neareoVar = isset($get['id']) ? $collection.'_'.$get['id'] : $collection;
        }else{
            $neareoVar = null;
        }
    }
    $template->assign('neareoVar',$neareoVar);
	$template->assign('neareoScriptWithSubkey',$addNeareoScript);
}