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
}
