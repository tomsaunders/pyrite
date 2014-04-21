<?php
class TIETest extends \PHPUnit_Framework_TestCase {

    private $TIE;
    private $dump;

    public function __construct(){
        $this->TIE = new TIE('missions/B7M1AM.TIE');
        $this->dump = $this->TIE->printDump();
    }

    public function testHeader(){
        $header = $this->TIE->header;
        $this->assertEquals(31,         $this->TIE->header->flightGroupCount);
        $this->assertEquals(5,          $this->TIE->header->messageCount);
        $this->assertEquals(2,          $this->TIE->header->briefingOfficers);
        $this->assertTrue($this->TIE->header->capturedOnEject);
    }

    public function testGoalMessages(){
        $messages = $this->TIE->goalMessages;
        $this->assertEquals("You've defeated the first wave of the assault on the Harpax",
            $messages['Primary Success #1']);
        $this->assertEquals("Return to [Harpax's] hangar to be ready for the next wave",
            $messages['Primary Success #2']);
        $this->assertEmpty($messages['Secondary Success #1']);
        $this->assertEmpty($messages['Secondary Success #2']);
        $this->assertEquals("Destruction of the [Harpax] will allow the Protector to escape",
            $messages['Primary Failure #1']);
        $this->assertEquals("Our plan to trap and destroy the Protector will surely fail!",
            $messages['Primary Failure #2']);
    }

    public function testIFF(){
        $this->assertEquals(0, count($this->TIE->IFF), "No IFFs set");
    }

    public function testFlightGroups(){
        $fg = $this->TIE->flightGroups;
        $this->assertEquals(31, count($fg));
    }

    public function testBriefing(){
        $b = $this->TIE->briefing;
        $this->assertEquals(738, $b['RunningTime']);
        $this->assertEquals(14,  $b['StartLength']);
        $this->assertEquals(168, $b['EventLength']);
        $this->assertEquals(46,  count($b['Events']));
        $this->assertEquals(10,  count($b['Tags']));
        $this->assertEquals(5,   count($b['Strings']));
    }

    public function testQuestions(){
        $pre = $this->TIE->preQuestions;
        $this->assertEquals(3, count($pre['Officer']));
        $this->assertEmpty($pre['Secret']);
        $post = $this->TIE->postQuestions;
        $this->assertEquals(2, count($post['Officer']));
        $this->assertEmpty($pre['Secret']);
    }
}
 