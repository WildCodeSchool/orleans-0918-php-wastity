<?php
/**
 * Created by PhpStorm.
 * User: patricia
 * Date: 11/12/18
 * Time: 11:20
 */

namespace App\Service;

use App\Controller\HasAddress;
use GuzzleHttp\Client;

class FindCoordinates
{
   public function findCoordinates(HasAddress $adress): ?float
    {
        $client = new Client(['base_uri' => 'https://api-adresse.data.gouv.fr']);

        $resAdress= $client->request('GET', 'search', ['query' => ['q' => $adress->fullAddress()]]);

        $resAdress = json_decode($resAdress->getBody(), true);

        $coordinates=$resAdress['features'][0]['geometry']['coordinates'];

        return $coordinates;
    }
}
