<?php

namespace Healsdata\ScrabbleDoku\Test;

use PHPUnit\Framework\TestCase;
use Healsdata\ScrabbleDoku\Solver;
use Healsdata\ScrabbleDoku\Grid;
use Healsdata\ScrabbleDoku\UnsolvablePuzzleException;

class SolverTest extends TestCase
{
	/**
	 * @var Grid
	 */
	private $_grid;

	/**
	 * @var Solver
	 */
	private $_solver;

	/**
	 * @var Grid
	 */
	private $_solution;

	public function setUp() : void
	{
		$this->_solver = new Solver();
	}

	public function tearDown() : void
	{
		unset($this->_solver);
		unset($this->_grid);
		unset($this->_solution);
	}

	public function testAcceptancePuzzle49()
	{
		$this->_givenAScrabbledokuGrid();
		$this->_andAHintOf('F1', 'H');
		$this->_andAHintOf('G1', 'P');		
		$this->_andAHintOf('A2', 'P');
		$this->_andAHintOf('C3', 'P');
		$this->_andAHintOf('F3', 'A');
		$this->_andAHintOf('B5', 'C');
		$this->_andAHintOf('E5', 'E');
		$this->_andAHintOf('G6', 'C');
		$this->_andAHintOf('A7', 'O');
		$this->_andAHintOf('B7', 'A');
		$this->_whenSolvingFor('SHOEPAC');
		$this->_theSolutionIs(array(
			array('A', 'E', 'O', 'C', 'S', 'H', 'P'),
			array('P', 'O', 'A', 'H', 'C', 'E', 'S'),
			array('C', 'S', 'P', 'E', 'O', 'A', 'H'),
			array('S', 'H', 'E', 'A', 'P', 'C', 'O'),
			array('H', 'C', 'S', 'P', 'E', 'O', 'A'),
			array('E', 'P', 'H', 'O', 'A', 'S', 'C'),
			array('O', 'A', 'C', 'S', 'H', 'P', 'E'),
		));		
	}	
	
	public function testAcceptanceUnsolvable()
	{
		$this->_givenAScrabbledokuGrid();
		$this->_andTooFewHints();
		$this->_thenExpectAnException();
		$this->_whenSolvingFor('SHOEPAC');
	}	
	
	public function testAcceptanceSudokuExample()
	{
		$this->_givenASudokuGrid();
		$this->_andAHintOf('A1', '9');
		$this->_andAHintOf('B1', '3');		
		$this->_andAHintOf('C1', '4');
		$this->_andAHintOf('D1', '7');
		
		$this->_andAHintOf('A2', '2');		
		$this->_andAHintOf('D2', '9');
		$this->_andAHintOf('I2', '7');		
		
		$this->_andAHintOf('B3', '1');		
		$this->_andAHintOf('C3', '6');
		$this->_andAHintOf('E3', '3');
		$this->_andAHintOf('I3', '9');
		
		$this->_andAHintOf('F4', '3');
				
		$this->_andAHintOf('C5', '5');
		$this->_andAHintOf('D5', '1');
		$this->_andAHintOf('F5', '2');		
		$this->_andAHintOf('G5', '6');
		
		$this->_andAHintOf('D6', '6');

		$this->_andAHintOf('A7', '8');
		$this->_andAHintOf('E7', '4');
		$this->_andAHintOf('G7', '9');
		$this->_andAHintOf('H7', '6');
		
		$this->_andAHintOf('A8', '4');
		$this->_andAHintOf('F8', '1');
		$this->_andAHintOf('I8', '5');

		$this->_andAHintOf('F9', '9');
		$this->_andAHintOf('G9', '1');
		$this->_andAHintOf('H9', '3');
		$this->_andAHintOf('I9', '4');
		

		$this->_whenSolvingFor('123456789');
		$this->_theSolutionIs(array(
			str_split(934728516),
			str_split(258916347),
			str_split(716534829),
			str_split(687453291),
			str_split(345192678),
			str_split(129687453),
			str_split(871345962),
			str_split(493261785),
			str_split(562879134),
		));		
	}		
	
	private function _andTooFewHints()
	{
		$this->_andAHintOf('F1', 'H');
		$this->_andAHintOf('G1', 'P');		
		$this->_andAHintOf('A2', 'P');
		$this->_andAHintOf('C3', 'P');
		$this->_andAHintOf('F3', 'A');		
	}
	
	private function _thenExpectAnException()
	{
		$this->expectException(UnsolvablePuzzleException::class);
	}	

	private function _givenAScrabbledokuGrid()
	{
		$this->_grid = new Grid(7);
		$this->_grid->addShape(array('A1', 'B1', 'A2', 'B2', 'A3', 'A4', 'A5'));
		$this->_grid->addShape(array('C1', 'D1', 'E1', 'C2', 'C3', 'D2', 'D3'));
		$this->_grid->addShape(array('F1', 'G1', 'E2', 'F2', 'G2', 'E3', 'F3'));		
		$this->_grid->addShape(array('B3', 'B4', 'C4', 'D4', 'E4', 'F4', 'F5'));		
		$this->_grid->addShape(array('B5', 'C5', 'A6', 'B6', 'C6', 'A7', 'B7'));		
		$this->_grid->addShape(array('D5', 'E5', 'D6', 'E6', 'C7', 'D7', 'E7'));
		$this->_grid->addShape(array('G3', 'G4', 'G5', 'F6', 'G6', 'F7', 'G7'));		
	}
	
	private function _givenASudokuGrid()
	{
		$this->_grid = new Grid(9);
		$this->_grid->addShape(array('A1', 'B1', 'C1', 'A2', 'B2', 'C2', 'A3', 'B3', 'C3'));
		$this->_grid->addShape(array('D1', 'E1', 'F1', 'D2', 'E2', 'F2', 'D3', 'E3', 'F3'));
		$this->_grid->addShape(array('G1', 'H1', 'I1', 'G2', 'H2', 'I2', 'G3', 'H3', 'I3'));
		$this->_grid->addShape(array('A4', 'B4', 'C4', 'A5', 'B5', 'C5', 'A6', 'B6', 'C6'));
		$this->_grid->addShape(array('D4', 'E4', 'F4', 'D5', 'E5', 'F5', 'D6', 'E6', 'F6'));
		$this->_grid->addShape(array('G4', 'H4', 'I4', 'G5', 'H5', 'I5', 'G6', 'H6', 'I6'));
		$this->_grid->addShape(array('A7', 'B7', 'C7', 'A8', 'B8', 'C8', 'A9', 'B9', 'C9'));
		$this->_grid->addShape(array('D7', 'E7', 'F7', 'D8', 'E8', 'F8', 'D9', 'E9', 'F9'));
		$this->_grid->addShape(array('G7', 'H7', 'I7', 'G8', 'H8', 'I8', 'G9', 'H9', 'I9'));			
	}	
	
	private function _andAHintOf($cellName, $value)
	{
		$this->_grid->setValue($cellName, $value);	
	}
	
	private function _whenSolvingFor($puzzle)
	{
		$this->_solution = $this->_solver->solve($this->_grid, $puzzle);
	}
	
	private function _theSolutionIs($expectedCells)
	{
		$this->assertEquals($expectedCells, $this->_solution->getCells());
	}
	
}	