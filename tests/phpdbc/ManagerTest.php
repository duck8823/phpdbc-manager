<?php
/**
 * Created by IntelliJ IDEA.
 * User: maeda
 * Date: 10/16/2016
 * Time: 8:34 PM
 */

use PHPUnit\Framework\TestCase;


class ManagerTest extends TestCase {

	function testCreate() {
		$manager = Phpdbc::connect('sqlite', 'test.db');
		$manager->drop(Test::class)->execute();

		$manager->create(new Test('INTEGER', 'TEXT'))->execute();

		$refcls = new ReflectionClass(phpdbc\Manager::class);
		$refprop = $refcls->getProperty('db');
		$refprop->setAccessible(true);

		$sth = $refprop->getValue($manager)->prepare('PRAGMA TABLE_INFO(Test)');
		$sth->execute();

		$rows = $sth->fetchAll();
		$this->assertEquals('id', $rows[0]['name']);
		$this->assertEquals('INTEGER', $rows[0]['type']);
		$this->assertEquals('name', $rows[1]['name']);
		$this->assertEquals('TEXT', $rows[1]['type']);
	}

	function testDrop() {
		$manager = Phpdbc::connect('sqlite', 'test.db');
		$manager->drop(Test::class)->execute();
		$manager->create(new Test('INTEGER', 'TEXT'))->execute();

		$manager->drop(Test::class)->execute();

		$refcls = new ReflectionClass(phpdbc\Manager::class);
		$refprop = $refcls->getProperty('db');
		$refprop->setAccessible(true);

		$sth = $refprop->getValue($manager)->prepare('PRAGMA TABLE_INFO(Test)');
		$sth->execute();
		$rows = $sth->fetchAll();
		$this->assertEquals(0, count($rows));
	}

	function testInsert() {
		$manager = Phpdbc::connect('sqlite', 'test.db');
		$manager->drop(Test::class)->execute();
		$manager->create(new Test('INTEGER', 'TEXT'))->execute();

		$manager->insert(new Test(1, 'name_1'))->execute();
		$manager->insert(new Test(2, 'name_2'))->execute();

		$refcls = new ReflectionClass(phpdbc\Manager::class);
		$refprop = $refcls->getProperty('db');
		$refprop->setAccessible(true);

		$sth = $refprop->getValue($manager)->prepare('SELECT * FROM Test');
		$sth->execute();
		$rows = $sth->fetchAll();

		$this->assertEquals(2, count($rows));
		$this->assertEquals(1, $rows[0]['id']);
		$this->assertEquals('name_1', $rows[0]['name']);
		$this->assertEquals(2, $rows[1]['id']);
		$this->assertEquals('name_2', $rows[1]['name']);
	}

	function testCreateSentence() {
		$refcls = new ReflectionClass(phpdbc\Manager::class);
		$refmethod = $refcls->getMethod('_createSentence');
		$refmethod->setAccessible(true);

		$actual = $refmethod->invoke(null, new Test(1, 'name_1'));
		$this->assertEquals("(id, name) VALUES ('1', 'name_1')", $actual);
	}
}