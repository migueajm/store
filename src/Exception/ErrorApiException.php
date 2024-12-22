<?php

namespace App\Exception;

use App\Entity\AppError;
use App\Entity\Error;
use App\Entity\RequestError;
use App\Interface\FormatterInterface;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use \Throwable;

class ErrorApiException extends Exception
{

	private Throwable $exception;
	private Request $request;
	public function __construct(Throwable $exception, Request $request)
	{
		parent::__construct($exception->getMessage(), $exception->getCode());
		$this->setExeption($exception)
			->setRequest($request);
	}

	public function __toString(): string
	{
		return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
	}

	public function getError(): Error
	{
		$className = self::getClassName($this->exception);
		$message = $this->exception->getMessage();
		$code = $this->exception->getCode();
		$request = $this->request;
		$error = new Error();
		$error->setCode($code)
			->setError($message	)
			->setRequest(new RequestError(
				$request->getMethod(),
				$request->getUri(),
				json_decode($request->getContent(), true),
				$request->headers->all(),
				$request->query->all()
			))
			->setException($className);
		return $error;
	}

	private function setExeption(Throwable $exception): self
	{
		$this->exception = $exception;
		return $this;
	}

	private function setRequest(Request $request): self
	{
		$this->request = $request;
		return $this;
	}

	static function getClassName(\Throwable $exception): string
	{
			$classNameParts = explode('\\', $exception::class);
			return end($classNameParts);
	}
}