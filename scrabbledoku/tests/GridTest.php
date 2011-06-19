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
	
		$grid->setValue('A1', 'C');
		$grid->setValue('D5', 'F');
		$grid->setValue('G7', 'B');
		
		$cells = $grid->getCells();
		
		$this->assertEquals('C', $cells[0][0]);
		$this->assertEquals('F', $cells[4][3]);
		$this->assertEquals('B', $cells[6][6]);	
			
	}

	public function testRetrievesCorrectValueFromASpecificCell()
	{
		$grid = $this->_givenAGridForXCharacters(7);
		
		$cell = 'A1';
		$value = 'C';
		$grid->setValue($cell, $value);
		$this->assertEquals($value, $grid->getValue($cell));		
	}
	
	
	
}