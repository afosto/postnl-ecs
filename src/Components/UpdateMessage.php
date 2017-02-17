<?php

namespace Afosto\Ecs\Components;

use Afosto\Ecs\Helpers\MessageHelper;

/**
 * Class UpdateMessage
 * @package Afosto\Ecs\Components
 */
abstract class UpdateMessage {

    /**
     * @var array
     */
    public $paths = [];

    /**
     * @var Model
     */
    protected $models = [];

    /**
     * Magic getter.
     *
     * @param string $name
     *
     * @return string|null|[]
     */
    public function __get($name) {
        $methodName = 'get' . ucfirst($name);
        if (method_exists($this, $methodName)) {
            return $this->$methodName();
        } else if (property_exists($this, $name)) {
            return $this->$name;
        }

        return null;
    }

    /**
     * UpdateMessage constructor.
     */
    public function getModels() {
        $files = MessageHelper::listMessages($this->getDirectory());
        foreach ($files as $file) {
            $update = new static();
            $this->paths[] = $file['path'];
            $this->models = array_merge($this->models, $update->processMessage($file['content']));
        }

        return $this->models;
    }

    /**
     * Marks the message as read
     *
     * @return bool
     */
    public function markAsRead() {
        foreach ($this->paths as $path) {
            return App::getInstance()->getFilesystem()->delete($path);
        }
    }

    protected abstract function getDirectory();

    /**
     * Processes the preprocessed message (XML-file)
     *
     * @param  array
     *
     * @return string
     */
    public abstract function processMessage($data);

    /**
     * Returns the message type
     * @return string
     */
    protected abstract function getType();

}