<?php
class UIList extends UIComponent
{
	/**
	 * @var array
	 */
	protected $dataProvider;
	
	/**
	 * @var ItemRenderer
	 */
	protected $renderer;
	
	/**
	 * @var string
	 */
	protected $id;
	
	public function __construct($id, array $dataProvider, ItemRenderer $renderer)
	{
		$this->setId($id);
		$this->setDataProvider($dataProvider);
		$this->setRenderer($renderer);
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
	 * @return ItemRenderer
	 */
	protected function getRenderer()
	{
		return $this->renderer;
	}

	/**
	 * @param ItemRenderer $renderer
	 */
	protected function setRenderer(ItemRenderer $renderer)
	{
		$this->renderer = $renderer;
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
	 * @return string
	 */
	protected function getItems()
	{
		$content = '';
		
		foreach ($this->getDataProvider() as $index => $obj) {
			$content .= '<div id="' . $this->getId() . '_item' . $index . '">' . $this->getRenderer()->render($obj) . '</div>';
		}
		
		return $content;
	}
}