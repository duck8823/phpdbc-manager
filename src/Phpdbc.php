<?php
/**
 * Created by IntelliJ IDEA.
 * User: maeda
 * Date: 9/18/2016
 * Time: 7:25 PM
 */

use phpdbc\Manager;

class Phpdbc {

	public static function connect($driver, $datasource, $username = null, $password = null) {
		return new Manager($driver, $datasource, $username, $password);
	}

	public static function struct($typename, $fieldNames) {
		$members = "";
		$constructors = "";
		$columns = [];
		for ($i = 0; $i < count($fieldNames); $i++ ) {
			$members .= "	public \$$fieldNames[$i];\n";
			$constructors .= "		\$this->$fieldNames[$i] = \$$fieldNames[$i];\n";
			array_push($columns, '$' . $fieldNames[$i]);
		}
		$args = join(", ", $columns);
		eval(" 
class $typename {

$members

	function __construct($args) {
$constructors
	}
}");

	}
}