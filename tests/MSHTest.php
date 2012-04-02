<?php
require_once 'PHPUnit/Framework/TestCase.php';
require_once "Net/HL7/Segment.php";
require_once "Net/HL7/Segments/MSH.php";
class MSHTest extends PHPUnit_Framework_TestCase {

    public function test() {
        $msh = new Net_HL7_Segments_MSH();

        $msh->setField(1, "*");

        $this->assertTrue($msh->getField(1) == "*", "MSH Field sep field (MSH(1))");

        $msh->setField(1, "xx");

        $this->assertTrue($msh->getField(1) == "*", "MSH Field sep field (MSH(1))");

        $msh->setField(2, "xxxxx");

        # Should have had no effect
        $this->assertTrue($msh->getField(2) == "^~\\&", "Special fields not changed");

        # Should have had the effect of changing some separator fields
        $msh->setField(2, "abcd");
        $this->assertTrue($msh->getField(2) == "abcd", "Encoding characters field set (MSH(2))");
    }
}