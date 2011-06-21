<?php

class Grid
{
	/**
	 * @var integer
	 */
	private $_numberOfCharacters;
	
	private $_cells;
	
	private $_shapes;
	
	public function __construct($numberOfCharacters)
	{
		$this->_numberOfCharacters = $numberOfCharacters;
		
		$this->_cells = array();
		
		for ($row = 0; $row < $this->_numberOfCharacters; $row++)
		{
			$gridRow = array();
			
			for ($col = 0; $col < $this->_numberOfCharacters; $col++)
			{
				$gridRow[] = '';
			}
			
			$this->_cells[] = $gridRow;
		}
	}
		
	public function getCells()
	{
		return $this->_cells;		
	}
	
	public function getShapes()
	{
		return $this->_shapes;
	}
	
	/**
	 * @param string $cellName
	 * @return integer
	 */
	private function _translateCellNameToRow($cellName)
	{
		$numericPart = preg_replace("/[^0-9]/", "",  $cellName);
		return $numericPart - 1;
	}
	
	/**
	 * @param string $cellName
	 * @return integer
	 */
	private function _translateCellNameToColumn($cellName)
	{
		$letterPart = preg_replace("/[^a-zA-Z]/", "",  $cellName);
		$letterPart = strtoupper($letterPart);
		foreach (range('A', 'Z') as $column => $letter) {
			if ($letterPart == $letter) {
				return $column;
			}
		}
	}	
	
	/**
	 * @param string $cellName
	 * @param string $value
	 * @return void
	 */
	public function setValue($cellName, $value)
	{
		$row = $this->_translateCellNameToRow($cellName);
		$col = $this->_translateCellNameToColumn($cellName);
		
		$this->_cells[$row][$col] = $value;
	}
	
	/**
	 * @param string $cellName
	 * @return string
	 */
	public function getValue($cellName)
	{
		$row = $this->_translateCellNameToRow($cellName);
		$col = $this->_translateCellNameToColumn($cellName);		
		return $this->_cells[$row][$col]; 
	}
	
	public function addShape($cells)
	{
		$this->_shapes[] = $cells;
	}
	
	public function getShapeForCell($cellName)
	{
		foreach ($this->_shapes as $key => $cells) {
			if (in_array($cellName, $cells)) {
				return $this->_shapes[$key];
			}
		}
	}	
	
}