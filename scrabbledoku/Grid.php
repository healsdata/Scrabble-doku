<?php

class Grid
{
	private $_cells;
	
	public function __construct()
	{
		$this->_cells = array();
		
		for ($row = 0; $row < 7; $row++)
		{
			$gridRow = array();
			
			for ($col = 0; $col < 7; $col++)
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