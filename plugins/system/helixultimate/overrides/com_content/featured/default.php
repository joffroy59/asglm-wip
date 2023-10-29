<?php
/**
 * @package Helix Ultimate Framework
 * @author JoomShaper https://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2021 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
*/

defined ('_JEXEC') or die();

use HelixUltimate\Framework\Platform\Helper;
use Joomla\CMS\HTML\HTMLHelper;

HTMLHelper::addIncludePath(JPATH_COMPONENT . '/helpers');

// HTMLHelper::_('behavior.caption');

// If the page class is defined, add to class as suffix.
// It will be a separate class if the user starts it with a space
?>
<div class="container-fluid blog-featured<?php echo $this->pageclass_sfx; ?>" itemscope itemtype="https://schema.org/Blog">
<?php if ($this->params->get('show_page_heading') != 0) : ?>
	<div class="page-header">
		<h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
	</div>
<?php endif; ?>

<?php $leadingcount = 0; ?>
<?php if (!empty($this->lead_items)) : ?>
<div class="article-list">
	<div class="items-leading">
		<?php foreach ($this->lead_items as &$item) : ?>
			<div class="leading-<?php echo $leadingcount; ?>">
				<div class="article"
					itemprop="blogPost" itemscope itemtype="https://schema.org/BlogPosting">
					<?php
						$this->item = &$item;
						$this->item->leading = true;
						echo $this->loadTemplate('item');
					?>
				</div>
			</div>
			<?php
				$leadingcount++;
			?>
		<?php endforeach; ?>
	</div>
</div>
<?php endif; ?>

<?php
	$introcount = count($this->intro_items);
	$counter = 0;
	$this->columns = $this->columns ?? 1;
?>
<?php if (!empty($this->intro_items)) : ?>
	<?php $blogClass = $this->params->get('blog_class', ''); ?>
		<?php if ((int) $this->params->get('num_columns') > 1) : ?>
			<?php $blogClass .= 'cols-' . (int) $this->params->get('num_columns'); ?>	
		<?php endif; ?>
	<div class="article-list">
		<div class="row row-<?php echo $counter + 1; ?> <?php echo $blogClass; ?>">
			<?php foreach ($this->intro_items as $key => &$item) : ?>
				<div class="col-lg-<?php echo round(12 / Helper::SetColumn($this->params->get('num_columns'), 3)); ?>">
					<div class="article"
						itemprop="blogPost" itemscope itemtype="https://schema.org/BlogPosting">
						<?php
							$this->item = &$item;
							echo $this->loadTemplate('item');
						?>
					</div>
				</div>
			<?php $counter++; ?>
			<?php endforeach; ?>
		</div>
	</div>
<?php endif; ?>

<?php if (!empty($this->link_items)) : ?>
	<div class="articles-more mb-4">
		<?php echo $this->loadTemplate('links'); ?>
	</div>
<?php endif; ?>

<?php if ($this->params->def('show_pagination', 2) == 1  || ($this->params->get('show_pagination') == 2 && $this->pagination->pagesTotal > 1)) : ?>
	<nav class="pagination-wrapper d-lg-flex justify-content-between w-100">
		<?php echo $this->pagination->getPagesLinks(); ?>
		<?php if ($this->params->def('show_pagination_results', 1)) : ?>
			<div class="pagination-counter text-muted mb-4">
				<?php echo $this->pagination->getPagesCounter(); ?>
			</div>
		<?php endif; ?>
	</nav>
<?php endif; ?>
</div>
