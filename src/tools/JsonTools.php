<?php
namespace Paydock\Sdk;

/*
 * This file is part of the Paydock.Sdk package.
 *
 * (c) Paydock
 *
 * For the full copyright and license information, please view
 * the LICENSE file which was distributed with this source code.
 */
 final class JsonTools
{
    public function CleanArray($array)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = $this->CleanArray($value);
            } else {
                if (empty($value)){
                    unset($array[$key]);
                }
            }
        }
        return $array;
    }
}
?>