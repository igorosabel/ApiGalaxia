<?php
use OsumiFramework\OFW\Tools\OTools;

foreach ($values['list'] as $i => $especial) {
  echo OTools::getComponent('api/especial_item', [ 'especial' => $especial ]);
  if ($i<count($values['list'])-1) {
	echo ",\n";
  }
}
