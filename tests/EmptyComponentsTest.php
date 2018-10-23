<?php

require_once "Net/HL7/Message.php";

class EmptyComponentsTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test that empty components are not removed
     *
     * Asserting that issue at https://github.com/pear/Net_HL7/issues/7 is fixed.
     */
    public function test()
    {
        $msgstr = 'MSH|^~\&|SOME_SOURCE||||20180723115125||DFT^P03|2018072311512590201|P|2.2||||NE|' . "\n" .
            'PV1|1|POFFV|723327^^^SLM^^^^^^^DEPID|3|||16367^LAST^FIRST^^^^^^EPROV^^^^PROVID|||||||2|||||300023352295|SELF||||||||||||||||||||||||20180123152601||||||100000000375|';
        $wrong = 'MSH|^~\&|SOME_SOURCE||||20180723115125||DFT^P03|2018072311512590201|P|2.2||||NE|' . "\n" .
            'PV1|1|POFFV|723327^SLM^DEPID|3|||16367^LAST^FIRST^EPROV^PROVID|||||||2|||||300023352295|SELF||||||||||||||||||||||||20180123152601||||||100000000375|' . "\n";

        $msg = new Net_HL7_Message($msgstr);
        $this->AssertEquals($msgstr . "\n", $msg->toString(true));
        $this->AssertNotEquals($wrong, $msg->toString(true));
    }
}
