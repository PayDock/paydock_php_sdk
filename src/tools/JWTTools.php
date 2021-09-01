<?php
namespace Paydock\Sdk\tools;

/*
 * This file is part of the Paydock.Sdk package.
 *
 * (c) Paydock
 *
 * For the full copyright and license information, please view
 * the LICENSE file which was distributed with this source code.
 */
final class JWTTools
{
    public function isJWTToken($input) {
        return (count(explode('.',$input)) == 3);
    }
}
