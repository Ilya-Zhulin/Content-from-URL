<?php

/**
 * @version 			SEBLOD 3.x Core
 * @package			SEBLOD (App Builder & CCK) // SEBLOD nano (Form Builder)
 * @url				http://www.seblod.com
 * @editor			Ilya A.Zhulin https://zhulinia.ru
 * @copyright		Copyright (C) 2013-2023 Ilya A.Zhulin. All Rights Reserved.
 * @license 			GNU General Public License version 2 or later; see _LICENSE.php
 * */
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;

JLoader::register('CCK_Content', JPATH_PLATFORM . '/cck/content/content.php');

// Plugin
class plgCCK_Field_LiveContent_From_Url extends JCckPluginLive {

	protected static $type = 'content_from_url';

	// -------- -------- -------- -------- -------- -------- -------- -------- // Prepare
	// onCCK_Field_LivePrepareForm
	public function onCCK_Field_LivePrepareForm(&$field, &$value = '', &$config = array(), $inherit = array()) {
		if (self::$type != $field->live) {
			return;
		}

		// Init
		$app			 = Factory::getApplication();
		$input			 = $app->input;
		$live			 = '';
		$options		 = parent::g_getLive($field->live_options);
		$id				 = '';
		// Prepare
		$variable		 = trim($options->get('variable', $field->name));
		$vartype		 = $options->get('type', 'string');
		$item_number	 = (int) trim($options->get('item_number', '0'));
		$fieldname		 = trim($options->get('field_name', $field->name));
		$Xfield_number	 = (int) trim($options->get('xfield_number', '0'));
		$Xfieldname		 = trim($options->get('xfield_name', ''));

		// Совместимость с предыдущими версиями плагина
		$var_ar = explode(',', $variable);
		if (count($var_ar) > 1) {
			$variable	 = trim($var_ar[0]);
			$fieldname	 = trim($var_ar[1]);
		}
		////////////////////////////////////////

		$id		 = $input->getVar($variable, '', $vartype);
		if ($vartype = 'array' && is_array($id)) {
			$id = $id[$item_number];
		}
		$source_field = JCckDevField::getObject($fieldname);
		if ($source_field !== FALSE && strlen($id) > 0) {
			$live = JCckDatabase::loadResult('select ' . $source_field->storage_field . ' from ' . $source_field->storage_table . ' where id=' . $id);
		}
		$source_field->value = $live;
		switch ($source_field->storage) {
			case 'custom':
				switch ($source_field->type) {
					case 'field_x':
						$tmp = CCK_Content::getValues($live, $source_field->extended);
						if (isset($tmp[$source_field->extended . '|' . $Xfield_number . '|' . $source_field->name])) {
							$live = $tmp[$source_field->extended . '|' . $Xfield_number . '|' . $source_field->name];
						}
						break;
					case 'group_x':
						$tmp = CCK_Content::getValues($live, $source_field->extended);
						if (isset($tmp[$Xfieldname . '|' . $Xfield_number . '|' . $source_field->name])) {
							$live = $tmp[$Xfieldname . '|' . $Xfield_number . '|' . $source_field->name];
						}
						break;
					default:
						if ($source_field->storage_field2) {
							$live = CCK_Content::getValue($live, $source_field->storage_field2);
						} else {
							$app->enqueueMessage("<h4>" . self::$type . "</h4><p><b>Wrong storage for {$source_field->name}</b> field. Specify like <b>`{$source_field->name}[internal_fieldname]`</b></p>", 'error');
						}
						break;
				}
				break;
			case 'json':
				$tmp = JCckDev::fromJSON($live);
				if (isset($tmp[$source_field->storage_field2])) {
					$live = $tmp[$source_field->storage_field2];
				}
				break;
			default:
				break;
		}
// Set
		$value = $live;
	}
}

?>