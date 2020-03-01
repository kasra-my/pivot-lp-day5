<?php

trait HaltCodePosition
{
	
	/**
	* Get the key of the halt code (99) in the intcode array
	* @return int
	*/
	public function getHaltCodePosition(array $intCodes, int $terminationCode): int
	{
		foreach ($intCodes as $key => $code) {

			if ($code == $terminationCode){

				return $key;

			}

		}
	}
}
?>