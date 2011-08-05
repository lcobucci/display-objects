<?php
namespace Lcobucci\DisplayObjects\Components\Datagrid;

use Lcobucci\DisplayObjects\Core\UIComponent;

class Datagrid extends UIComponent
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
	 * @var array
	 */
	protected $columns;

	/**
	 * @param string $id
	 * @param array $dataProvider
	 * @param array $columns
	 */
	public function __construct($id, array $dataProvider, array $columns)
	{
		$this->setId($id);
		$this->setDataProvider($dataProvider);
		$this->setColumns($columns);
	}

	/**
	 * Returns the $id
	 *
	 * @return string
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Configures the $id
	 *
	 * @param string $id
	 */
	protected function setId($id)
	{
		$this->id = $id;
	}

	/**
	 * Returns the $dataProvider
	 *
	 * @return array
	 */
	public function getDataProvider()
	{
		return $this->dataProvider;
	}

	/**
	 * Configures the $dataProvider
	 *
	 * @param array $dataProvider
	 */
	protected function setDataProvider(array $dataProvider)
	{
		$this->dataProvider = $dataProvider;
	}

	/**
	 * Returns the $columns
	 *
	 * @return array
	 */
	public function getColumns()
	{
		return $this->columns;
	}

	/**
	 * Configures the $columns
	 *
	 * @param array $columns
	 */
	protected function setColumns(array $columns)
	{
		$this->columns = $columns;
	}
}