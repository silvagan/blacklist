<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit06d4329e665b33044f90a485cd2e3153
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Silva\\Silva\\' => 12,
        ),
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Silva\\Silva\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit06d4329e665b33044f90a485cd2e3153::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit06d4329e665b33044f90a485cd2e3153::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit06d4329e665b33044f90a485cd2e3153::$classMap;

        }, null, ClassLoader::class);
    }
}
