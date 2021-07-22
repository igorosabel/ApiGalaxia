<?php
use OsumiFramework\OFW\Tools\OTools;

foreach ($values['list'] as $i => $raza) {
  echo OTools::getComponent('api/raza_item', [ 'raza' => $raza ]);
  if ($i<count($values['list'])-1) {
    echo ",\n";
  }
}
