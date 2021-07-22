<?php if (is_null($values['raza'])): ?>
null
<?php else: ?>
{
  "id": <?php echo $values['raza']->get('id') ?>,
	"nombre": "<?php echo urlencode($values['raza']->get('nombre')) ?>"
}
<?php endif ?>
