<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita1317542255aca08877df7fd3ee81f99
{
    public static $prefixLengthsPsr4 = array (
        'C' => 
        array (
            'Curl\\' => 5,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Curl\\' => 
        array (
            0 => __DIR__ . '/..' . '/php-curl-class/php-curl-class/src/Curl',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInita1317542255aca08877df7fd3ee81f99::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInita1317542255aca08877df7fd3ee81f99::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInita1317542255aca08877df7fd3ee81f99::$classMap;

        }, null, ClassLoader::class);
    }
}
