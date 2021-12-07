<?php
namespace App\Support\Session;

interface DeviceTypeContract
{
    public function backgroundClass():string;
    public function textClass():string;

}