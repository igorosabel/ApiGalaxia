<?php if (is_null($values['especial'])): ?>
null
<?php else: ?>
{
  "id": <?php echo $values['especial']->get('id') ?>,
	"nombre": <?php echo urlencode($values['especial']->get('nombre')) ?>
}
<?php endif ?>
