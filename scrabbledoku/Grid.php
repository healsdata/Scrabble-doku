<?php

class Grid
{
	/**
	 * @var integer
	 */
	private $_numberOfCharacters;
	
	private $_cells;
	
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
	
	/**
	 * @param integer $row
	 * @param integer $col
	 * @param string $value
	 * @return void
	 */
	public function setValue($row, $col, $value)
	{
		$this->_cells[$row][$col] = $value;
	}
	
	/**
	 * @param integer $row
	 * @param integer $col
	 * @return string
	 */
	public function getValue($row, $col)
	{
		return $this->_cells[$row][$col]; 
	}
	
}