<?php

/**
 * Joomla! Content Management System
 *
 * @copyright  (C) 2009 Open Source Matters, Inc. <https://www.joomla.org>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\CMS\Form\Field;

use Joomla\CMS\Application\ApplicationHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

// phpcs:disable PSR1.Files.SideEffects
\defined('JPATH_PLATFORM') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Module Position field.
 *
 * @since  1.6
 */
class ModulepositionField extends TextField
{
    /**
     * The form field type.
     *
     * @var    string
     * @since  1.6
     */
    protected $type = 'ModulePosition';

    /**
     * The client ID.
     *
     * @var    integer
     * @since  3.2
     */
    protected $clientId;

    /**
     * Method to get certain otherwise inaccessible properties from the form field object.
     *
     * @param   string  $name  The property name for which to get the value.
     *
     * @return  mixed  The property value or null.
     *
     * @since   3.2
     */
    public function __get($name)
    {
        if ($name === 'clientId') {
            return $this->clientId;
        }

        return parent::__get($name);
    }

    /**
     * Method to set certain otherwise inaccessible properties of the form field object.
     *
     * @param   string  $name   The property name for which to set the value.
     * @param   mixed   $value  The value of the property.
     *
     * @return  void
     *
     * @since   3.2
     */
    public function __set($name, $value)
    {
        switch ($name) {
            case 'clientId':
                $this->clientId = (int) $value;
                break;

            default:
                parent::__set($name, $value);
        }
    }

    /**
     * Method to attach a Form object to the field.
     *
     * @param   \SimpleXMLElement  $element  The SimpleXMLElement object representing the `<field>` tag for the form field object.
     * @param   mixed              $value    The form field value to validate.
     * @param   string             $group    The field name group control value. This acts as an array container for the field.
     *                                       For example if the field has name="foo" and the group value is set to "bar" then the
     *                                       full field name would end up being "bar[foo]".
     *
     * @return  boolean  True on success.
     *
     * @see     FormField::setup()
     * @since   3.2
     */
    public function setup(\SimpleXMLElement $element, $value, $group = null)
    {
        $result = parent::setup($element, $value, $group);

        if ($result === true) {
            // Get the client id.
            $clientId = $this->element['client_id'];

            if (!isset($clientId)) {
                $clientName = $this->element['client'];

                if (isset($clientName)) {
                    $client   = ApplicationHelper::getClientInfo($clientName, true);
                    $clientId = $client->id;
                }
            }

            if (!isset($clientId) && $this->form instanceof Form) {
                $clientId = $this->form->getValue('client_id');
            }

            $this->clientId = (int) $clientId;
        }

        return $result;
    }

    /**
     * Method to get the field input markup.
     *
     * @return  string  The field input markup.
     *
     * @since   1.6
     */
    protected function getInput()
    {
        // Build the script.
        $script   = [];
        $script[] = '	function jSelectPosition_' . $this->id . '(name) {';
        $script[] = '		document.getElementById("' . $this->id . '").value = name;';
        $script[] = '		jModalClose();';
        $script[] = '	}';

        // Add the script to the document head.
        Factory::getDocument()->addScriptDeclaration(implode("\n", $script));

        // Setup variables for display.
        $html = [];
        $link = 'index.php?option=com_modules&view=positions&layout=modal&tmpl=component&function=jSelectPosition_' . $this->id
            . '&amp;client_id=' . $this->clientId;

        // The current user display field.
        $html[] = '<div class="input-append">';
        $html[] = parent::getInput()
            . '<a class="btn" title="' . Text::_('COM_MODULES_CHANGE_POSITION_TITLE') . '" href="' . $link
            . '" data-bs-toggle="modal" data-bs-target="#modulePositionModal">'
            . Text::_('COM_MODULES_CHANGE_POSITION_BUTTON') . '</a>';

        $html[] = HTMLHelper::_(
            'bootstrap.renderModal',
            'modulePositionModal',
            [
                'url'        => $link,
                'title'      => Text::_('COM_MODULES_CHANGE_POSITION_BUTTON'),
                'height'     => '100%',
                'width'      => '100%',
                'modalWidth' => '800',
                'bodyHeight' => '450',
                'footer'     => '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-hidden="true">'
                    . Text::_('JLIB_HTML_BEHAVIOR_CLOSE') . '</button>',
            ]
        );
        $html[] = '</div>';

        return implode("\n", $html);
    }
}
