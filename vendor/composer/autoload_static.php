<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit508b43e811fa73e0032f4e342b5ba8be
{
    public static $prefixLengthsPsr4 = array (
        'Z' => 
        array (
            'Zend\\EventManager\\' => 18,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Zend\\EventManager\\' => 
        array (
            0 => __DIR__ . '/..' . '/zendframework/zend-eventmanager/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit508b43e811fa73e0032f4e342b5ba8be::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit508b43e811fa73e0032f4e342b5ba8be::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
