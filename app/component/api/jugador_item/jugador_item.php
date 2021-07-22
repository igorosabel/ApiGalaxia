<?php
use OsumiFramework\OFW\Tools\OTools;

if (is_null($values['jugador'])) {
  echo 'null';
}
else {
?>
{
  "id": <?php echo $values['jugador']->get('id') ?>,
	"nombre": "<?php echo urlencode($values['jugador']->get('nombre')) ?>",
	"raza": <?php echo OTools::getComponent('api/raza_item', [ 'raza' => $values['jugador']->getRaza() ]) ?>,
	"alianza": <?php echo OTools::getComponent('api/alianza_item', [ 'alianza' => $values['jugador']->getAlianza() ]) ?>
}
<?php
}
?>
