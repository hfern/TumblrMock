<?php
namespace TumblrMock;

class AutoLoader {
	/**
	 * Called by external client code to hook
	 * class loading system into PHP's
	 */
	public static function Register () {
		spl_autoload_register(function($classname){
			AutoLoader::ClassLoader($classname);
		});
	}
	/**
	 * Called by PHP for each unknown classname
	 * @param string $classname
	 */
	public static function ClassLoader ( $classname ) {
		$namespaces = explode("\\", $classname);
		if ($namespaces[0] == __NAMESPACE__) {
			$path_slices = array_slice($namespaces, 1);
			$path = __DIR__.'/'.join('/', $path_slices).'.php';
			if (file_exists($path)) {
				require_once($path);
			}
		}		
	}
}