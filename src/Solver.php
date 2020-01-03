<?php

namespace Healsdata\ScrabbleDoku;

class Solver
{	
	/**
	 * @var Grid
	 */
	private $_grid;
	
	/**
	 * @var array
	 */
	private $_puzzle;
	
	/**
	 * @var array
	 */
	private $_possibilities;
	
	/**
	 * @param Grid $grid
	 * @param string $puzzle
	 * @return Grid
	 */
	public function solve(Grid $grid, $puzzle)
	{
		$this->_grid = $grid;
		$this->_puzzle = str_split($puzzle);				
		$this->_possibilities = $this->_generatePossibilities();
		
		$this->_reducePossibilitiesForValuesAppearingInHint();		
		
		while ($this->_possibilitiesRemain()) {
			
			if ($this->_canAssignCellsWithOnlyOnePossibility()) {
				continue;
			}
		
			if ($this->_canAssignCellsWhereOnlyOneInGroupHasAPossiblity()) {
				continue;	
			}
			
			if ($this->_canReducePossibilitiesWhereOneValueAppearsInOneRowOrColumnInAShape()) {
				continue;
			}

			// If nothing above was a match, this puzzle is unsolveable.
			throw new UnsolvablePuzzleException();
		}
		
		return $this->_grid;
	}	
	
	private function _canAssignCellsWithOnlyOnePossibility()
	{
		foreach ($this->_possibilities as $cellName => $possibilities) {
			if (sizeof($possibilities) == 1) {
				$this->_assignValue($cellName, array_shift($possibilities));
				return true;
			}
		}		
		
		return false;
	}
	
	private function _canAssignCellsWhereOnlyOneInGroupHasAPossiblity()
	{
		foreach ($this->_possibilities as $cellName => $possibilities) {
			$onlyInRow = $possibilities;
			$onlyInCol = $possibilities;
			$onlyInShape = $possibilities;
			
			foreach ($this->_possibilities as $otherCellName => $otherPossibilities) {
				if ($cellName == $otherCellName) {
					continue;
				}
				
				if ($this->_isSameRow($cellName, $otherCellName)) {
					$onlyInRow = array_diff($onlyInRow, $otherPossibilities);
				}
				if ($this->_isSameColumn($cellName, $otherCellName)) {
					$onlyInCol = array_diff($onlyInCol, $otherPossibilities);
				}
				if ($this->_isSameShape($cellName, $otherCellName)) {
					$onlyInShape = array_diff($onlyInShape, $otherPossibilities);
				}
				
			}					

			if (sizeof($onlyInRow) == 1) {
				$this->_assignValue($cellName, array_shift($onlyInRow));
				return true;
			}
			if (sizeof($onlyInCol) == 1) {
				$this->_assignValue($cellName, array_shift($onlyInCol));
				return true;
			}
			if (sizeof($onlyInShape) == 1) {
				$this->_assignValue($cellName, array_shift($onlyInShape));
				return true;
			}								
		}			
		
		return false;
	}		
	
	public function _canReducePossibilitiesWhereOneValueAppearsInOneRowOrColumnInAShape()
	{
		$reducedPossibilities = false;
		
		foreach ($this->_grid->getShapes() as $shapeCellNames) {
			foreach ($this->_puzzle as $value) {
				$inRows = array();
				$inCols = array();
				
				foreach ($shapeCellNames as $shapeCell) {
					if (!in_array($value, $this->_possibilities[$shapeCell])) {
						continue;
					}
					
					$inRows[] = $this->_getRowFromCellName($shapeCell);
					$inCols[] = $this->_getColumnFromCellName($shapeCell);
				
					$inRows = array_unique($inRows);
					$inCols = array_unique($inCols);
					
					if (sizeof($inCols) > 1 && sizeof($inRows) > 1) {
						continue 2;
					}								
				}

				if (sizeof($inCols) == 1) {
					$col = array_shift($inCols);
					foreach ($shapeCellNames as $shapeCell) {
						if ($this->_getColumnFromCellName($shapeCell) == $col) {
							break;
						}
					}
					
					foreach ($this->_possibilities as $cellName => $possibilities) {
						if ($cellName == $shapeCell) {
							continue;
						}								
						if ($this->_isSameColumn($shapeCell, $cellName)
							&& !$this->_isSameShape($shapeCell, $cellName)
						) {
							if ($this->_removePossibilityFromCell($cellName, $value)) {
								$reducedPossibilities = true;	
							}		
													
						}
					}
				}
				
				if (sizeof($inRows) == 1) {
					$row = array_shift($inRows);
					foreach ($shapeCellNames as $shapeCell) {
						if ($this->_getRowFromCellName($shapeCell) == $row) {
							break;
						}
					}
					
					foreach ($this->_possibilities as $cellName => $possibilities) {
						if ($cellName == $shapeCell) {
							continue;
						}								
						if ($this->_isSameRow($shapeCell, $cellName)
							&& !$this->_isSameShape($shapeCell, $cellName)
						) {				
							if ($this->_removePossibilityFromCell($cellName, $value)) {
								$reducedPossibilities = true;								
							}
						}
					}
				}					
			}
		}		
		
		return $reducedPossibilities;
	}
	
	private function _generatePossibilities()
	{
		$possibilities = array();
		
		$rows = $this->_grid->getCells();
		foreach ($rows as $row => $cells){
			foreach ($cells as $col => $cell) {
				$cellName = Cell::factoryFromCoordinates($col, $row)->getName();
				$possibilities[$cellName] = $this->_puzzle;
			}			
		}
		
		return $possibilities;
	}
	
	private function _reducePossibilitiesForValuesAppearingInHint()
	{
		$rows = $this->_grid->getCells();
		foreach ($rows as $row => $cells){
			foreach ($cells as $col => $cell) {
				$cellName = Cell::factoryFromCoordinates($col, $row)->getName();
				$value = $this->_grid->getValue($cellName);
				if (!$value) {
					continue;
				}
				$this->_assignValue($cellName, $value);
			}			
		}		
	}	
	
	private function _possibilitiesRemain()
	{		
		foreach ($this->_possibilities as $possibilities) {
			if (sizeof($possibilities)) {
				return true;
			}
		}
		
		return false;
	}
	
	private function _removePossibilityFromCell($cellName, $value)
	{		
		$possibilities = $this->_possibilities[$cellName];
		if (!in_array($value, $possibilities)) {
			return false;
		}
		
		$remove = array($value);
		$this->_possibilities[$cellName] = array_diff($possibilities, $remove);
		return true;		
	}
	
	
	private function _assignValue($assignedCellName, $value)
	{
		$this->_grid->setValue($assignedCellName, $value);
		$this->_possibilities[$assignedCellName] = array();
		
		foreach ($this->_possibilities as $cellName => $possibilities) {
			if ($assignedCellName == $cellName) {
				continue;
			}
			
			if ($this->_isSameRow($assignedCellName, $cellName)
				|| $this->_isSameColumn($assignedCellName, $cellName)
				|| $this->_isSameShape($assignedCellName, $cellName)
			) {
				$this->_removePossibilityFromCell($cellName, $value);
			}
		}
	}
	
	private function _getRowFromCellName($cellName)
	{
		return Cell::factoryFromName($cellName)->getYCoordinate();
	}
	
	private function _getColumnFromCellName($cellName)
	{
		return Cell::factoryFromName($cellName)->getXCoordinate();
	}	
	
	private function _isSameRow($assignedCellName, $cellName)
	{
		return $this->_getRowFromCellName($assignedCellName) ==  $this->_getRowFromCellName($cellName);
	}
	
	private function _isSameColumn($assignedCellName, $cellName)
	{
		return $this->_getColumnFromCellName($assignedCellName) ==  $this->_getColumnFromCellName($cellName);
	}	
	
	private function _isSameShape($assignedCellName, $cellName)
	{
		$shapeCells = $this->_grid->getShapeForCell($assignedCellName);
		return in_array($cellName, $shapeCells);
	}		
}