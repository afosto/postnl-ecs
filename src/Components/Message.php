<?php
/**
 * This class is used for outgoing messages (XML-files) that should be send to PostNL ECS.
 * The xml-files (messages) are stored on the system's SFTP server and will then await processing.
 */

namespace Afosto\Ecs\Components;

use Afosto\Ecs\Helpers\MessageException;
use Sabre\Xml\Service;

abstract class Message {

    /**
     * @var \DateTime
     */
    private $_dateTime;

    /**
     * The message number
     * @var integer
     */
    private $_messageNumber;

    /**
     * Message parts
     * @var Model[]
     */
    protected $parts = [];

    /**
     * Returns the name of the directory where to store the xml file into
     * @return string
     */
    public abstract function getDirectory();

    /**
     * Message constructor.
     *
     * @param integer $messageNumber Incremental message number
     */

    public function __construct($messageNumber) {
        $this->_dateTime = new \DateTime();
        $this->_messageNumber = $messageNumber;
    }

    /**
     * Send the message
     * @return bool
     * @throws MessageException
     */
    public function send() {
        if (!App::getInstance()->getFilesystem()
                ->write($this->getDirectory() . DIRECTORY_SEPARATOR . $this->getFilename(), $this->_getXml())
        ) {
            throw new MessageException("Cannot write file {$this->getDirectory()} / {$this->getFilename()})");
        }

        return true;
    }

    /**
     * Show the message in the browser
     */
    public function show() {
        header("Content-type: application/xml");
        echo $this->_getXml();
        exit();
    }

    /**
     * Download the file to the browser
     *
     * @param string $filename
     */
    public function download($filename = 'message.xml') {
        header("Content-type: application/xml");
        header("Content-Disposition: attachment; filename=" . $filename);
        echo $this->_getXml();
        exit();
    }

    /**
     * Returns the message type
     * @return string
     */
    protected abstract function getType();

    /**
     * @return mixed
     */
    protected abstract function toArray();

    /**
     * Return the date part for the filename
     * @return string
     */
    protected function getFileName() {
        return $this->_dateTime->format('YmdHis') . '.xml';
    }

    /**
     * @return string
     */
    private function _getXml() {
        $service = new Service();

        return $service->write('message', [
            'type'      => $this->getType(),
            'messageNo' => $this->_messageNumber,
            'date'      => $this->_dateTime->format('Y-m-d'),
            'time'      => $this->_dateTime->format('H:i:s'),
            $this->toArray(),
        ]);
    }

}