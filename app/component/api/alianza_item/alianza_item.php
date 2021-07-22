<?php if (is_null($values['alianza'])): ?>
null
<?php else: ?>
{
  "id": <?php echo $values['alianza']->get('id') ?>,
	"nombre": "<?php echo urlencode($values['alianza']->get('nombre')) ?>"
}
<?php endif ?>
