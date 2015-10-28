<?php
/**
 * Created by PhpStorm.
 * User: duncan
 * Date: 26/10/15
 * Time: 15:58
 */

namespace DuncanOgle\BooleanSearchParser;


class ParserTest extends \PHPUnit_Framework_TestCase
{
    public function testSimpleParsing() {
        $parser = new Parser();

        $this->assertEquals('+ict', $parser->parse('ict'));
        $this->assertEquals('+ict +it', $parser->parse('ict it'));
        $this->assertEquals('ict it', $parser->parse('ict OR it'));
        $this->assertEquals('-ict', $parser->parse('NOT ict'));
        $this->assertEquals('+it -ict', $parser->parse('it NOT ict'));
    }

    public function testFailing() {
        $parser = new Parser();

        $this->assertEquals(null, $parser->parse('"Business Development" or "IT sales" and ("Danish" or "Dutch" or "Italian" or" Denmark" or "Holland or "Netherlands" or "Italy")'));
        $this->assertEquals(null, $parser->parse('("Digital Transformation")) OR ("Innovation Lead"))'));
        $this->assertEquals(null, $parser->parse('title: Customer Experience AND ("Insight Experience" OR "Marketing Strategy)'));
        $this->assertEquals(null, $parser->parse('"ict'));
    }

    public function testComplexParsing() {
        $parser = new Parser();

        $this->assertEquals('+("project assistant" "project supervisor") +retail -construction', $parser->parse('(title:"project assistant" OR title:"project supervisor") AND retail  -construction'));
        $this->assertEquals('+"john-paul caffery" +"john-paul" +caffery', $parser->parse('"john-paul caffery" john-paul caffery'));
        $this->assertEquals('+"procurement" +"source to pay" "supplier relationship management" "srm" +"vetting" +"compliance"', $parser->parse('"Procurement" and "source to pay" and "Supplier relationship management" or "SRM"  and "vetting" and "compliance"'));
        $this->assertEquals('(+"nursing home" +(manager supervisor)) (+commercial +sales +(manager management "team leader"))', $parser->parse('("Nursing Home" and (Manager OR Supervisor)) OR (commercial AND sales AND (manager OR management OR "team leader"))'));
        $this->assertEquals('(�IT� AND security*) OR �security engineer*� OR (financial AND analyst* AND german)', $parser->parse('(+"it" +security*) "security engineer*" (+financial +analyst* +german)'));
    }
}
