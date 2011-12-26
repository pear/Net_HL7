<?php

require_once "Net/HL7/Segments/MSH.php";
require_once "Net/HL7/Message.php";
require_once "Net/HL7/Connection.php";
require_once "test_base.php";

$msg  = new Net_HL7_Message();
$msg->addSegment(new Net_HL7_Segments_MSH());

$seg1 = new Net_HL7_Segment("PID");

$seg1->setField(3, "XXX");

$msg->addSegment($seg1);

/**
// If you have fork support, try this...

$pid = pcntl_fork();

if (! $pid) {

  // Server process
  set_time_limit(0);
  
  // Turn on implicit output flushing so we see what we're getting
  // as it comes in.
  ob_implicit_flush();

  if (($sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) < 0) {
    echo "socket_create() failed: reason: " . socket_strerror($sock) . "\n";
  }
  
  if (($ret = socket_bind($sock, "localhost", 12011)) < 0) {
    echo "socket_bind() failed: reason: " . socket_strerror($ret) . "\n";
  }

  if (($ret = socket_listen($sock, 5)) < 0) {
    echo "socket_listen() failed: reason: " . socket_strerror($ret) . "\n";
  }

  do {
    if (($msgsock = socket_accept($sock)) < 0) {
      echo "socket_accept() failed: reason: " . socket_strerror($msgsock) . "\n";
      break;
    }
    
    if (false === ($buf = socket_read($msgsock, 8192, PHP_NORMAL_READ))) {
      echo "socket_read() failed: reason: " . socket_strerror($ret) . "\n";
      break 2;
    }

    echo "Incoming: $buf\n";

    $msg = new Net_HL7_Message($buf);

    $ack = new Net_HL7_Messages_ACK($msg);
    socket_write($msgsock, "\013" . $ack->toString() . "\034\015", strlen($ack->toString() + 3));
    echo "Said: " . $ack->toString(1) . "\n";

  } while (true);
  
  socket_close($msgsock);
  
  exit;
} 
*/

$conn = new Net_HL7_Connection("localhost", 12002);

ok($conn, "Trying to connect");

$conn || exit -1;

ok($conn, "Sending message\n" . $msg->toString(1));

$resp = $conn->send($msg);

$resp || exit -1;

$msh = $resp->getSegmentByIndex(0);

ok($msh->getField(9) == "ACK", "Checking ACK field");

$conn->close();
