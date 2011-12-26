<?php

require_once "Net/HL7/Segment.php";
require_once "Net/HL7.php";
require_once "test_base.php";


# Basic stuff
#
$hl7 = new Net_HL7();

$seg = new Net_HL7_Segment("PID");
$seg->setField(0, "XXX");
$seg->setField(3, "XXX");

ok($seg->getField(0) == "PID", "Field 0 is PID");
ok($seg->getName() == "PID", "Segment name is PID");
ok($seg->getField(3) == "XXX", "Field 3 is XXX");

# Try faulty constructors
#
//ok(! defined(new Net::HL7::Segment()), "Segment constructor with no name");
//ok(! defined( new Net::HL7::Segment("XXXX")), "Segment constructor with 4 char name");
//ok(! defined(new Net::HL7::Segment("xxx")), "Segment constructor with lowercase name");


$seg = new Net_HL7_Segment("DG1", array(4,3,2,array(1,2,3),0));

ok($seg->getField(3) == "2", "Constructor with array ref");

$comps = $seg->getField(4);

ok($comps[2] == "3", "Constructor with array ref for composed fields");


# Field setters/getters
#
$seg = new Net_HL7_Segment("DG1");

$seg->setField(3, array(1, 2, 3));
$seg->setField(8, $hl7->getNull());

ok(is_array($seg->getField(3)), "Composed field 1^2^3");

ok($seg->getField(8) == "\"\"" && $seg->getField(8) == $hl7->getNull(), "HL7 NULL value");

$subFields = $seg->getField(3);

ok(count($subFields) == 3, "Getting composed fields as array");

ok($subFields[1] == "2", "Getting single value from composed field");

$flds = $seg->getFields();

ok(count($flds) == 9, "Number of fields in segment");

$flds = $seg->getFields(2);

ok(count($flds) == 7, "Getting all fields from 2nd index");

$flds = $seg->getFields(2, 4);

ok(count($flds) == 3, "Getting fields from 2 till 4");

$seg->setField(12);

ok($seg->size() == 8, "Size operator");

$seg->setField(12, "x");

ok($seg->size() == 12, "Size operator");
?>