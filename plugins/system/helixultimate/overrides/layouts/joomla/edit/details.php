<?php
/**
 * @package Helix Ultimate Framework
 * @author JoomShaper https://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2021 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
*/

defined ('JPATH_BASE') or die();

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Language\Text;

$app = Factory::getApplication();

// JLayout for standard handling of the details sidebar in administrator edit screens.
$title = $displayData->getForm()->getValue('title');
$published = $displayData->getForm()->getField('published');
$saveHistory = $displayData->get('state')->get('params')->get('save_history', 0);
?>
<div class="col-lg-2">
	<h4><?php echo Text::_('JDETAILS'); ?></h4>
	<hr>
	<fieldset class="form-vertical">
		<?php if (empty($title)) : ?>
			<div class="control-group">
				<div class="controls">
					<?php echo $displayData->getForm()->getValue('name'); ?>
				</div>
			</div>
		<?php else : ?>
			<div class="control-group">
				<div class="controls">
					<?php echo $displayData->getForm()->getValue('title'); ?>
				</div>
			</div>
		<?php endif; ?>

		<?php if ($published) : ?>
			<?php echo $displayData->getForm()->renderField('published'); ?>
		<?php else : ?>
			<?php echo $displayData->getForm()->renderField('state'); ?>
		<?php endif; ?>

		<?php echo $displayData->getForm()->renderField('access'); ?>
		<?php echo $displayData->getForm()->renderField('featured'); ?>
		<?php if (Multilanguage::isEnabled()) : ?>
			<?php echo $displayData->getForm()->renderField('language'); ?>
		<?php else : ?>
			<input type="hidden" id="jform_language" name="jform[language]" value="<?php echo $displayData->getForm()->getValue('language'); ?>">
		<?php endif; ?>

		<?php echo $displayData->getForm()->renderField('tags'); ?>
		<?php if ($saveHistory) : ?>
			<?php echo $displayData->getForm()->renderField('version_note'); ?>
		<?php endif; ?>
	</fieldset>
</div>
