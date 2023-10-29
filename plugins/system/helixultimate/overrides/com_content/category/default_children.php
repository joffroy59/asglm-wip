<?php
/**
 * @package Helix Ultimate Framework
 * @author JoomShaper https://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2021 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
*/

defined ('_JEXEC') or die();

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

$class  = ' class="first"';
$lang   = Factory::getLanguage();
$user   = Factory::getUser();
$groups = $user->getAuthorisedViewLevels();
?>

<?php if (count($this->children[$this->category->id]) > 0) : ?>
	<?php foreach ($this->children[$this->category->id] as $id => $child) : ?>
		<?php // Check whether category access level allows access to subcategories. ?>
		<?php if (in_array($child->access, $groups)) : ?>
			<?php
			if ($this->params->get('show_empty_categories') || $child->getNumItems(true) || count($child->getChildren())) :
				if (!isset($this->children[$this->category->id][$id + 1])) :
					$class = ' class="last"';
				endif;
			?>

			<div<?php echo $class; ?>>
				<?php $class = ''; ?>
				<?php if ($lang->isRtl()) : ?>
				<h3 class="page-header item-title">
					<?php if ( $this->params->get('show_cat_num_articles', 1)) : ?>
						<span class="badge bg-info tip hasTooltip" title="<?php echo HTMLHelper::_('tooltipText', 'COM_CONTENT_NUM_ITEMS_TIP'); ?>">
							<?php echo $child->getNumItems(true); ?>
						</span>
					<?php endif; ?>
					<a href="<?php echo Route::_(ContentHelperRoute::getCategoryRoute($child->id)); ?>">
					<?php echo $this->escape($child->title); ?></a>

					<?php if (count($child->getChildren()) > 0 && $this->maxLevel > 1) : ?>
						<a href="#category-<?php echo $child->id; ?>" data-bs-toggle="collapse" data-bs-toggle="button" class="btn btn-xs float-end" aria-label="<?php echo Text::_('JGLOBAL_EXPAND_CATEGORIES'); ?>"><span class="icon-plus" aria-hidden="true"></span></a>
					<?php endif; ?>
				</h3>
				<?php else : ?>
				<h3 class="page-header item-title"><a href="<?php echo Route::_(ContentHelperRoute::getCategoryRoute($child->id)); ?>">
					<?php echo $this->escape($child->title); ?></a>
					<?php if ( $this->params->get('show_cat_num_articles', 1)) : ?>
						<span class="badge bg-info tip hasTooltip" title="<?php echo HTMLHelper::_('tooltipText', 'COM_CONTENT_NUM_ITEMS_TIP'); ?>">
							<?php echo $child->getNumItems(true); ?>
						</span>
					<?php endif; ?>

					<?php if (count($child->getChildren()) > 0 && $this->maxLevel > 1) : ?>
						<a href="#category-<?php echo $child->id; ?>" data-bs-toggle="collapse" data-bs-toggle="button" class="btn btn-xs float-end" aria-label="<?php echo Text::_('JGLOBAL_EXPAND_CATEGORIES'); ?>"><span class="icon-plus" aria-hidden="true"></span></a>
					<?php endif; ?>
				</h3>
				<?php endif; ?>
				<?php if ($this->params->get('show_subcat_desc') == 1) : ?>
					<?php if ($child->description) : ?>
						<div class="category-desc">
							<?php echo HTMLHelper::_('content.prepare', $child->description, '', 'com_content.category'); ?>
						</div>
					<?php endif; ?>
				<?php endif; ?>

				<?php if (count($child->getChildren()) > 0 && $this->maxLevel > 1) : ?>
				<div class="collapse fade" id="category-<?php echo $child->id; ?>">
					<?php
					$this->children[$child->id] = $child->getChildren();
					$this->category = $child;
					$this->maxLevel--;
					echo $this->loadTemplate('children');
					$this->category = $child->getParent();
					$this->maxLevel++;
					?>
				</div>
				<?php endif; ?>
			</div>
			<?php endif; ?>
		<?php endif; ?>
	<?php endforeach; ?>
<?php endif; ?>
