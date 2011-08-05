<?php
namespace Lcobucci\DisplayObjects\Components;

use Lcobucci\DisplayObjects\Core\ItemRenderer;
use Lcobucci\DisplayObjects\Core\UIComponent;

class SimpleList extends UIComponent
{
	/**
	 * @var string
	 */
	protected $id;

	/**
	 * @var array
	 */
	protected $dataProvider;

	/**
	 * @var Lcobucci\DisplayObjects\Core\ItemRenderer
	 */
	protected $renderer;

	/**
	 * @param string $id
	 * @param array $dataProvider
	 * @param ItemRenderer $renderer
	 */
	public function __construct($id, array $dataProvider, ItemRenderer $renderer)
	{
		$this->setId($id);
		$this->setDataProvider($dataProvider);
		$this->setRenderer($renderer);
	}

	/**
	 * @return string
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param string $id
	 */
	public function setId($id)
	{
		$this->id = $id;
	}

	/**
	 * @return array
	 */
	protected function getDataProvider()
	{
		return $this->dataProvider;
	}

	/**
	 * @param array $dataProvider
	 */
	protected function setDataProvider(array $dataProvider)
	{
		$this->dataProvider = $dataProvider;
	}

	/**
	 * @return Lcobucci\DisplayObjects\Core\ItemRenderer
	 */
	protected function getRenderer()
	{
		return $this->renderer;
	}

	/**
	 * @param Lcobucci\DisplayObjects\Core\ItemRenderer $renderer
	 */
	protected function setRenderer(ItemRenderer $renderer)
	{
		$this->renderer = $renderer;
	}
}