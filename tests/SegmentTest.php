<?php

require_once "Net/HL7/Segment.php";
require_once "Net/HL7.php";
require_once 'PHPUnit/Framework/TestCase.php';

class SegmentTest extends PHPUnit_Framework_TestCase {

    public function test() {
        # Basic stuff
        #
        $hl7 = new Net_HL7();

        $seg = new Net_HL7_Segment("PID");
        $seg->setField(0, "XXX");
        $seg->setField(3, "XXX");

        $this->assertTrue($seg->getField(0) == "PID", "Field 0 is PID");
        $this->assertTrue($seg->getName() == "PID", "Segment name is PID");
        $this->assertTrue($seg->getField(3) == "XXX", "Field 3 is XXX");

        # Try faulty constructors
        #
        //$this->assertTrue(! defined(new Net::HL7::Segment()), "Segment constructor with no name");
        //$this->assertTrue(! defined( new Net::HL7::Segment("XXXX")), "Segment constructor with 4 char name");
        //$this->assertTrue(! defined(new Net::HL7::Segment("xxx")), "Segment constructor with lowercase name");


        $seg = new Net_HL7_Segment("DG1", array(4,3,2,array(1,2,3),0));

        $this->assertTrue($seg->getField(3) == "2", "Constructor with array ref");

        $comps = $seg->getField(4);

        $this->assertTrue($comps[2] == "3", "Constructor with array ref for composed fields");


        # Field setters/getters
        #
        $seg = new Net_HL7_Segment("DG1");

        $seg->setField(3, array(1, 2, 3));
        $seg->setField(8, $hl7->getNull());

        $this->assertTrue(is_array($seg->getField(3)), "Composed field 1^2^3");

        $this->assertTrue($seg->getField(8) == "\"\"" && $seg->getField(8) == $hl7->getNull(), "HL7 NULL value");

        $subFields = $seg->getField(3);

        $this->assertTrue(count($subFields) == 3, "Getting composed fields as array");

        $this->assertTrue($subFields[1] == "2", "Getting single value from composed field");

        $flds = $seg->getFields();

        $this->assertTrue(count($flds) == 9, "Number of fields in segment");

        $flds = $seg->getFields(2);

        $this->assertTrue(count($flds) == 7, "Getting all fields from 2nd index");

        $flds = $seg->getFields(2, 4);

        $this->assertTrue(count($flds) == 3, "Getting fields from 2 till 4");

        $seg->setField(12);

        $this->assertTrue($seg->size() == 8, "Size operator");

        $seg->setField(12, "x");

        $this->assertTrue($seg->size() == 12, "Size operator");
    }
}