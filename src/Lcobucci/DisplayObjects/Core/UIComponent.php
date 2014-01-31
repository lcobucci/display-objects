<?php
namespace Lcobucci\DisplayObjects\Core;

use Doctrine\Common\Cache\Cache;

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
    const CACHE_IDENTIFIER = 'template-map';

    /**
     * @var string
     */
    protected static $baseUrl;

    /**
     * @var bool
     */
    protected static $includePathVerified = false;

    /**
     * @var Cache
     */
    protected static $cache;

    /**
     * @var array
     */
    protected static $map = array();

    /**
     * Appends the library dir into the include path
     */
    protected function checkIncludePath()
    {
        if (static::$includePathVerified) {
            return ;
        }

        $path = realpath(__DIR__ . '/../../../');
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
        if (isset(static::$map[$class])) {
            return static::$map[$class];
        }

        $templateFile = $this->getPath($class);

        if ($this->templateExists($templateFile)) {
            return $this->mapFile($class, $templateFile);
        }

        throw new UIComponentNotFoundException('Template file not found for class ' . $class . '.');
    }

    /**
     * @param string $class
     * @param string $file
     * @return string mixed
     */
    protected function mapFile($class, $file)
    {
        static::$map[$class] = $file;

        if (static::$cache) {
            static::$cache->save(static::CACHE_IDENTIFIER, static::$map);
        }

        return $file;
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
        } catch (\Exception $e) {
            trigger_error((string) $e, E_USER_ERROR);
        }
    }

    /**
     * @param Cache $cache
     */
    public static function setCache(Cache $cache)
    {
        static::$cache = $cache;
        static::$map = $cache->fetch(static::CACHE_IDENTIFIER) ?: array();
    }

    /**
     * @param string $baseUrl
     */
    public static function setBaseUrl($baseUrl)
    {
        static::$baseUrl = rtrim($baseUrl, '/') . '/';
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return static::$baseUrl;
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
