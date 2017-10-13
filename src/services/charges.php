<?php
namespace Paydock\Sdk;

require_once(__DIR__."/../tools/serviceHelper.php");
use Paydock\Sdk\serviceHelper;
/*
 * This file is part of the Paydock.Sdk package.
 *
 * (c) Paydock
 *
 * For the full copyright and license information, please view
 * the LICENSE file which was distributed with this source code.
 */
final class Charges
{
    private $chargeData;
    private $token;
    
    // TODO: define signature
    public function create($amount, $currency, $description = "", $reference = "")
    {
        $this->chargeData = array("amount" => $amount, "currency"=>$currency, "description"=>$description, "reference" => $reference);
        return $this;
    }

    public function withToken($token)
    {
        $this->token = $token;
        return $this;
    }

    public function call()
    {
        // TODO: add validation that at least one payment option has been provided (eg token, customer etc)

        $data = json_encode([
            'amount'      => $this->chargeData["amount"],
            'currency'    => $this->chargeData["currency"],
            'reference'   => $this->chargeData["reference"],
            'description' => $this->chargeData["description"],
            'token'       => $this->token,
        ]);

        // TODO: handle optional parameters being passed through (eg token, credit card etc)

        return ServiceHelper::privateApiCall("POST", "charges", $data);

        // TODO: handle errors
    }
}
?>