<?php

namespace App\Exception;

use Symfony\Component\Translation\Exception\NotFoundResourceException;

class FormException extends NotFoundResourceException
{
	private array $formError;
	public function __construct(string $message, array $formError = [], int $code = 400)
	{
		parent::__construct($message, $code);
		$this->setFormErrors($formError);
	}

	public function getFormErrors(): array
	{
		return $this->formError;
	}

	private function setFormErrors(array $formError): self
	{
		$this->formError = $formError;
		return $this;
	}
}