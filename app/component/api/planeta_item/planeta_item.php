<?php
use OsumiFramework\OFW\Tools\OTools;

if (is_null($values['planeta'])) {
  echo 'null';
}
else {
?>
{
	"id": <?php echo $values['planeta']->get('id') ?>,
	"galaxia": <?php echo OTools::getComponent('api/galaxia_item', [ 'galaxia' => $values['planeta']->getGalaxia() ]) ?>,
	"sector": <?php echo $values['planeta']->get('sector') ?>,
	"cuadrante": <?php echo $values['planeta']->get('cuadrante') ?>,
	"ind": <?php echo $values['planeta']->get('ind') ?>,
	"nombre": "<?php echo urlencode($values['planeta']->get('nombre')) ?>",
	"valor": <?php echo $values['planeta']->get('valor') ?>,
	"jugador": <?php echo OTools::getComponent('api/jugador_item', [ 'jugador' => $values['planeta']->getJugador() ]) ?>,
	"especial": <?php echo OTools::getComponent('api/especial_item', [ 'especial' => $values['planeta']->getEspecial() ]) ?>,
	"protegido": <?php echo $values['planeta']->get('protegido') ? 'true' : 'false' ?>
	}
<?php
}
?>
