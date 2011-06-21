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
	
	public function __construct($column, $row)
	{
		$this->_row = $row;
		$this->_column = $column;
	}
	
	public static function factoryFromName($cellName)
	{
		$numericPart = preg_replace("/[^0-9]/", "",  $cellName);
		$row = $numericPart - 1;
		
		$letterPart = preg_replace("/[^a-zA-Z]/", "",  $cellName);
		$letterPart = strtoupper($letterPart);		
		$column = array_search($letterPart, range('A', 'Z'));

		return new self($column, $row);
	}
	
	public static function factoryFromCoordinates($column, $row)
	{
		return new self($column, $row);
	}
	
	public function getXCoordinate()
	{
		return $this->_column;
	}
	
	public function getYCoordinate()
	{
		return $this->_row;
	}
}