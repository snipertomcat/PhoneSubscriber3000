<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit7277545fc8a29cde669ae747aaba7657
{
    public static $prefixLengthsPsr4 = array (
        'T' => 
        array (
            'Twilio\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Twilio\\' => 
        array (
            0 => __DIR__ . '/..' . '/twilio/sdk/src/Twilio',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit7277545fc8a29cde669ae747aaba7657::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit7277545fc8a29cde669ae747aaba7657::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit7277545fc8a29cde669ae747aaba7657::$classMap;

        }, null, ClassLoader::class);
    }
}
