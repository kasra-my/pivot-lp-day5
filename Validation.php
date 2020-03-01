<?php

require_once('HaltCodePosition.php');

/**
*
* This class is for validating user Intcode program inputs
*/
class Validation
{

	use HaltCodePosition;

	public const LIMIT    	        = 100;
	public const TERMINATION_CODE    = 99;

	private const NOT_INT_ENTRY    			= "Your input is invalid for: ";
	private const INVALID_OP_CODE 			= "Something went wrong. Opcode must be 1, 2 or 99!";


	public const INVALID_OPERATTIONS		= "Please notice operations that end of with invalid results are ignored so you will see the same thing as you entered. For example, if the result of add/multiple is greater than the Intcode array count, it will be ignored because that index key is not available! Also, the halt code value (99) will never overwritten!";


	// @var string
	private $userInput;

	/**
	* Intcodes entered by user as an array of integers
	* @var array
	*/
	private $intCodes;


	/**
	 *  
	 * @param $userInput
	 *
	*/
	public function __construct(string $userInput)
	{
		$this->userInput = $userInput;
		$this->intCodes = explode(",", $this->userInput);
	}


	/**
	 * Do all validations one by one and if there is something wrong
	 * in each step, break and return the error messages
	 * @return array $errorMsgs
	 */
	public function validate(): array
	{

		// Validate user inputs to make sure they are integers
		if ( !empty($this->isInteger()) ){
			return $this->isInteger();
		}		

		// Check whether there is enough intcode (at least 5)
		if ( !empty($this->isThereEnoughIntCode()) ){
			return $this->isThereEnoughIntCode();
		}

		// Check all numbers are smaller than limit (100)
		if ( !empty($this->isSmallerThanLimit()) ){
			return $this->isSmallerThanLimit();
		}		

		/**
		* Check termination opcode (99) is available in the
		* user input and it is in the right position. Also checks 
		* the opcode is not anything else other than 1 and 2
		*
		*/
		if ( !empty($this->isValidOpcode()) ){
			return $this->isValidOpcode();
		}		

		/** 
		* Checks whether the values of 1st, 2nd and 3rd 
		* positions are valid in the given input intcodes array
		*/
		if ( !empty($this->isValidPosition()) ){
			return $this->isValidPosition();
		}			

		if ( !empty($this->isValidOpcode()) ){
			return $this->isValidOpcode();
		}				

		return [];
	}


	/** 
	* Validate user inputs to make sure they are integers
	* @return array of errors
	*/
	private function isInteger()
	{
		$errorMsgs = [];
		// Are inputs integer?
		foreach ($this->intCodes as $key => $value) {
			if(preg_replace('/[^0-9]/', '', $value) === ""){
				$errorMsgs[] = self::NOT_INT_ENTRY . '"' . $value . '"'; 
			}
		}

		return $errorMsgs;
	}

	/**
	* Check whether there is enough intcode 
	*  ( > 5in) the user input
	*/
	private function isThereEnoughIntCode()
	{
		$errorMsgs = [];
		if(count($this->intCodes) < 5) {
			$errorMsgs[]	= 'At least 5 numbers needed in your Intcode program entry!';
		}
		return $errorMsgs;
	}	

	/**
	* Check all numbers are smaller than limit (100) 
	*/
	private function isSmallerThanLimit()
	{
		$errorMsgs = [];
		foreach ($this->intCodes as $key => $code) {
			if ($code >= self::LIMIT){
				$errorMsgs[] = $code . ' is greater than "' . self::LIMIT . '"';
			}
		}
		return $errorMsgs;
	}


	/**
	* Check termination opcode (99) is available in the
	* user input and it is in the right position. Also checks 
	* the opcode is not anything else other than 1 and 2
	*
	*/
	private function isValidOpcode()
	{
		$foundHaltCode = false;
		$errorMsgs =[];

		$chunkOfFour = array_chunk($this->intCodes, 4);
		foreach ($chunkOfFour as $key => $chunk) {

			if (isset($chunk[0]) && $chunk[0] == self::TERMINATION_CODE){

				$foundHaltCode = true;

			}elseif(isset($chunk[0]) && ($chunk[0] != 1 && $chunk[0] != 2)){

				$errorMsgs[]= 'Your opcode is invalid for: "' . $chunk[0] .'" '. self::INVALID_OP_CODE;

			}

		}

		if (!$foundHaltCode){
			$errorMsgs[]="Something went wrong! No termination opcode (99) found in the right position!";
		}
		return $errorMsgs;
	}


	/**
	* Checks whether the values of 1st, 2nd and 3rd 
	* positions are valid in the given input intcodes array
	*/
	private function isValidPosition()
	{
		$countOfIntcodes = count($this->intCodes);
		$errorMsgs =[];

		$haltCodePosition = $this->getHaltCodePosition($this->intCodes, Validation::TERMINATION_CODE);
		
		// Only the position of intcodes before halt code (99) needs to be validated
		$beforeHaltCode = array_slice($this->intCodes, 0, $haltCodePosition);


		$chunkOfFour = array_chunk($beforeHaltCode, 4);

		foreach ($chunkOfFour as $key => $chunk) {

			if (isset($chunk[3]) && $chunk[3] > $countOfIntcodes){

				$errorMsgs[] = 'The position of "' . $chunk[3] . '" is not available in your given intcodes!';
			}
		}

		return $errorMsgs;
	}



}


?>