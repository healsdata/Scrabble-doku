<?php

class Cell
{
	/**
	 * @var integer
	 */
	private $_row;
	
	/**
	 * @var integer
	 */
	private $_column;
	
	/**
	 * @param integer $column
	 * @param integer $row
	 */	
	public function __construct($column, $row)
	{
		$this->_row = $row;
		$this->_column = $column;
	}
	
	/**
	 * @param string $cellName
	 * @return Cell
	 */
	public static function factoryFromName($cellName)
	{
		$numericPart = preg_replace("/[^0-9]/", "",  $cellName);
		$row = $numericPart - 1;
		
		$letterPart = preg_replace("/[^a-zA-Z]/", "",  $cellName);
		$letterPart = strtoupper($letterPart);		
		$column = array_search($letterPart, range('A', 'Z'));

		return new self($column, $row);
	}
	
	/**
	 * @param integer $column
	 * @param integer $row
	 * @return Cell
	 */
	public static function factoryFromCoordinates($column, $row)
	{
		return new self($column, $row);
	}
	
	/**
	 * @return integer
	 */
	public function getXCoordinate()
	{
		return $this->_column;
	}
	
	/**
	 * @return integer
	 */
	public function getYCoordinate()
	{
		return $this->_row;
	}	
	
	/**
	 * @return string
	 */
	public function getName()
	{
		$letters = range('A', 'Z');
		$columnName = $letters[$this->_column];
		
		$rowName = $this->_row + 1;
		
		return $columnName . $rowName;
	}
}