<?php
namespace Lcobucci\DisplayObjects\Core;

abstract class ItemRenderer extends UIComponent
{
	/**
	 * Item to render
	 *
	 * @var object
	 */
	private $_item;

	/**
	 * Renders an object
	 *
	 * @param object $item
	 * @return ItemRenderer
	 */
	public function render($item)
	{
		$this->_item = $item;

		return $this;
	}

	/**
	 * Returns the item
	 *
	 * @return object
	 */
	public function getItem()
	{
		return $this->_item;
	}
}