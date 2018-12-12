<?php
/**
 * Created by PhpStorm.
 * User: patricia
 * Date: 11/12/18
 * Time: 11:23
 */

namespace App\Controller;

interface HasAddress
{
    public function fullAddress():string;
}
