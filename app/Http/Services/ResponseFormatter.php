<?php

namespace App\Http\Services;

use App\Http\Contracts\ResponseFormatterInterface;

class ResponseFormatter implements ResponseFormatterInterface
{

	public function prepareSuccessResponseBody($data, $code = '200')
	{
		return [
			'code'		=> $code,
			'status'	=> 'SCC',
			'data'		=> $data
		];
	}

	public function prepareNotFoundResponseBody($message = 'Resource not found')
	{
		return [
			'code'		=> '404',
			'status'	=> 'NFR',
			'message'	=> $message
		];
	}

	public function prepareErrorResponseBody($message = 'An error occured during operation')
	{
		return [
			'code'		=> '500',
			'status'	=> 'ERR',
			'message'	=> $message
		];
	}

}