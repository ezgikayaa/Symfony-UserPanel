<?php

namespace TowerUIX\Kernel\Contracts;

interface AppInterface{

    public static function ConfigLoader(array $LoadConfigs);

    public function View($Parameter,$Data);

}