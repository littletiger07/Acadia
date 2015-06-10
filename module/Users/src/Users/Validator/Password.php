<?php
namespace Users\Validator;

use Zend\Validator\AbstractValidator;

class PasswordStrength extends AbstractValidator {

	const LENGTH = 'length';
	const UPPER  = 'upper';
	const LOWER  = 'lower';
	const DIGIT  = 'digit';

	protected $messageTemplates = array(
			self::LENGTH => "'%value%' must be at least 6 characters long",
			self::UPPER => "'%value% must contain at least one uppercase letter",
			self::LOWER => "'%value% must contain at least one lowercase letter",
			self::DIGIT => "'%value% must contain at least one digit letter"
	);

	public function isValid($value) {
		if (strlen($value)<4)
		{
		    $this->error(self::LENGTH);
		    return false;
		}
		return true;
	
	}
}