<?php
/**
 * Created by IntelliJ IDEA.
 * User: maeda
 * Date: 9/19/2016
 * Time: 2:29 PM
 */

use PHPUnit\Framework\TestCase;

use phpdbc\Where;
use phpdbc\Operator;


class ReadmeTest extends TestCase {

	function testReadme() {
		# クラスを動的に生成
		Phpdbc::struct('PgTest', ['id', 'name', 'flg']);

		# データベースへの接続
		$manager = Phpdbc::connect("pgsql", "dbname=test host=localhost port=5432", 'postgres');

		# 初期処理
		$manager->drop(PgTest::class)->execute();

		# テーブルの作成
		$manager->create(new PgTest('INTEGER', 'TEXT', 'BOOLEAN'))->execute();
		# データの挿入
		$manager->insert(new PgTest(1, 'name_1', true))->execute();
		$manager->insert(new PgTest(2, 'name_2', false))->execute();

		# データの取得（リスト）
		$rows = $manager->from(PgTest::class)->list();
		foreach ($rows as $row) {
			print_r($row);
		}
		$manager->from(PgTest::class)->where(new Where('name', 'name', Operator::LIKE()))->list();
		# データの取得（一意）
		$row = $manager->from(PgTest::class)->where(new Where('id', 1, Operator::EQUAL()))->singleResult();
		print_r($row);
		# データの削除
		$manager->from(PgTest::class)->where(new Where('id', 1, Operator::EQUAL()))->delete()->execute();
		# テーブルの削除
		$manager->drop(PgTest::class)->execute();
		# SQLの取得
		$create_sql = $manager->create(new PgTest('INTEGER', 'TEXT', 'BOOLEAN'))->getSql();
		$insert_sql = $manager->insert(new PgTest(1, 'name_1', true))->getSql();
		$delete_sql = $manager->from(PgTest::class)->where(new Where('id', 1, Operator::EQUAL()))->delete()->getSql();
		$drop_sql = $manager->drop(PgTest::class)->getSql();
	}
}