<?php
abstract class UIComponent
{
	/**
	 * Lista de diretórios
	 *
	 * @var ArrayObject
	 */
	private static $htmlDirs;

	/**
	 * Retorna a lista de diretórios
	 *
	 * @return ArrayObject
	 */
	public static function getHtmlDirs()
	{
		if (is_null(self::$htmlDirs)) {
			self::$htmlDirs = new ArrayObject();
		}

		return self::$htmlDirs;
	}

	/**
	 * Adiciona um novo diretório à lista de diretórios do sistema
	 *
	 * @param string $dir
	 * @throws InvalidArgumentException
	 */
	public static function appendHtmlDir($dir)
	{
		if (is_dir($dir)) {
			self::appendDirSeparator($dir);
			self::getHtmlDirs()->append($dir);
		} else {
			throw new InvalidArgumentException('Você deve enviar um diretório válido do seu sistema');
		}
	}

	/**
	 * Adiciona uma barra no final do caminho caso ela não exista
	 *
	 * @param string $dir
	 */
	private static function appendDirSeparator(&$dir)
	{
		if (substr($dir, -1, 1) != DIRECTORY_SEPARATOR) {
			$dir .= DIRECTORY_SEPARATOR;
		}
	}

	/**
	 * Retorna o caminho completo do template
	 *
	 * @param string $class
	 * @return string
	 * @throws UIComponentNotFoundException
	 */
	public function getFile($class)
	{
		foreach ($this->getDirList() as $dir) {
			if ($this->templateExists($dir, $class)) {
				return $dir . $class . '.phtml';
			} else {
				foreach ($this->getSubDirectories($dir) as $directory) {
					if (!$directory->isDot() && $directory->isDir()) {
						$path = $directory->getPathName();

						if ($this->templateExists($path, $class)) {
							return $path . $class . '.phtml';
						}
					}
				}
			}
		}

		throw new UIComponentNotFoundException('Template file not found for class ' . $class . '.');
	}

	/**
	 * Retorna os sub diretórios
	 *
	 * @param string $dir
	 * @return DirectoryIterator
	 */
	protected function getSubDirectories($dir)
	{
		return new DirectoryIterator($dir);
	}

	/**
	 * Verifica se o HTML do componente existe no diretório passado
	 *
	 * @param string $path
	 * @param string $class
	 * @return boolean
	 */
	protected function templateExists(&$path, $class)
	{
		self::appendDirSeparator($path);

		return file_exists($path . $class . '.phtml');
	}

	/**
	 * Retorna a lista de diretórios (apenas para testes)
	 *
	 * @return ArrayObject
	 */
	protected function getDirList()
	{
		return self::getHtmlDirs();
	}

	/**
	 * Pega o arquivo HTML com o mesmo nome do componente e retorna seu conteúdo
	 *
	 * @return string
	 */
	public function show($class = null)
	{
		if (is_null($class)) {
			$class = get_class($this);
		}

		return $this->includeFile($this->getFile($class));
	}

	/**
	 * @param string $file
	 * @return string
	 */
	protected function includeFile($file)
	{
		ob_start();
		include $file;
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		try {
			return $this->show();
		} catch (PDOException $e) {
			return '<pre>' . $e->getMessage() . '</pre>';
		} catch (Exception $e) {
			return '<pre>' . $e . '</pre>';
		}
	}
}