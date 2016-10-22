<?php
/**
 * Created by IntelliJ IDEA.
 * User: maeda
 * Date: 9/19/2016
 * Time: 2:29 PM
 */

use PHPUnit\Framework\TestCase;

use phpdbc\Manager;

class PhpdbcTest extends TestCase {

	function testConnect() {
		$manager = Phpdbc::connect("sqlite", "test.db");
		$this->assertInstanceOf(Manager::class, $manager);
	}
}