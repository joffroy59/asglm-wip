<?php

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

/**
 * Sample trait for managing API endpoints.
 */
trait ProductPageContentById
{
	public function productPageContentById()
	{
		$method = $this->getInputMethod();
		$this->checkNotAllowedMethods(['POST', 'PUT', 'DELETE', 'PATCH'], $method);

		$this->getProductPageContentById();
	}

	public function getProductPageContentById()
	{
		$id = $this->getInput('id', null, 'STRING');
		$model = $this->getModel('Editor');

		if (!$id)
		{
			$response['message'] = 'Missing Page ID';
			$this->sendResponse($response, 400);
		}

		$content = $model->getProductPageContent('com_easystore', $id === 'product-list' ? 'list' : 'single');

		if (empty($content))
		{
			$pageId = $this->createProductPage($id === 'product-list' ? 'list' : 'single');
			$content = $model->getPageContent($pageId);
		}

		$content = ApplicationHelper::preparePageData($content);

		$content->url = SppagebuilderHelperRoute::getFormRoute($content->id, $content->language);
		unset($content->content);

		$this->sendResponse($content);
	}

	public function createProductPage(string $product_type)
	{
		$title = $product_type === 'list' ? "Product List" : "Single Product";

		$model = $this->getModel('Editor');
		$data = [];
		$user = Factory::getUser();
		$version = SppagebuilderHelper::getVersion();

		$user = Factory::getUser();
		$canCreate = $user->authorise('core.create', 'com_sppagebuilder');

		if (!$canCreate) {
			$this->sendResponse([
				'message' => Text::_('COM_SPPAGEBUILDER_EDITOR_INVALID_CREATE_ACCESS')
			], 403);
		}

		$data = (object) [
			'id' => 0,
			'title' => $title,
			'text' => '[]',
			'css' => '',
			'catid' => 0,
			'language' => '*',
			'access' => 1,
			'published' => 1,
			'extension' => 'com_easystore',
			'extension_view' => $product_type === 'list' ? 'list' : 'single',
			'created_on' => Factory::getDate()->toSql(),
			'created_by' => $user->id,
			'modified' => Factory::getDate()->toSql(),
			'version' => $version,
		];

		return $model->createPage($data);
	}
}
