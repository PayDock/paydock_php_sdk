<?php
namespace Paydock\Sdk;

use \Exception;

/*
 * This file is part of the Paydock.Sdk package.
 *
 * (c) Paydock
 *
 * For the full copyright and license information, please view
 * the LICENSE file which was distributed with this source code.
 */
class ResponseException extends Exception
{
    public $Status;
    public $ErrorMessage;
    public $JsonResponse;

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    /**
     * @param mixed $Status
     */
    public function setCode($Status)
    {
        $this->code = $Status;
    }

    /**
     * @param mixed $ErrorMessage
     */
    public function setMessage($ErrorMessage)
    {
        $this->ErrorMessage = $ErrorMessage;
        $this->message = $ErrorMessage;
    }

    /**
     * @param mixed $JsonResponse
     */
    public function setJsonResponse($JsonResponse)
    {
        $this->JsonResponse = $JsonResponse;
    }

}
