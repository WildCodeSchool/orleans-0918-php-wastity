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
use Symfony\Component\HttpFoundation\Session\Session;

class DistanceCalculator
{
    const EARTH_RADIUS = 6378137;

    private function calculateDistance(array $coordinatesStart, array $coordinatesEnd):float
    {
        $rlo1 = deg2rad($coordinatesStart[1]);
        $rla1 = deg2rad($coordinatesStart[0]);
        $rlo2 = deg2rad($coordinatesEnd[1]);
        $rla2 = deg2rad($coordinatesEnd[0]);

        $dlo = ($rlo2 - $rlo1) / 2;
        $dla = ($rla2 - $rla1) / 2;
        $a = (sin($dla) * sin($dla)) + cos($rla1) * cos($rla2) * (sin($dlo) * sin($dlo));
        $d = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return round(self::EARTH_RADIUS * $d / 1000, 2);
    }
    
    public function calculateDistanceFromAddresses(HasAddress $start, HasAddress $end): ?float
    {
        $client = new Client(['base_uri' => 'https://api-adresse.data.gouv.fr']);

        $resStart= $client->request('GET', 'search', ['query' => ['q' => urlencode($start->fullAddress())]]);
        $resEnd= $client->request('GET', 'search', ['query' => ['q' => urlencode($end->fullAddress())]]);

        $resStart = json_decode($resStart->getBody(), true);
        $resEnd = json_decode($resEnd->getBody(), true);

        $coordinatesStart=$resStart['features'][0]['geometry']['coordinates'];
        $coordinatesEnd=$resEnd['features'][0]['geometry']['coordinates'];

        return $this->calculateDistance($coordinatesStart, $coordinatesEnd);
    }


    public function calculateDistanceFromGps(?float $startLat, ?float $startLong, HasAddress $end): ?float
    {
        $client = new Client(['base_uri' => 'https://api-adresse.data.gouv.fr']);

        $resEnd= $client->request('GET', 'search', ['query' => ['q' => $end->fullAddress()]]);

        $resEnd = json_decode($resEnd->getBody(), true);

        $coordinatesStart=[$startLong,$startLat];
        $coordinatesEnd=$resEnd['features'][0]['geometry']['coordinates'];

        return $this->calculateDistance($coordinatesStart, $coordinatesEnd);
    }
}
