<?php

require_once "Net/HL7/Segments/MSH.php";
require_once "Net/HL7/Segment.php";
require_once "Net/HL7/Message.php";
require_once "Net/HL7/Messages/ACK.php";

class AckTest extends PHPUnit_Framework_TestCase {

    public function test() {
        $msg = new Net_HL7_Message();
        $msg->addSegment(new Net_HL7_Segments_MSH());

        $msh = $msg->getSegmentByIndex(0);
        $msh->setField(15, "AL");
        $msh->setField(16, "NE");

        $ack = new Net_HL7_Messages_ACK($msg);

        $seg1 = $ack->getSegmentByIndex(1);

        $this->assertTrue($seg1->getField(1) == "CA", "Error code is CA");

        $msg = new Net_HL7_Message();
        $msh = new Net_HL7_Segments_MSH();
        $msh->setField(15, "");
        $msh->setField(16, "NE");
        $msg->addSegment($msh);
        $ack = new Net_HL7_Messages_ACK($msg);

        $seg1 = $ack->getSegmentByIndex(1);

        $this->assertTrue($seg1->getField(1) == "CA", "Error code is CA");

        $msg = new Net_HL7_Message();
        $msh = new Net_HL7_Segments_MSH();
        $msh->setField(16, "");
        $msh->setField(15, "");
        $msg->addSegment($msh);
        $ack = new Net_HL7_Messages_ACK($msg);

        $seg1 = $ack->getSegmentByIndex(1);

        $this->assertTrue($seg1->getField(1) == "AA", "Error code is AA");

        $ack->setAckCode("E");

        $this->assertTrue($seg1->getField(1) == "AE", "Error code is AE");

        $ack->setAckCode("CR");

        $this->assertTrue($seg1->getField(1) == "CR", "Error code is CR");

        $ack->setAckCode("CR", "XX");

        $this->assertTrue($seg1->getField(3) == "XX", "Set message and code");


        $msg = new Net_HL7_Message();
        $msg->addSegment(new Net_HL7_Segments_MSH());
        $msh = $msg->getSegmentByIndex(0);

        $msh->setField(16, "NE");
        $msh->setField(11, "P");
        $msh->setField(12, "2.4");
        $msh->setField(15, "NE");

        $ack = new Net_HL7_Messages_ACK($msg);

        $seg0 = $ack->getSegmentByIndex(0);

        $this->assertTrue($seg0->getField(11) == "P", "Field 11 is P");
        $this->assertTrue($seg0->getField(12) == "2.4", "Field 12 is 2.4");
        $this->assertTrue($seg0->getField(15) == "NE", "Field 15 is NE");
        $this->assertTrue($seg0->getField(16) == "NE", "Field 16 is NE");

        $ack = new Net_HL7_Messages_ACK($msg);
        $ack->setErrorMessage("Some error");

        $seg1 = $ack->getSegmentByIndex(1);

        $this->assertTrue($seg1->getField(3) == "Some error", "Setting error message");
        $this->assertTrue($seg1->getField(1) == "CE", "Code CE after setting message");
    }
}