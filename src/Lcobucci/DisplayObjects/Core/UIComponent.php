<?php
namespace Lcobucci\DisplayObjects\Core;

/**
 * Main class to create components
 *
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
abstract class UIComponent
{
    /**
     * @var string
     */
    protected static $baseUrl;

    /**
     * @var bool
     */
    protected static $includePathVerified = false;

    /**
     * Appends the library dir into the include path
     */
    protected function checkIncludePath()
    {
        if (static::$includePathVerified) {
            return ;
        }

        $path = realpath(__DIR__ .'/../../../');
        $includePath = get_include_path();

        if (strpos($path, $includePath) === false) {
            set_include_path($includePath . PATH_SEPARATOR . $path);
        }

        static::$includePathVerified = true;
    }

    /**
     * Verify if the template exists on actual include path
     *
     * @param string $templateFile
     * @return boolean
     */
    protected function templateExists($templateFile)
    {
        $this->checkIncludePath();

        return stream_resolve_include_path($templateFile);
    }

    /**
     * Calculate the path of the template from the class name (according to PSR-0 autoloader)
     *
     * @param string $class
     * @return string
     */
    protected function getPath($class)
    {
        $fileName = '';

        if (false !== ($lastNsPos = strripos($class, '\\'))) {
            $namespace = substr($class, 0, $lastNsPos);
            $class = substr($class, $lastNsPos + 1);

            $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;

            if (strpos($fileName, DIRECTORY_SEPARATOR) === 0) {
                $fileName = substr($fileName, 1);
            }
        }

        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $class) . '.phtml';

        return $fileName;
    }

    /**
     * Returns the template file to be included
     *
     * @param string $class
     * @return string
     * @throws UIComponentNotFoundException
     */
    protected function getFile($class)
    {
        $templateFile = $this->getPath($class);

        if ($this->templateExists($this->getPath($class))) {
            return $templateFile;
        }

        throw new UIComponentNotFoundException('Template file not found for class ' . $class . '.');
    }

    /**
     * Returns the template content (after processing)
     *
     * @param string $class
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
     * Get the template's content
     *
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
     * Render the component
     *
     * @return string
     */
    public function __toString()
    {
        try {
            return $this->show();
        } catch (\PDOException $e) {
            return '<pre>' . $e->getMessage() . '</pre>';
        } catch (\Exception $e) {
            return '<pre>' . $e . '</pre>';
        }
    }

    /**
     * @param string $baseUrl
     */
    public static function setDefaultBaseUrl($baseUrl)
    {
        static::$baseUrl = rtrim($baseUrl, '/') . '/';
    }

    /**
     * @return string
     */
    public static function getDefaultBaseUrl()
    {
        return static::$baseUrl;
    }

    /**
     * @param string $baseUrl
     */
    public function setBaseUrl($baseUrl)
    {
        static::setDefaultBaseUrl($baseUrl);
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return static::getDefaultBaseUrl();
    }

    /**
     * @param string $path
     * @return string
     */
    public function getUrl($path = '')
    {
        return $this->getBaseUrl() . ltrim($path, '/');
    }
}
