<?php
/**
 * Created by PhpStorm.
 * User: patricia
 * Date: 11/12/18
 * Time: 11:20
 */

namespace App\Service;


use App\Entity\Association;
use App\Entity\Company;
use GuzzleHttp\Client;


class Distance
{
    public function calculateDistanceCompanyAsso(Company $company, Association $association): ?float
    {
        $client = new Client();

        $queryCompany = "https://api-adresse.data.gouv.fr/search/?q=" . $company->fullAddress();
        $queryAssociation = "https://api-adresse.data.gouv.fr/search/?q=" . $association->fullAddress();

        $resCompany = $client->request('GET', $queryCompany);
        $resAssociation = $client->request('GET', $queryAssociation);
        $resCompany = json_decode($resCompany->getBody(), true);
        $resAssociation = json_decode($resAssociation->getBody(), true);

        $coordinatesCompany=$resCompany['features'][0]['geometry']['coordinates'];
        $coordinatesAssociation=$resAssociation['features'][0]['geometry']['coordinates'];

        $earth_radius = 6378137;
        $rlo1 = deg2rad($coordinatesCompany[1]);
        $rla1 = deg2rad($coordinatesCompany[0]);
        $rlo2 = deg2rad($coordinatesAssociation[1]);
        $rla2 = deg2rad($coordinatesAssociation[0]);

        $dlo = ($rlo2 - $rlo1) / 2;
        $dla = ($rla2 - $rla1) / 2;
        $a = (sin($dla) * sin($dla)) + cos($rla1) * cos($rla2) * (sin($dlo) * sin($dlo
                ));
        $d = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return round($earth_radius * $d / 1000, 2);
    }
}
