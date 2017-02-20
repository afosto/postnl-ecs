<?php

namespace Afosto\Ecs\Components;

use Afosto\Ecs\Helpers\MessageHelper;

/**
 * Class UpdateMessage
 * @package Afosto\Ecs\Components
 */
abstract class UpdateMessage {

    /**
     * The file paths, used as reference to mark the message(s) as read
     *
     * @var array
     */
    private $_paths = [];

    /**
     * @var Model
     */
    private $_models = [];

    /**
     * Marks the message as read
     *
     * @return bool
     */
    public function markAsRead() {
        foreach ($this->_paths as $path) {
            if (!App::getInstance()->getFilesystem()->delete($path)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Returns the models
     * @return Model[]
     */
    protected function getUpdates() {
        $files = MessageHelper::listMessages($this->getDirectory());
        foreach ($files as $file) {
            $update = new static();
            $this->_paths[] = $file['path'];
            $this->_models = array_merge($this->_models, $update->processMessage($file['content']));
        }

        return $this->_models;
    }

    /**
     * @return string
     */
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