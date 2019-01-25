<?php
/**
 * Created by PhpStorm.
 * User: wilder7
 * Date: 24/01/19
 * Time: 09:44
 */

namespace App\Service;

use GuzzleHttp\Client;

class AddressChecker
{
    public function checkAddress(string $address)
    {
        $client = new Client(['base_uri' => 'https://api-adresse.data.gouv.fr']);
        $req = $client->request('GET', 'search', ['query' => ['q' => urlencode($address)]]);
        $coordinates =  json_decode($req->getBody(), true);
        return isset($coordinates['features'][0]['geometry']['coordinates']);
    }
}
