<?php

namespace App\Interface;

interface FormatterInterface
{
	public function toArray(): array;
	public function fromObject(): self;
	public function fromArray(array $array): self;
}