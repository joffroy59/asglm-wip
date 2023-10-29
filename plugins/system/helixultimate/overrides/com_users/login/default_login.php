<?php
/**
 * @package Helix Ultimate Framework
 * @author JoomShaper https://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2021 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
*/

defined('_JEXEC') or die;

use HelixUltimate\Framework\Platform\Helper;
use HelixUltimate\Framework\Platform\Settings;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Router\Route;

HTMLHelper::_('behavior.keepalive');
HTMLHelper::_('behavior.formvalidator');

$usersConfig = ComponentHelper::getParams('com_users');

?>
<div class="login<?php echo $this->pageclass_sfx; ?>">
	<div class="row justify-content-center">
		<div class="col-lg-4">
			<?php if ($this->params->get('show_page_heading')) : ?>
				<div class="page-header">
					<h1>
						<?php echo $this->escape($this->params->get('page_heading')); ?>
					</h1>
				</div>
			<?php endif; ?>

			<?php if (($this->params->get('logindescription_show') == 1 && str_replace(' ', '', Helper::CheckNull($this->params->get('login_description'))) != '') || $this->params->get('login_image') != '') : ?>
				<div class="login-description">
				<?php endif; ?>

				<?php if ($this->params->get('logindescription_show') == 1) : ?>
					<?php echo $this->params->get('login_description'); ?>
				<?php endif; ?>

				<?php if ($this->params->get('login_image') != '') : ?>
					<img src="<?php echo $this->escape($this->params->get('login_image')); ?>" class="login-image" alt="<?php echo Text::_('COM_USERS_LOGIN_IMAGE_ALT'); ?>">
				<?php endif; ?>

				<?php if (($this->params->get('logindescription_show') == 1 && str_replace(' ', '', Helper::CheckNull($this->params->get('login_description'))) != '') || $this->params->get('login_image') != '') : ?>
				</div>
			<?php endif; ?>

			<form action="<?php echo Route::_('index.php?option=com_users&task=user.login'); ?>" method="post" class="form-validate" id="com-users-login__form">

				<?php foreach ($this->form->getFieldset('credentials') as $field) : ?>
					<?php
						$showon = $field->getAttribute('showon');
						$attribs = '';
						if ($showon) 
						{
							$attribs .= ' data-showon=\'' . json_encode(Settings::parseShowOnConditions($showon, $field->formControl)) . '\'';
						}
						// Enable disable on
						$enableOn = $field->getAttribute('enableon', '');
						if ($enableOn)
						{
							$attribs .= ' data-enableon="' . $enableOn . '"';
						}
					?>
					<?php if (!$field->hidden) : ?>
						<div class="mb-3" <?php echo $attribs; ?>>
							<?php echo $field->label; ?>
							<?php echo $field->input; ?>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>

				<?php if ($this->tfa) : ?>
					<div class="mb-3">
						<?php echo $this->form->getField('secretkey')->label; ?>
						<?php echo $this->form->getField('secretkey')->input; ?>
					</div>
				<?php endif; ?>

				<?php if (PluginHelper::isEnabled('system', 'remember')) : ?>
					<div class="form-check mb-3">
						<label class="form-check-label">
							<input class="form-check-input" type="checkbox" name="remember" id="remember" class="inputbox" value="yes">
							<?php echo Text::_('COM_USERS_LOGIN_REMEMBER_ME') ?>
						</label>
					</div>
				<?php endif; ?>

				<?php foreach ($this->extraButtons as $button) :
                $dataAttributeKeys = array_filter(array_keys($button), function ($key) {
                    return substr($key, 0, 5) == 'data-';
                });
                ?>
                <div class="com-users-login__submit control-group">
                    <div class="controls">
                        <button type="button"
                                class="btn btn-dark w-100 <?php echo $button['class'] ?? '' ?>"
                                <?php foreach ($dataAttributeKeys as $key) : ?>
                                    <?php echo $key ?>="<?php echo $button[$key] ?>"
                                <?php endforeach; ?>
                                <?php if ($button['onclick']) : ?>
                                onclick="<?php echo $button['onclick'] ?>"
                                <?php endif; ?>
                                title="<?php echo Text::_($button['label']) ?>"
                                id="<?php echo $button['id'] ?>"
                        >
                            <?php if (!empty($button['icon'])) : ?>
                                <span class="<?php echo $button['icon'] ?>"></span>
                            <?php elseif (!empty($button['image'])) : ?>
                                <?php echo HTMLHelper::_('image', $button['image'], Text::_($button['tooltip'] ?? ''), [
                                    'class' => 'icon',
                                ], true) ?>
                            <?php elseif (!empty($button['svg'])) : ?>
                                <?php echo $button['svg']; ?>
                            <?php endif; ?>
                            <?php echo Text::_($button['label']) ?>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>

				<div class="mb-3">
					<button type="submit" class="btn btn-primary btn-lg w-100">
						<?php echo Text::_('JLOGIN'); ?>
					</button>
				</div>

				<?php $return = $this->form->getValue('return', '', $this->params->get('login_redirect_url', $this->params->get('login_redirect_menuitem'))); ?>
				<input type="hidden" name="return" value="<?php echo base64_encode(Helper::CheckNull($return)); ?>">
				<?php echo HTMLHelper::_('form.token'); ?>
			</form>

			<div>
				<div class="list-group">
					<a class="list-group-item" href="<?php echo Route::_('index.php?option=com_users&view=reset'); ?>">
						<?php echo Text::_('COM_USERS_LOGIN_RESET'); ?>
					</a>
					<a class="list-group-item" href="<?php echo Route::_('index.php?option=com_users&view=remind'); ?>">
						<?php echo Text::_('COM_USERS_LOGIN_REMIND'); ?>
					</a>
					<?php if ($usersConfig->get('allowUserRegistration')) : ?>
						<a class="list-group-item" href="<?php echo Route::_('index.php?option=com_users&view=registration'); ?>">
							<?php echo Text::_('COM_USERS_LOGIN_REGISTER'); ?>
						</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>
