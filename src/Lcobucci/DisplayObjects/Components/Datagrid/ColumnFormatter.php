<?php
namespace Lcobucci\DisplayObjects\Components\Datagrid;

interface ColumnFormatter
{
	/**
	 * @param string $content
	 * @return string
	 */
	public function format($content);
}