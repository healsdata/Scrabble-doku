<?php

class GridTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @var Grid
	 */
	private $_grid;

	public function setUp()
	{
		$this->_grid = new Grid();
	}

	public function tearDown()
	{
		unset($this->_grid);
	}

	public function testContainsASevenBySevenGrid()
	{
		$rows = $this->_grid->getCells();
		$this->assertType('array', $rows);
		$this->assertEquals(7, sizeof($rows));
		
		foreach ($rows as $row) {
			$this->assertEquals(7, sizeof($row));
		}
	}

	public function testSetsAGivenValueToASpecificCell()
	{
		$row = 4;
		$col = 3;
		$value = 'A';
		$this->_grid->setValue($row, $col, $value);
		
		$cells = $this->_grid->getCells();
		
		$this->assertEquals($value, $cells[$row][$col]);		
	}

	public function testRetrievesCorrectValueFromASpecificCell()
	{
		$row = 4;
		$col = 3;
		$value = 'A';
		$this->_grid->setValue($row, $col, $value);
		$this->assertEquals($value, $this->_grid->getValue($row, $col));		
	}
	
	
	
}