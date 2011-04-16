<?php
interface ItemRenderer
{
	/**
	 * Renders an object
	 * 
	 * @param object $object
	 * @return ItemRenderer
	 */
	public function render($object);
	
	/**
	 * Returns the item
	 * 
	 * @return object
	 */
	public function getItem();
}