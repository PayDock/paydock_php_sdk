<?php

require_once(__DIR__."/Shared/TestBase.php");

use PHPUnit\Framework\TestCase;
use Paydock\Sdk\Config;
use Paydock\Sdk\services\ExternalCheckout;
use Paydock\Sdk\ResponseException;

/**
 * @covers charges
 */
final class TestExternalCheckout extends TestBase
{
    public function testCreateForPaypal()
    {
        $svc = new ExternalCheckout();
        $response = $svc->create("test", "58bf55343c541b5b87f741bd", "https://wwww.success.com", "https://www.failure.com", "", [])
            ->call();
        $this->assertEquals("200", $response["status"]);
    }

    public function testCreateForZipMoney()
    {
        $svc = new ExternalCheckout();

        $meta =  [
            "first_name"=> "Fname",
            "last_name"=> "Lname",
            "email"=>"email@email.com",
            "tokenize"=> true,
            "description"=> "My test PayDock description",
            "charge"=> [
                "shipping_address"=> [
                    "first_name"=> "Fname",
                    "last_name"=> "Lname",
                    "line1"=> "1 Help St",
                    "country"=> "AU",
                    "postcode"=> "2000",
                    "city"=> "Sydney",
                    "state"=> "NSW"
                ],
                "billing_address"=> [
                    "first_name"=> "Fname",
                    "last_name"=> "Lname",
                    "line1"=> "1 Help St",
                    "country"=> "AU",
                    "postcode"=> "2000",
                    "city"=> "Sydney",
                    "state"=> "NSW"
                ],
                "amount"=> "4",
                "currency"=>"AUD",
                "items"=> [[
                    "name"=>"Shoes",
                    "amount"=>"2",
                    "quantity"=> 1,
                    "reference"=>"sds"
                ],
                [
                    "name"=>"Shoes2",
                    "amount"=>"2",
                    "quantity"=> 1,
                    "reference"=>"sds1"
                ]],
            ],
            "statistics"=> [
                "account_created"=> "2017-05-05",
                "sales_total_number"=> "5",
                "sales_total_amount"=> "4",
                "sales_avg_value"=> "45",
                "sales_max_value"=> "400",
                "refunds_total_amount"=> "1",
                "previous_chargeback"=> "false",
                "currency"=> "AUD",
                "last_login"=> "2017-06-01"
            ]
        ];

        $reference = uniqid();
        $response = $svc->create("test", "599a76529c1d950790167453", "", "", "https://www.redirecturl.com", $meta, "", $reference)
            ->call();
        $this->assertEquals("200", $response["status"]);
    }


    public function testCreateForAfterpay()
    {
        $svc = new ExternalCheckout();

        $meta =  [
            'amount' => '50',
            'currency' => 'AUD',
            'reference' => 'testreference',
            'email' => 'test@test.com',
            'first_name' => 'testFirstName',
            'last_name' => 'testLastName',
            'address_line1' => 'AddrLine1',
            'address_line2' => 'AddrLine2',
            'address_city' => 'AddrCity',
            'address_state' => 'NSW',
            'address_postcode' => '1234',
            'address_country' => 'AddrCountry',
            'phone' => '040000000'
        ];

        $reference = uniqid();
        $response = $svc->create("test", "59714107fd3abd105fd8ea42",  "https://wwww.success.com", "https://www.failure.com", "", $meta, "", $reference)
            ->call();
        $this->assertEquals("200", $response["status"]);
    }
}

