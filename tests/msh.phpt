<?php

require_once "Net/HL7/Segment.php";
require_once "Net/HL7/Segments/MSH.php";
require_once "test_base.php";

$msh = new Net_HL7_Segments_MSH();

$msh->setField(1, "*");

ok($msh->getField(1) == "*", "MSH Field sep field (MSH(1))");

$msh->setField(1, "xx");

ok($msh->getField(1) == "*", "MSH Field sep field (MSH(1))");

$msh->setField(2, "xxxxx");

# Should have had no effect
ok($msh->getField(2) == "^~\\&", "Special fields not changed");

# Should have had the effect of changing some separator fields
$msh->setField(2, "abcd");
ok($msh->getField(2) == "abcd", "Encoding characters field set (MSH(2))");

?>