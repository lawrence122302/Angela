<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitc188d5c50da8e7194c3d6faf27fb2cbf
{
    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInitc188d5c50da8e7194c3d6faf27fb2cbf::$classMap;

        }, null, ClassLoader::class);
    }
}
