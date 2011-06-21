<?php

class CellTest extends PHPUnit_Framework_TestCase
{
	public function testFactoryFromCoordinatesCreatesCorrectInstance()
	{
		$this->assertEquals(
			new Cell(1, 3),
			Cell::factoryFromCoordinates(1, 3)
		); 
	}
	
	public function testFactoryCellNameCreatesCorrectInstance()
	{
		$this->assertEquals(
			new Cell(1, 3),
			Cell::factoryFromName('B4')
		); 
	}	
	
	public function testReturnsCorrectValueForXCoordinate()
	{
		$expectedColumn = 4;
		$Cell = new Cell($expectedColumn, 5);
		$this->assertEquals($expectedColumn, $Cell->getXCoordinate());
	}
	
	public function testReturnsCorrectValueForYCoordinate()
	{
		$expectedRow = 4;
		$Cell = new Cell(5, $expectedRow);
		$this->assertEquals($expectedRow, $Cell->getYCoordinate());
	}	
}