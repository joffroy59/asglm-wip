<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\MVC\Model\ListModel;

/**
 * Icon Model for managing the custom icons.
 * 
 * @version 4.1.0
 */
class SppagebuilderModelIcon extends ListModel
{
	/**
	 *  Columns array of #__sppagebuilder_assets table.
	 * 
	 * @var array
	 * @version 4.1.0
	 */
	private $columns = [];

	/**
	 * This is the __construct function.
	 * 
	 * @param mixed $config
	 * @version 4.1.0
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
		$this->columns = ['id', 'type', 'name', 'title', 'assets', 'css_path', 'published', 'access', 'created', 'created_by'];
	}

	/**
	 * Method to get an array of the result set rows from the database query where each row is an object. The array
	 * of objects can optionally be keyed by a field name, but defaults to a sequential numeric array.
	 * 
	 * @return mixed The return value or null if the query failed.
	 * @version 4.1.0
	 */
	public function getAllIcons($status = null)
	{
		$db 	= Factory::getDbo();
		$query 	= $db->getQuery(true);

		$query->select($db->quoteName($this->columns))
			->from($db->quoteName('#__sppagebuilder_assets'));
	
		if (isset($status))
		{
			$query->where($db->quoteName('published') . ' = ' . $db->quote($status));
		}
		else
		{
			$query->where($db->quoteName('published') . ' IN (0, 1)');
		}

		$db->setQuery($query);

		try
		{
			$result = $db->loadObjectList();
		}
		catch (\Exception $e)
		{
			return [];
		}

		if (!empty($result))
		{
			foreach ($result as &$icon)
			{
				$path = 'components/com_sppagebuilder/assets/images/customIcons/' . $icon->name . '.jpg';

				$rootPath = str_replace("\\", "/", JPATH_ROOT . '/' . $path);

				if (\file_exists($rootPath))
				{
					$icon->thumb = Uri::root() . '/' . $path;
				}
				else
				{
					$icon->thumb = Uri::root() . '/components/com_sppagebuilder/assets/images/customIcons/default.jpg';
				}

				if (!empty($icon->assets) && \is_string($icon->assets))
				{
					$icon->assets = \json_decode($icon->assets);
				}
			}

			unset($icon);
		}

		return $result;
	}

	/**
	 * Summary of insertAsset
	 * @param mixed $data
	 * @return mixed
	 * @version 4.1.0
	 */
	public function insertAsset($data)
	{
		$dataObject = (object) $data;
		$dataObject->id = null;

		$db = Factory::getDbo();

		try
		{
			if (($icon = $this->getAssetByName($dataObject->name)))
			{
				$dataObject->id = $icon->id;
				$result = $db->updateObject('#__sppagebuilder_assets', $dataObject, 'id', true);
			}
			else
			{
				$result = $db->insertObject('#__sppagebuilder_assets', $dataObject, 'id');
			}

			$result = $this->getAssetByName($dataObject->name);
			$path = 'components/com_sppagebuilder/assets/images/customIcons/' . $result->name . '.jpg';

			if (\file_exists(JPATH_ROOT . '/' . $path))
			{
				$result->thumb = Uri::root() . '/' . $path;
			}
			else
			{
				$result->thumb = Uri::root() . '/components/com_sppagebuilder/assets/images/customIcons/default.jpg';
			}

			return $result;
		}
		catch (Exception $e)
		{
			return false;
		}
	}

	/**
	 * Summary of getAssetByName
	 * @param string $name
	 * @return mixed
	 * @version 4.1.0
	 */
	public function getAssetByName(string $name)
	{
		$db 	= Factory::getDbo();
		$query 	= $db->getQuery(true);
		$query->select('*')->from($db->quoteName('#__sppagebuilder_assets'))
			->where($db->quoteName('name') . ' = ' . $db->quote($name));
		$db->setQuery($query);

		return $db->loadObject();
	}

/**
	 * Delete custom icon by ID.
	 *
	 * @param	int		$id		The icon id to remove.
	 *
	 * @return	bool	True on success, false otherwise.
	 * @since	4.1.0
	 */
	public function deleteCustomIcon(int $id) : bool
	{
		$asset = $this->getAssetById($id);
		$assetName = isset($asset->name) ? $asset->name : '';
		$assetPath = JPATH_ROOT . '/media/com_sppagebuilder/assets/iconfont/' . $assetName;

		$db 	= Factory::getDbo();
		$query 	= $db->getQuery(true);
		$query->delete($db->quoteName('#__sppagebuilder_assets'))
			->where($db->quoteName('id') . ' = ' . $id);
		$db->setQuery($query);

		if (Folder::exists($assetPath))
		{
			Folder::delete($assetPath);
		}

		try
		{
			return $db->execute() !== false;
		}
		catch (\Exception $e)
		{
			return false;
		}
	}

	public function getAssetById(int $id)
	{
		$db 	= Factory::getDbo();
		$query 	= $db->getQuery(true);
		$query->select('*')->from($db->quoteName('#__sppagebuilder_assets'))
			->where($db->quoteName('id') . ' = ' . $db->quote($id));
		$db->setQuery($query);

		return $db->loadObject();
	}

    /**
	 * Change the status of the custom icon's status.
	 *
	 * @param	int		$id		The icon id.
	 * @param	int		$status	The updating status.
	 *
	 * @return	void
	 * @since	4.1.0
	 */
	public function changeCustomIconStatus(int $id, int $status) : bool
	{
		$db 	= Factory::getDbo();
		$query 	= $db->getQuery(true);

		$query->update($db->quoteName('#__sppagebuilder_assets'))
			->set($db->quoteName('published') . ' = ' . $status)
			->where($db->quoteName('id') . ' = ' . $id);

		$db->setQuery($query);

		try
		{
			return $db->execute() !== false;
		}
		catch (\Exception $e)
		{
			return false;
		}
	}

	public function getIconList($name)
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->quoteName('assets'));
		$query->from($db->quoteName('#__sppagebuilder_assets'));
		$query->where($db->quoteName('name').' = '. $db->quote($name));
		$db->setQuery($query);

		$result = $db->loadResult();
		return $result;
	}

	public function getAssetProviders()
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$query->select('DISTINCT ' . $db->quoteName('name') . ', title');
		$query->from($db->quoteName('#__sppagebuilder_assets'));
		$query->where($db->quoteName('published') . ' = 1');
		
		$db->setQuery($query);

		$result = $db->loadObjectList();
		return $result;
	}
}
