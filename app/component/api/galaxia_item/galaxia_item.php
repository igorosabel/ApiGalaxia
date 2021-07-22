<?php if (is_null($values['galaxia'])): ?>
null
<?php else: ?>
{
  "id": <?php echo $values['galaxia']->get('id') ?>,
	"nombre": "<?php echo urlencode($values['galaxia']->get('nombre')) ?>",
	"sectores": <?php echo $values['galaxia']->get('sectores') ?>,
	"cuadrantes": <?php echo $values['galaxia']->get('cuadrantes') ?>,
	"planetas": <?php echo $values['galaxia']->get('planetas') ?>,
	"num": <?php echo $values['galaxia']->get('num') ?>,
	"investigados": <?php echo $values['galaxia']->get('investigados') ?>
}
<?php endif ?>
