<?php namespace Atoz;

class Parser {

	public static function LoadApi($path, $ref_separator = '/', $action_prefix = '', $object_prefix = '') {
		$api = NULL;

		if( file_exists($path) ) {
		    $api = json_decode(file_get_contents($path));

		    if( json_last_error() )
		    {
		        $api = NULL;
		    }
		}

		$api->actions = self::Format($api->actions, $ref_separator, $action_prefix);
		$api->objects = self::Format($api->objects, $ref_separator, $object_prefix);
		$api->actions_tree = self::GenerateTree($api->actions, $ref_separator, $action_prefix);
        $api->objects_tree = self::GenerateTree($api->objects, $ref_separator, $object_prefix);
        $api->actions_index = self::GenerateIndex($api->actions, $ref_separator, $action_prefix);
        $api->objects_index = self::GenerateIndex($api->objects, $ref_separator, $object_prefix);

		return $api;
	}

	public static function Format($items, $ref_separator = '/', $prefix = '') {
		foreach( $items as $i => $item ) {
			$items[$i]->ref_cleaned = self::CleanReference($item->ref, $ref_separator, $prefix);
			$items[$i]->ref_formatted = self::FormatReference($item->ref, $ref_separator, $prefix);
		}

		return $items;
	}

	public static function FormatReference($ref, $ref_separator = '/', $prefix = '') {
		$ref = self::CleanReference($ref, $ref_separator, $prefix);

		return implode($ref_separator,array_map(function ($str) { return ucfirst($str); }, explode($ref_separator, $ref)));
	}

	public static function CleanReference($ref, $ref_separator = '/', $prefix = '') {
		$ref = strtolower($ref);
		$prefix = strtolower($prefix);

		while( strpos($ref, $ref_separator.$ref_separator) !== FALSE ) {
			$ref = str_replace($ref_separator.$ref_separator, $ref_separator, $ref);
		}

		if( substr($ref, 0, strlen($prefix)) === $prefix ) {
			$ref = substr($ref, strlen($prefix));
		}

		if( substr($ref,0,1) == $ref_separator ) {
			$ref = substr($ref,1);
		}

		if( substr($ref,-1) == $ref_separator ) {
			$ref = substr($ref,0,-1);
		}

		return $ref;
	}

	public static function GenerateIndex($items, $ref_separator = '/', $prefix = '') {
		$index = [];

		foreach( $items as $i => $item ) {
			$ref = self::CleanReference($item->ref,$ref_separator,$prefix);
			
			$index[strtolower($ref)] = $i;
		}

		return $index;
	}

	public static function GenerateTree($items, $ref_separator = '/', $prefix = '') {

		$tree = [
			'name' => 'Base',
			'class' => null,
			'tree' => [],
		];

		foreach( $items as $item )
		{
			$ref = self::CleanReference($item->ref, $ref_separator, $prefix);

			self::addItemToTree($tree['tree'], explode($ref_separator, $ref));
		}

		self::sortTree($tree);

		return $tree['tree'];
	}

	private static function addItemToTree(&$tree, $parts, $path = '') {
		if( ! count($parts) ) {
			return;
		}

		$name = ucfirst($parts[0]);

		if( ! isset($tree[$name]) ) {
			$tree[$name] = [
				'class' => null,
				'name' => ucfirst($name),
				'tree' => [],
			];
		}

		if( count($parts) === 1 ) {
			$tree[$name]['class'] = self::FormatReference($path.'/'.$name,'/');
		} else {
			self::addItemToTree($tree[$name]['tree'], array_slice($parts,1), $path.'/'.$name);
		}

		return;
	}

	private static function sortTree(&$tree) {
		foreach( $tree['tree'] as $index => $child ) {
			self::sortTree($tree['tree'][$index]);
		}

		usort($tree['tree'], array('self', 'cmpName'));

		return;
	}

	private static function cmpName($a, $b) {
		return strcmp($a['name'], $b['name']);
	}
}