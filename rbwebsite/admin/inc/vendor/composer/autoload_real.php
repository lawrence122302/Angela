<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitc188d5c50da8e7194c3d6faf27fb2cbf
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderInitc188d5c50da8e7194c3d6faf27fb2cbf', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInitc188d5c50da8e7194c3d6faf27fb2cbf', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInitc188d5c50da8e7194c3d6faf27fb2cbf::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
