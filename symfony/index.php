<?php
  session_start();
  ob_start();
/**
 * Boilerplate - Toweruix Project Starter
 *
 * @package  TowerUIX
 * @author   toweruix <hello@toweruix.com>
 */



/*
|--------------------------------------------------------------------------
| Otomatik Yükleyici
|--------------------------------------------------------------------------
|
| Sistemin çalışması için gerekli olan tüm dosyaları dahil eder
|
*/

require_once "app/Bootstrap.php";

use TowerUIX\Kernel\Routing;

new Routing();
