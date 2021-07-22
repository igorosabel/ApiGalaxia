<?php
use OsumiFramework\OFW\Tools\OTools;

foreach ($values['list'] as $i => $jugador) {
  echo OTools::getComponent('api/jugador_item', [ 'jugador' => $jugador ]);
  if ($i<count($values['list'])-1) {
    echo ",\n";
  }
}
