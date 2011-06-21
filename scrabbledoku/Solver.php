<?php

class Solver
{
	
	public function solve(Grid $grid, $puzzle)
	{
		$this->_grid = $grid;
		$this->_puzzle = str_split($puzzle);
		
		$this->_possibilities = array();
		
		$rows = $this->_grid->getCells();
		foreach ($rows as $row => $cells){
			foreach ($cells as $col => $cell) {
				$cellName = $this->_getCellName($row, $col);
				$this->_possibilities[$cellName] = $this->_puzzle;
			}			
		}
		
		foreach ($rows as $row => $cells){
			foreach ($cells as $col => $cell) {
				$cellName = $this->_getCellName($row, $col);
				$value = $this->_grid->getValue($cellName);
				if (!$value) {
					continue;
				}
				$this->_assignValue($cellName, $value);
			}			
		}
		
		while (!$this->_isFinished()) {
			
			// If something can only be one thing, it must be that.
			foreach ($this->_possibilities as $cellName => $possibilities) {
				if (sizeof($possibilities) == 1) {
					$this->_assignValue($cellName, array_shift($possibilities));
					continue 2;
				}
			}			
			
			// Each row, column and shape must have one of each thing.
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
					continue 2;
				}
				if (sizeof($onlyInCol) == 1) {
					$this->_assignValue($cellName, array_shift($onlyInCol));
					continue 2;
				}
				if (sizeof($onlyInShape) == 1) {
					$this->_assignValue($cellName, array_shift($onlyInShape));
					continue 2;
				}								

			}				
			
			// If a value only appears in one row or column in a shape,
			// other shapes can't have it
			foreach ($this->_grid->getShapes() as $shapeCellNames) {
				foreach ($this->_puzzle as $value) {
					$inRows = array();
					$inCols = array();
					
					foreach ($shapeCellNames as $shapeCell) {
						if (!in_array($value, $this->_possibilities[$shapeCell])) {
							continue;
						}
						
						$inRows[] = substr($shapeCell, 1, 1);
						$inCols[] = substr($shapeCell, 0, 1);
					
						$inRows = array_unique($inRows);
						$inCols = array_unique($inCols);
						
						if (sizeof($inCols) > 1 && sizeof($inRows) > 1) {
							continue 2;
						}								
					}

					if (sizeof($inCols) == 1) {
						$col = array_shift($inCols);
						// Remove from non-shape in column
						foreach ($shapeCellNames as $shapeCell) {
							if (substr($shapeCell, 0, 1) == $col) {
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
								$this->_removePossibilityFromCell($cellName, $value);								
							}
						}
					}
					
					if (sizeof($inRows) == 1) {
						$row = array_shift($inRows);
						// Remove from non-shape in column
						foreach ($shapeCellNames as $shapeCell) {
							if (substr($shapeCell, 1, 1) == $row) {
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
								$this->_removePossibilityFromCell($cellName, $value);								
							}
						}
					}
					
				}
			}
			//break;
		}
	}
	
	private function _isFinished()
	{		
		foreach ($this->_possibilities as $possibilities) {
			if (sizeof($possibilities)) {
				return false;
			}
		}
		
		return true;
	}
	
	private function _removePossibilityFromCell($cellName, $value)
	{	
		$possibilities = $this->_possibilities[$cellName];
		$remove = array($value);
		$this->_possibilities[$cellName] = array_diff($possibilities, $remove);		
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
	
	private function _isSameRow($assignedCellName, $cellName)
	{
		return substr($assignedCellName, 1, 1) == substr($cellName, 1, 1);
	}
	
	private function _isSameColumn($assignedCellName, $cellName)
	{
		return substr($assignedCellName, 0, 1) == substr($cellName, 0, 1);
	}	
	
	private function _isSameShape($assignedCellName, $cellName)
	{
		$shapeCells = $this->_grid->getShapeForCell($assignedCellName);
		return in_array($cellName, $shapeCells);
	}		
	
	public function getGrid()
	{
		return $this->_grid;
	}
	
	private function _getCellName($row, $col)
	{
		$cellName = $this->_translateColumnToLetter($col);
		$row++;
		$cellName .= $row;
		return $cellName;		
	}
	
	
	private function _translateColumnToLetter($col)
	{
		$letters = range('A', 'Z');
		return $letters[$col];
	}
	
	private function _setValueForLocation($row, $col, $value)
	{

		return $this->_setValueForCell($cellName, $value);	
	}
	
}