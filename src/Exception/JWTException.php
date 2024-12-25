<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class JWTException extends HttpException
{
	public function __construct(string $message, int $code)
	{
		parent::__construct($code, $message);
	}
}