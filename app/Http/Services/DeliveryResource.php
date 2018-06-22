<?php

namespace App\Http\Services;


use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use GuzzleHttp\Psr7\Request;

use App\Http\Contracts\DeliveryResourceInterface;

class DeliveryResource implements DeliveryResourceInterface
{
    /**
     * Get details via asynchronous requests
     * @param  Request $request
     * @return array
     */
    public function getDetails()
    {
        $client = new Client([
                                'base_uri' => 'https://api.staging.lbcx.ph',
                                'headers'   => ['X-Time-Zone' => 'Asia/Manila']
        ]);
                
        // Initiate each request but do not block
        $promises = [
                    $client->getAsync('/v1/orders/0077-6495-AYUX'),
                    $client->getAsync('/v1/orders/0077-6491-ASLK'),
                    $client->getAsync('/v1/orders/0077-6490-VNCM'),
                    $client->getAsync('/v1/orders/0077-6478-DMAR'),
                    $client->getAsync('/v1/orders/0077-1456-TESV'),
                    $client->getAsync('/v1/orders/0077-0836-PEFL'),
                    $client->getAsync('/v1/orders/0077-0526-EBDW'),
                    $client->getAsync('/v1/orders/0077-0522-QAYC'),
                    $client->getAsync('/v1/orders/0077-0516-VBTW'),
                    $client->getAsync('/v1/orders/0077-0424-NSHE')
        ];
                
        // Wait on all of the requests to complete. Throws a ConnectException
        // if any of the requests fail
        $results = Promise\unwrap($promises);
        
        // Wait for the requests to complete, even if some of them fail
        $results = Promise\settle($promises)->wait();
        
        // You can access each result using the key provided to the unwrap
        // function.
        $data = [];
        $getTotalCollection = 0;
        $getSubtotalSales   = 0;
        for($i=0; $i<count($promises); $i++)
        {
            $details = json_decode($results[$i]['value']->getBody()->getContents(),true);
            $data[] = [ 
                        $details['tracking_number']." (".$details['status'].")" => [
                            //"history"   => $details['tat'],
                            "history"   => self::sortDataByDate($details['tat'],'date'),
                            "breakdown" => [
                                            "subtotal"          => $details['subtotal'],
                                            "shipping"          => $details['shipping'],
                                            "tax"               => $details['tax'],
                                            "fee"               => $details['fee'],
                                            "insurance"         => $details['insurance'],
                                            "discount"          => $details['discount'],
                                            "total"             => $details['total'] 
                            ],
                            "fee"       => [
                                            "shipping fee"      =>  $details['shipping_fee'],
                                            "insurance_fee"     =>  $details['insurance_fee'],
                                            "transaction_fee"   =>  $details['transaction_fee']
                            ],
                        ]
                      ];
            
            $getTotalCollection     +=  $details['total'];
            $getSubtotalSales       += $details['shipping_fee'] + $details['insurance_fee'] + $details['transaction_fee'];
                    
        }
        $data[] = [
                    "Total collections" => $getTotalCollection,
                    "Total Sales"       => $getSubtotalSales
        ];
        return $data;
    }
    
    /**
     * Sort data
     * @param  array
     * @return array
     */
    public function sortDataByDate($array = [], $sorted_data = "")
    {
        
        foreach ($array as $key => $val) 
        {
            $sort[$key] = $val[$sorted_data];
        }
        
        array_multisort($sort, SORT_ASC, $array);
        return $sort;
    }
}