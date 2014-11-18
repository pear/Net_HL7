<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004 D.A.Dokter                                        |
// +----------------------------------------------------------------------+
// | This source file is subject to version 3.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.php.net/license/3_0.txt.                                  |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: D.A.Dokter <dokter@w20e.com>                                |
// +----------------------------------------------------------------------+
//
// $Id: Connection.php,v 1.7 2004/08/06 07:38:54 wyldebeast Exp $

require_once 'Net/HL7/Message.php';
require_once 'Net/Socket.php';

/**
 * Usage:
 * <code>
 * $socket = new Net_Socket();
 * $socket->connect('localhost', 8089);
 *
 * $conn = new Net_HL7_Connection($socket);
 *
 *
 * $req = new Net_HL7_Message();
 *
 * ... set some request attributes
 *
 * $res = $conn->send($req);
 * </code>
 *
 * The Net_HL7_Connection object represents the tcp connection to the
 * HL7 message broker. The Connection has only two useful methods
 * (apart from the constructor), send and close. The 'send' method
 * takes a Net_HL7_Message object as argument, and also returns a
 * Net_HL7_Message object. The send method can be used more than once,
 * before the connection is closed.
 *
 * The Connection object holds the following fields:
 *
 * _MESSAGE_PREFIX
 *
 * The prefix to be sent to the HL7 server to initiate the
 * message. Defaults to \013.
 *
 * _MESSAGE_SUFFIX
 * End of message signal for HL7 server. Defaults to \034\015.
 *
 *
 * @version    0.10
 * @author     D.A.Dokter <dokter@w20e.com>
 * @access     public
 * @category   Networking
 * @package    Net_HL7
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 */
class Net_HL7_Connection {

    var $_HANDLE;
    var $_MESSAGE_PREFIX;
    var $_MESSAGE_SUFFIX;


    /**
     * Creates a connection to a HL7 server, or returns undef when a
     * connection could not be established.are:
     *
     * @param mixed Host to connect to
     * @param int Port to connect to
     * @return boolean
     */
    public function __construct(Net_Socket $socket)
    {
        $this->setSocket($socket);
        $this->_MESSAGE_PREFIX = "\013";
        $this->_MESSAGE_SUFFIX = "\034\015";

        return true;
    }

    public function setSocket(Net_Socket $socket) {
        $this->_HANDLE = $socket;
    }

    /**
     * Sends a Net_HL7_Message object over this connection.
     *
     * @param object Instance of Net_HL7_Message
     * @param string $responseCharEncoding The expected character encoding of the response.
     * @return object Instance of Net_HL7_Message
     * @access public
     * @see Net_HL7_Message
     */
    function send($req, $responseCharEncoding = 'UTF-8')
    {
        $handle = $this->_HANDLE;
        $hl7Msg = $req->toString();

        $handle->write($this->_MESSAGE_PREFIX . $hl7Msg . $this->_MESSAGE_SUFFIX);

        $data = "";

        while(($buf = $handle->read(1)) !== false) {
            $data .= $buf;

            if(preg_match("/" . $this->_MESSAGE_SUFFIX . "$/", $data))
                break;
        }

        // Remove message prefix and suffix
        $data = preg_replace("/^" . $this->_MESSAGE_PREFIX . "/", "", $data);
        $data = preg_replace("/" . $this->_MESSAGE_SUFFIX . "$/", "", $data);

        // set character encoding
        $data = mb_convert_encoding($data, $responseCharEncoding);

        $resp = new Net_HL7_Message($data);

        return $resp;
    }
}
