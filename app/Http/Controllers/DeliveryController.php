<?php

namespace App\Http\Controllers;

use App\Http\Contracts\ResponseFormatterInterface;
use App\Http\Contracts\DeliveryResourceInterface;

class DeliveryController extends Controller
{
    public $delivery;
    
    protected $format;
    
    public function __construct(DeliveryResourceInterface $delivery, ResponseFormatterInterface $responseFormatter)
    {
        $this->delivery = $delivery;
        $this->format = $responseFormatter;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = $this->format->prepareSuccessResponseBody($this->delivery->getDetails());
        return $response;
    }
    
}
