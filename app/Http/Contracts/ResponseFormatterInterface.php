<?php

namespace App\Http\Contracts;

interface ResponseFormatterInterface
{

	public function prepareSuccessResponseBody($data, $code = '200');

	public function prepareNotFoundResponseBody($message = '');

	public function prepareErrorResponseBody($message = '');

}