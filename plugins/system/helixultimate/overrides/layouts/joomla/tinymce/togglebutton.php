<?php
/**
 * @package Helix Ultimate Framework
 * @author JoomShaper https://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2021 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
*/

defined('JPATH_BASE') or die;

use Joomla\CMS\Language\Text;
if (JVERSION >= 4)
{
	?>
	<div class="toggle-editor btn-toolbar float-end clearfix mt-3">
		<div class="btn-group">
			<button type="button" disabled class="btn btn-secondary js-tiny-toggler-button">
				<span class="icon-eye" aria-hidden="true"></span>
				<?php echo Text::_('PLG_TINY_BUTTON_TOGGLE_EDITOR'); ?>
			</button>
		</div>
	</div>
	<?php
}
else 
{
	$name = $displayData;
	?>
	<div class="toggle-editor btn-toolbar pull-right clearfix">
		<div class="btn-group">
			<a class="btn btn-secondary" href="#"
				onclick="tinyMCE.execCommand('mceToggleEditor', false, '<?php echo $name; ?>');return false;"
				title="<?php echo Text::_('PLG_TINY_BUTTON_TOGGLE_EDITOR'); ?>"
			>
				<span class="icon-eye" aria-hidden="true"></span> <?php echo Text::_('PLG_TINY_BUTTON_TOGGLE_EDITOR'); ?>
			</a>
		</div>
	</div>
	<?php 
	} 
?>
