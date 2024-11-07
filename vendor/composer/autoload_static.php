<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInite0e15947624ab39ec88bb6f315605b25
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'SGLB\\' => 5,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'SGLB\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInite0e15947624ab39ec88bb6f315605b25::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInite0e15947624ab39ec88bb6f315605b25::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInite0e15947624ab39ec88bb6f315605b25::$classMap;

        }, null, ClassLoader::class);
    }
}
