<?php
/**
 * @package Helix Ultimate Framework
 * @author JoomShaper https://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2021 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
*/

defined ('JPATH_BASE') or die();

use Joomla\CMS\Layout\LayoutHelper;

$intro = (isset($displayData['intro']) && $displayData['intro']) ? $displayData['intro'] : false;
$displayData['articleView'] = ($intro) ? 'intro' : 'details';
$blockPosition = $displayData['params']->get('info_block_position', 0);

?>
<div class="article-info">

	<?php if ($displayData['position'] === 'above' && ($blockPosition == 0 || $blockPosition == 2)
			|| $displayData['position'] === 'below' && ($blockPosition == 1)
			) : ?>

		<?php if ($displayData['params']->get('show_author') && !empty($displayData['item']->author )) : ?>
			<?php echo $this->sublayout('author', $displayData); ?>
		<?php endif; ?>

		<?php if ($displayData['params']->get('show_parent_category') && !empty($displayData['item']->parent_slug) && $intro == false) : ?>
			<?php echo $this->sublayout('parent_category', $displayData); ?>
		<?php endif; ?>

		<?php if ($displayData['params']->get('show_category')) : ?>
			<?php echo $this->sublayout('category', $displayData); ?>
		<?php endif; ?>

		<?php if ($displayData['params']->get('show_associations') && $intro == false) : ?>
			<?php echo $this->sublayout('associations', $displayData); ?>
		<?php endif; ?>

		<?php if ($displayData['params']->get('show_publish_date')) : ?>
			<?php echo $this->sublayout('publish_date', $displayData); ?>
		<?php endif; ?>
		
		<?php if ($intro) : ?>
			<?php echo LayoutHelper::render('joomla.content.blog.comments.count', $displayData); ?>
		<?php endif; ?>

	<?php endif; ?>

	<?php if ($displayData['position'] === 'above' && ($blockPosition == 0)
			|| $displayData['position'] === 'below' && ($blockPosition == 1 || $blockPosition == 2)
			) : ?>
		<?php if ($displayData['params']->get('show_create_date') && $intro == false) : ?>
			<?php echo $this->sublayout('create_date', $displayData); ?>
		<?php endif; ?>

		<?php if ($displayData['params']->get('show_modify_date') && $intro == false) : ?>
			<?php echo $this->sublayout('modify_date', $displayData); ?>
		<?php endif; ?>

		<?php if ($displayData['params']->get('show_hits') && $intro == false) : ?>
			<?php echo $this->sublayout('hits', $displayData); ?>
		<?php endif; ?>
	<?php endif; ?>
</div>
