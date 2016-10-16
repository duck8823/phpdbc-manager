<?php
/**
 * Created by IntelliJ IDEA.
 * User: maeda
 * Date: 9/19/2016
 * Time: 2:29 PM
 */

use PHPUnit\Framework\TestCase;

use phpdbc\Manager;
use phpdbc\Where;
use phpdbc\Operator;


class PhpdbcTest extends TestCase {

	function testConnect() {
		$manager = Phpdbc::connect("sqlite", "test.db");
		$this->assertInstanceOf(Manager::class, $manager);
//
//		$manager->drop(Hoge::class)->execute();
//		$manager->create(new Hoge('INTEGER', 'TEXT'))->execute();
//		$manager->insert(new Hoge(1, 'name_1'))->execute();
//		$manager->insert(new Hoge(2, 'name_2'))->execute();
//
//		$list = $manager->from(Hoge::class)->list();
//
//		$this->assertEquals([new Hoge(1, 'name_1'), new Hoge(2, 'name_2')], $list);
//		$this->assertEquals('DROP TABLE IF EXISTS Hoge', $manager->drop(Hoge::class)->getSql());
//
//		$this->assertEquals(new Hoge(1, 'name_1'), $manager->from(Hoge::class)->where(new Where("id", 1, Operator::EQUAL))->singleResult());
	}
}