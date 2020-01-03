<?php

namespace Healsdata\ScrabbleDoku\Test;

use PHPUnit\Framework\TestCase;
use Healsdata\ScrabbleDoku\Grid;

class GridTest extends TestCase
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
		$this->assertIsArray($rows);
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
	
	public function testFindsShapeForASpecificCell()
	{
		$grid = $this->_givenAGridForXCharacters(7);
		
		$theShape = array('A1', 'B1', 'A2', 'B2', 'A3', 'A4', 'A5');
		$grid->addShape($theShape);
		
		$this->assertEquals($theShape, $grid->getShapeForCell('A1'));
	}
	
}