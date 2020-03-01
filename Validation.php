<?php

require_once('HaltCodePosition.php');

/**
*
* This class is for validating user Intcode program inputs
*/
class Validation
{

	use HaltCodePosition;

	public const LIMIT    	                = 100;
	public const TERMINATION_CODE           = 99;
	public const TERMINATION_CODE_ERR 		= 'No halt code (99) found in your entry!';

	public const VALID_OPCODES       		= [1,2,3,4,99];
	public const MODE_ERROR          		= 'Your mode input is invalid for: ';


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

	private $firstOp  = 0;
	private $secondOp = 0;
	private $thirdOp  = 0;
	private $forthOp  = 0;


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

		/**
		* Check positions of ABCDE of the opcode
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
		$errorMsgs =[];

		$chunkOfFour = array_chunk($this->intCodes, 4);
		foreach ($chunkOfFour as $key => $chunk) {

			// extract opcodes
			$opcode = str_split($chunk[0]);

			$opcodeCount = count($opcode);

			$errorMsgs = $this->extractAndValidateOpcode($opcode, $opcodeCount);

		}

		return $errorMsgs;
	}


	private function extractAndValidateOpcode(array $opcode, int $count)
	{
		
	}



}


?>