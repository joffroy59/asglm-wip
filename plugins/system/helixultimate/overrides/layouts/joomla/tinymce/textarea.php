<?php
/**
 * @package Helix Ultimate Framework
 * @author JoomShaper https://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2021 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
*/

defined('JPATH_BASE') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;

$data = $displayData;

if (JVERSION < 4)
{
	$doc = Factory::getDocument();
	$doc->addStylesheet(Uri::root(true) . '/plugins/system/helixultimate/assets/css/icomoon.css');
}
else 
{
	$wa   = Factory::getDocument()->getWebAssetManager();

	if (!$wa->assetExists('script', 'tinymce'))
	{
		$wa->registerScript('tinymce', 'media/vendor/tinymce/tinymce.min.js', [], ['defer' => true]);
	}
	
	if (!$wa->assetExists('script', 'plg_editors_tinymce'))
	{
		$wa->registerScript('plg_editors_tinymce', 'plg_editors_tinymce/tinymce.min.js', [], ['defer' => true], ['core', 'tinymce']);
	}
	
	$wa->useScript('tinymce')->useScript('plg_editors_tinymce');
}

?>
<textarea
	name="<?php echo $data->name; ?>"
	id="<?php echo $data->id; ?>"
	cols="<?php echo $data->cols; ?>"
	rows="<?php echo $data->rows; ?>"
	style="width: <?php echo $data->width; ?>; height: <?php echo $data->height; ?>;"
	class="<?php echo empty($data->class) ? 'mce_editable form-control' : 'form-control ' . $data->class; ?>"
	<?php echo $data->readonly ? ' readonly disabled' : ''; ?>
>
	<?php echo $data->content; ?>
</textarea>
