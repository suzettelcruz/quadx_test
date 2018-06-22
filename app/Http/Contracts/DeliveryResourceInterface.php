<?php

namespace App\Http\Contracts;


interface DeliveryResourceInterface
{

    public function getDetails();
    public function sortDataByDate($array = [], $sorted_data = "");

}