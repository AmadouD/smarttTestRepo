<?php
    /*********************************************************************************
     * Zurmo is a customer relationship management program developed by
     * Zurmo, Inc. Copyright (C) 2015 Zurmo Inc.
     *
     * Zurmo is free software; you can redistribute it and/or modify it under
     * the terms of the GNU Affero General Public License version 3 as published by the
     * Free Software Foundation with the addition of the following permission added
     * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
     * IN WHICH THE COPYRIGHT IS OWNED BY ZURMO, ZURMO DISCLAIMS THE WARRANTY
     * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
     *
     * Zurmo is distributed in the hope that it will be useful, but WITHOUT
     * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
     * FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
     * details.
     *
     * You should have received a copy of the GNU Affero General Public License along with
     * this program; if not, see http://www.gnu.org/licenses or write to the Free
     * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
     * 02110-1301 USA.
     *
     * You can contact Zurmo, Inc. with a mailing address at 27 North Wacker Drive
     * Suite 370 Chicago, IL 60606. or at email address contact@zurmo.com.
     *
     * The interactive user interfaces in original and modified versions
     * of this program must display Appropriate Legal Notices, as required under
     * Section 5 of the GNU Affero General Public License version 3.
     *
     * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
     * these Appropriate Legal Notices must retain the display of the Zurmo
     * logo and Zurmo copyright notice. If the display of the logo is not reasonably
     * feasible for technical reasons, the Appropriate Legal Notices must display the words
     * "Copyright Zurmo Inc. 2015. All rights reserved".
     ********************************************************************************/

    /**
     * Helper utility to capture information and error messages during the execution of functions.
     */
    class MessageLogger
    {
        /**
         * Error message type.
         */
        const ERROR = 1;

        /**
         * Info message type.
         */
        const INFO = 2;

        /**
         * Debug Message type.
         */
        const DEBUG = 3;

        /**
         * @var bool
         */
        public $logDateTimeStamp = true;

        /**
         * @var bool
         */
        protected $errorMessagePresent = false;

        /**
         * @var array
         */
        protected $messages = array();

        /**
         * @var null|object
         */
        protected $messageStreamer;

        protected $messageStreamers = array();

        /**
         * Specify a MessageStreamer if desired.  A message streamer can allow messages to be streamed to the user
         * interface or command line as they are generated instead of waiting for the entire output to be finished.
         * @param object $messageStreamer MessageStreamer or array of MessageStreamers or null
         * @see MessageStreamer class
         */
        public function __construct($messageStreamer = null)
        {
            assert('$messageStreamer == null || $messageStreamer instanceof MessageStreamer || $messageStreamer instanceof JobManagerFileLogRouteMessageStreamer || is_array($messageStreamer)');
            $messageStreamers = array();
            if ($messageStreamer instanceof MessageStreamer || $messageStreamer instanceof JobManagerFileLogRouteMessageStreamer)
            {
                $messageStreamers[] = $messageStreamer;
            }
            elseif (is_array($messageStreamer) && !empty($messageStreamer))
            {
                foreach ($messageStreamer as $messageStreamerItem)
                {
                    assert('$messageStreamerItem instanceof MessageStreamer || $messageStreamerItem instanceof JobManagerFileLogRouteMessageStreamer');
                    $messageStreamers[] = $messageStreamerItem;
                }
            }
            $this->messageStreamers = $messageStreamers;
        }

        /**
         * Add an informational message.
         * @param string $message
         */
        public function addInfoMessage($message)
        {
            $this->add(array(MessageLogger::INFO, $message, static::getFormattedDateTimeStampForNow()));
        }

        /**
         * Add an error message.
         * @param string $message
         */
        public function addErrorMessage($message)
        {
            $this->errorMessagePresent = true;
            $this->add(array(MessageLogger::ERROR, $message, static::getFormattedDateTimeStampForNow()));
        }

        /**
         * Add debug message.
         * @param string $message
         */
        public function addDebugMessage($message)
        {
            $this->add(array(MessageLogger::DEBUG, $message, static::getFormattedDateTimeStampForNow()));
        }

        protected function add($message)
        {
            assert('is_array($message)');
            $this->messages[] = $message;
            if (!empty($this->messageStreamers))
            {
                if ($message[0] != MessageLogger::DEBUG ||
                    ($this->shouldPrintDebugMessages() && $message[0] == MessageLogger::DEBUG))
                {
                    foreach ($this->messageStreamers as $messageStreamer)
                    {
                        if ($this->logDateTimeStamp)
                        {
                            $prefixContent = $message[2] . ' ';
                        }
                        else
                        {
                            $prefixContent = null;
                        }
                        $messageStreamer->add($prefixContent . static::getTypeLabel($message[0]) . ' - ' . $message[1]);
                    }
                }
            }
        }

        public function getMessages()
        {
            return $this->messages;
        }

        /**
         * Print messages.  If $return is true, then the @return value is a string representing the message content.
         * @param boolean $return
         * @param boolean $errorOnly - Only print the error messages.
         */
        public function printMessages($return = false, $errorOnly = false)
        {
            $content = '';
            foreach ($this->messages as $messageInfo)
            {
                if (!$errorOnly || ($errorOnly && $messageInfo[0] == MessageLogger::ERROR))
                {
                    if ($messageInfo[0] != MessageLogger::DEBUG ||
                        ($this->shouldPrintDebugMessages() && $messageInfo[0] == MessageLogger::DEBUG))
                    {
                        $content .= static::getTypeLabel($messageInfo[0]) . ' - ' . $messageInfo[1] . "\n";
                    }
                }
            }
            if ($return)
            {
                return $content;
            }
            echo $content;
        }

        /**
         * Given a message type, get the corresponding translated display label.
         * @param integer $type
         */
        public static function getTypeLabel($type)
        {
            assert('$type == MessageLogger::ERROR || $type == MessageLogger::INFO || $type == MessageLogger::DEBUG');
            if ($type == MessageLogger::ERROR)
            {
                return str_pad(Zurmo::t('Core', 'Error'), 5);
            }
            elseif ($type == MessageLogger::INFO)
            {
                return str_pad(Zurmo::t('Core', 'Info'), 5);
            }
            else
            {
                return str_pad(Zurmo::t('Core', 'Debug'), 5);
            }
        }

        /**
         * @return boolean true if at least one error message is present.
         */
        public function isErrorMessagePresent()
        {
            return $this->errorMessagePresent;
        }

        /**
         * @return bool
         */
        protected function shouldPrintDebugMessages()
        {
            if (YII_DEBUG)
            {
                return true;
            }
            return false;
        }

        /**
         * Blocking error on date because that is how Yii::log does it. Not sure why this is the case.
         * @return bool|string
         */
        protected static function getFormattedDateTimeStampForNow()
        {
            return @date('Y/m/d H:i:s', microtime(true));
        }
    }
?>