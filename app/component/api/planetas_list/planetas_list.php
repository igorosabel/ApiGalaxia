<?php
use OsumiFramework\OFW\Tools\OTools;

foreach ($values['list'] as $i => $planeta) {
  echo OTools::getComponent('api/planeta_item', [ 'planeta' => $planeta ]);
  if ($i<count($values['list'])-1) {
    echo ",\n";
  }
}
