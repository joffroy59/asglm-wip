<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Http\Http;

/** No direct access */
defined('_JEXEC') or die('Restricted access');

final class ApplicationHelper
{
	public static function generateSiteClassName($addonName)
	{
		if (empty($addonName))
		{
			return '';
		}

		if (stripos($addonName, 'easystore_') === 0)
		{
			$parts = explode('_', $addonName);
			$parts = array_map(function ($part)
			{
				return ucfirst($part);
			}, $parts);

			return 'SppagebuilderAddon' . implode('', $parts);
		}

		return 'SppagebuilderAddon' . ucfirst($addonName);
	}

	public static function hasPageContent(int $id)
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);

		$query->select('content')
			->from($db->quoteName('#__sppagebuilder'))
			->where($db->quoteName('id') . ' = ' . $id);

		$db->setQuery($query);

		try
		{
			$result = $db->loadResult();

			if (\is_string($result) && !empty($result))
			{
				$result = \json_decode($result);
			}

			return !empty($result);
		}
		catch (\Exception $e)
		{
			return false;
		}
	}

	public static function sanitizePageText($text)
	{
		$text = $text ?? '[]';
		$text = !\is_string($text) ? json_encode($text) : $text;
		$parsed = SpPageBuilderAddonHelper::__($text);
		$parsed = SppagebuilderHelperSite::sanitize($parsed);

		return json_decode($parsed);
	}

	public static function preparePageData($pageData)
	{
		if (empty($pageData))
		{
			return (object) [
				'text' => new stdClass
			];
		}

		$content = [];

		if (is_null($pageData->content))
		{
			$pageData->text = SppagebuilderHelperSite::prepareSpacingData($pageData->text);
			$pageData->text = self::sanitizePageText($pageData->text);

			return $pageData;
		}

		if (\is_string($pageData->content))
		{
			$content = \json_decode($pageData->content);
		}

		if (is_null($content))
		{
			$pageData->text = SppagebuilderHelperSite::prepareSpacingData($pageData->text);
			$pageData->text = self::sanitizePageText($pageData->text);

			return $pageData;
		}

		$version = SppagebuilderHelper::getVersion();
		$storedVersion = $pageData->version;
		$pageData->text = $content;
		$pageData->text = SppagebuilderHelperSite::prepareSpacingData($pageData->text);
		$pageData->text = json_decode($pageData->text);

		if ($version !== $storedVersion)
		{
			$pageData->text = self::sanitizePageText(json_encode($pageData->text));
		}


		return $pageData;
	}

	public static function isEasyStoreInstalled()
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);

		$query->select('enabled')
			->from($db->quoteName('#__extensions'))
			->where($db->quoteName('name') . ' = ' . $db->quote('com_easystore'));

		$db->setQuery($query);

		try
		{
			return (int) $db->loadResult() === 1;
		}
		catch (Exception $e)
		{
			return false;
		}

		return false;
	}

	public static function getStorePageId($viewType)
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);

		$query->select('id')
			->from($db->quoteName('#__sppagebuilder'))
			->where($db->quoteName('extension') . ' = ' . $db->quote('com_easystore'))
			->where($db->quoteName('extension_view') . ' = ' . $db->quote($viewType));

		$db->setQuery($query);

		try
		{
			return $db->loadResult() ?? 0;
		}
		catch (Exception $error)
		{
			return 0;
		}

		return 0;
	}

	public static function isProVersion()
	{
		// $http = new Http();
		// $params = ComponentHelper::getParams('com_sppagebuilder');
		
		// $email = $params->get('joomshaper_email', '');
		// $apiKey = $params->get('joomshaper_license_key', '');

		// $args = '&email=' . $email . '&api_key=' . $apiKey;
		// $apiURL = 'https://www.joomshaper.com/index.php?option=com_layouts&task=block.list&support=4beyond' . $args;

		// $pageResponse = $http->get($apiURL);
		// if ($pageResponse->code !== 200)
		// {
		// 	return false;
		// }

		// $responseData = $pageResponse->body;

		// if (!empty($responseData))
		// {
		// 	try {
		// 		$responseData = json_decode($responseData);
		// 		if (!$responseData->authorised)
		// 		{
		// 			return false;
		// 		}
		// 	}
		// 	catch(Exception $e)
		// 	{
		// 		return false;
		// 	}
		// }

		return true;
	}
}
