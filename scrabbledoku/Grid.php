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
	 * @param string $value
	 * @return void
	 */
	public function setValue($cellName, $value)
	{
		$Cell = Cell::factoryFromName($cellName);
		$this->_cells[$Cell->getYCoordinate()][$Cell->getXCoordinate()] = $value;
	}
	
	/**
	 * @param string $cellName
	 * @return string
	 */
	public function getValue($cellName)
	{
		$Cell = Cell::factoryFromName($cellName);
		return $this->_cells[$Cell->getYCoordinate()][$Cell->getXCoordinate()]; 
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