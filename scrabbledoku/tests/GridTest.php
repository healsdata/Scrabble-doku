<?php

class GridTest extends PHPUnit_Framework_TestCase
{
	private function _givenAGridForXCharacters($numberOfCharacters)
	{
		return new Grid($numberOfCharacters);
	}
	
	public function testContainsAGridWithTheCorrectRowsAndCols()
	{
		$numberOfCharacters = 7;
		
		$grid = $this->_givenAGridForXCharacters($numberOfCharacters);
		$rows = $grid->getCells();
		$this->assertType('array', $rows);
		$this->assertEquals($numberOfCharacters, sizeof($rows));
		
		foreach ($rows as $row) {
			$this->assertEquals($numberOfCharacters, sizeof($row));
		}
	}

	public function testSetsAGivenValueToASpecificCell()
	{
		$grid = $this->_givenAGridForXCharacters(7);
	
		$row = 4;
		$col = 3;
		$value = 'A';
		$grid->setValue($row, $col, $value);
		
		$cells = $grid->getCells();
		
		$this->assertEquals($value, $cells[$row][$col]);		
	}

	public function testRetrievesCorrectValueFromASpecificCell()
	{
		$grid = $this->_givenAGridForXCharacters(7);
		
		$row = 4;
		$col = 3;
		$value = 'A';
		$grid->setValue($row, $col, $value);
		$this->assertEquals($value, $grid->getValue($row, $col));		
	}
	
	
	
}