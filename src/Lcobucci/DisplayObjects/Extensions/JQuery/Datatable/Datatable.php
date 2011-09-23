<?php
namespace Lcobucci\DisplayObjects\Extensions\JQuery\Datatable;

use \Lcobucci\DisplayObjects\Core\UIComponent;
use \Lcobucci\DisplayObjects\Components\Datagrid\Datagrid;

class Datatable extends UIComponent
{
	/**
	 * @var \Lcobucci\DisplayObjects\Components\Datagrid\Datagrid
	 */
	private $table;

	/**
	 * @var array
	 */
	private $options;

	/**
	 * @param string $id
	 * @param array $dataProvider
	 * @param array $columns
	 * @param array $options
	 */
	public function __construct($id, array $dataProvider, array $columns, array $options = null)
	{
		$this->table = new Datagrid($id, $dataProvider, $columns);
	}

	/**
	 * @return string
	 */
	public function getId()
	{
		return $this->table->getId();
	}

	/**
	 * @return \Lcobucci\DisplayObjects\Components\Datagrid\Datagrid
	 */
	public function getTable()
	{
		return $this->table;
	}

	/**
	 * @return string
	 */
	public function getOptions()
	{
		return $this->options ? json_encode($this->options) : '';
	}
}