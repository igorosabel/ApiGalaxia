<?php
use OsumiFramework\OFW\Tools\OTools;

foreach ($values['list'] as $i => $galaxia) {
  echo OTools::getComponent('api/galaxia_item', [ 'galaxia' => $galaxia ]);
  if ($i<count($values['list'])-1) {
    echo ",\n";
  }
}
