<?php

namespace Afosto\Ecs\Helpers;

use Afosto\Ecs\Components\App;

class MessageHelper {

    /**
     * Returns the outstanding messages for the message type
     *
     * @param $directory
     *
     * @return array
     */
    public static function listMessages($directory) {
        $messages = [];
        $files = App::getInstance()->getFilesystem()->listContents($directory);

        //Sort oldest files first in case of a queue
        uasort($files, function ($file1, $file2) {
            return $file1['timestamp'] > $file2['timestamp'];
        });

        foreach ($files as $metaData) {
            $contents = App::getInstance()->getFilesystem()->read($metaData['path']);
            $message['content'] = json_decode(json_encode(simplexml_load_string($contents)), true);
            $message['path'] = $metaData['path'];
            $messages[] = $message;
        }

        return $messages;
    }

    /**
     * Delete messages with the given number
     *
     * @param $directory
     * @param $number
     *
     * @return array
     */
    public static function deleteMessage($directory, $number) {
        $deleted = [];
        foreach (App::getInstance()->getFilesystem()->listContents($directory) as $metaData) {
            $contents = App::getInstance()->getFilesystem()->read($metaData['path']);
            $message = json_decode(json_encode(simplexml_load_string($contents)), true);
            if ($message['messageNo'] == $number) {
                App::getInstance()->getFilesystem()->delete($metaData['path']);
                $deleted[] = $message;
            }
        }

        return $deleted;
    }
}