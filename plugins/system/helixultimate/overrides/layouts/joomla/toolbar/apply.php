<?php
/**
 * @package Helix Ultimate Framework
 * @author JoomShaper https://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2021 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
*/

defined('JPATH_BASE') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;

HTMLHelper::_('behavior.core');

if (preg_match('/Joomla.submitbutton/', $displayData['doTask']))
{
	$ctrls = str_replace("Joomla.submitbutton('", '', $displayData['doTask']);
	$ctrls = str_replace("')", '', $ctrls);
	$ctrls = str_replace(";", '', $ctrls);

	$options = array('task' => $ctrls);
	Factory::getDocument()->addScriptOptions('keySave', $options);
}

$id       = isset($displayData['id']) ? $displayData['id'] : '';
$doTask   = isset($displayData['onclick']) ? $displayData['onclick'] : $displayData['doTask'];
$class    = $displayData['class'];
$text     = $displayData['text'];
$btnClass = $displayData['btnClass'];
?>
<button id="<?php echo $id; ?>" onclick="<?php echo $doTask; ?>" class="<?php echo $btnClass; ?>">
	<span class="<?php echo trim($class); ?>"></span>
	<?php echo $text; ?>
</button>
