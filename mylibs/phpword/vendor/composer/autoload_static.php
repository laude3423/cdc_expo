<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit2bd7df098f22e9c4651b6e8d56cdf6a5
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PhpOffice\\PhpWord\\' => 18,
        ),
        'L' => 
        array (
            'Laminas\\Escaper\\' => 16,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PhpOffice\\PhpWord\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpoffice/phpword/src/PhpWord',
        ),
        'Laminas\\Escaper\\' => 
        array (
            0 => __DIR__ . '/..' . '/laminas/laminas-escaper/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit2bd7df098f22e9c4651b6e8d56cdf6a5::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit2bd7df098f22e9c4651b6e8d56cdf6a5::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit2bd7df098f22e9c4651b6e8d56cdf6a5::$classMap;

        }, null, ClassLoader::class);
    }
}
