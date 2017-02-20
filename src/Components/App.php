<?php

namespace Afosto\Ecs\Components;

use Afosto\Ecs\Helpers\AppException;
use League\Flysystem\Filesystem;
use League\Flysystem\Sftp\SftpAdapter;

class App {

    /**
     * Contains the app
     * @var App
     */
    private static $_app;

    /**
     * Filesystem to write the XML files to
     * @var Filesystem
     */
    private $_filesystem;

    /**
     * Init with the configuration
     *
     * @param $config
     */
    public static function init($config) {
        if (self::$_app === null) {
            self::$_app = new App();
            self::$_app->setFilesystem(new Filesystem(new SftpAdapter(array_merge($config, ['timeout' => 10]))));
        }
    }

    /**
     * Return the bootstrapped instance
     *
     * @return App
     * @throws AppException
     */
    public static function getInstance() {
        if (self::$_app === null) {
            throw new AppException('App not instantiated: call init first');
        }

        return self::$_app;
    }

    /**
     * Return the filesystem handlers
     *
     * @return Filesystem
     */
    public function getFilesystem() {
        return $this->_filesystem;
    }

    /**
     * Store the filesystem settings
     *
     * @param Filesystem $filesystem
     */
    public function setFilesystem(Filesystem $filesystem) {
        $this->_filesystem = $filesystem;
    }

}