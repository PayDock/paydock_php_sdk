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
final class UrlTools
{
    public function BuildQueryStringUrl($baseUrl, $id, $filter)
    {
        $url = $baseUrl;
        if (!empty($id)) {
            $url .= "/" . urlencode($id);
        } else if (!empty($filter)) {
            $url .= "?";
            foreach ($filter as $key => $value) {
                $url .= urlencode($key) . "=" . urlencode($value);
            }
        }
        return $url;
    }
}
?>