<?php
/**
 * @version 			SEBLOD 3.x Core
 * @package			SEBLOD (App Builder & CCK) // SEBLOD nano (Form Builder)
 * @url				http://www.seblod.com
 * @editor			Octopoos - www.octopoos.com
 * @copyright		Copyright (C) 2013 SEBLOD. All Rights Reserved.
 * @license 			GNU General Public License version 2 or later; see _LICENSE.php
 * */
defined('_JEXEC') or die;
JCckDev::initScript('live', $this->item);
?>

<div class="seblod">
	<?php echo JCckDev::renderLegend(JText::_('COM_CCK_CONSTRUCTION'), JText::_('PLG_CCK_FIELD_LIVE_' . $this->item->name . '_DESC')); ?>
    <ul class="adminformlist adminformlist-2cols">
		<?php
		echo JCckDev::renderForm('core_dev_text', '', $config, array('label' => 'Variable', 'storage_field' => 'variable', 'required' => 'required', 'defaultvalue' => 'id'));
		echo JCckDev::renderForm('core_dev_text', '', $config, ['label' => 'Item number', 'storage_field' => 'item_number', 'defaultvalue' => '0']);
		echo JCckDev::renderForm('core_variable_type', '', $config, ['required' => 'required', 'defaultvalue' => 'string']);
		echo JCckDev::renderForm('core_dev_text', '', $config, ['required' => 'required', 'label' => 'Field Name', 'storage_field' => 'field_name', 'defaultvalue' => 'art_title']);
		echo JCckDev::renderForm('core_dev_text', '', $config, ['label' => 'X Field number', 'storage_field' => 'xfield_number', 'defaultvalue' => '0']);
		echo JCckDev::renderForm('core_dev_text', '', $config, ['label' => 'X Field name', 'storage_field' => 'xfield_name', 'defaultvalue' => '']);
		?>
    </ul>
</div>

<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('#item_number').isVisibleWhen('type', 'array');
    });
</script>
