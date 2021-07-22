<?php
use OsumiFramework\OFW\Tools\OTools;

foreach ($values['list'] as $i => $alianza) {
  echo OTools::getComponent('api/alianza_item', [ 'alianza' => $alianza ]);
  if ($i<count($values['list'])-1) {
    echo ",\n";
  }
}
