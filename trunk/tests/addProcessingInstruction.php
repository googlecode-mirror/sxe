<?php
/*

Copyright 2007 The SXE Working Group Initiative

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

*/

require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__) . '/../sxe.php';
 
class SXE_TestCase_addProcessingInstruction extends PHPUnit_Framework_TestCase
{
	public function testNoData()
	{
		$root = new SXE('<root />');
		$expected_xml = '<?xml-stylesheet ?><root />';

		$return = $root->addProcessingInstruction('xml-stylesheet');

		$this->assertTrue($return);
		$this->assertXmlStringEqualsXmlString($root->asXML(), $expected_xml);
	}

	public function testString()
	{
		$root = new SXE('<root />');
		$expected_xml = '<?xml-stylesheet type="text/xsl" href="foo.xsl"?><root />';

		$return = $root->addProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="foo.xsl"');

		$this->assertTrue($return);
		$this->assertXmlStringEqualsXmlString($root->asXML(), $expected_xml);
	}

	public function testArray()
	{
		$root = new SXE('<root />');
		$expected_xml = '<?xml-stylesheet type="text/xsl" href="foo.xsl"?><root />';

		$return = $root->addProcessingInstruction('xml-stylesheet', array(
			'type' => 'text/xsl',
			'href' => '"foo.xsl"'
		));

		$this->assertTrue($return);
		$this->assertXmlStringEqualsXmlString($root->asXML(), $expected_xml);
	}

	public function testMultiple()
	{
		$root = new SXE('<root />');
		$expected_xml = '<?xml-stylesheet type="text/xsl" href="foo.xsl"?><?xml-stylesheet type="text/xsl" href="bar.xsl"?><root />';

		$return = $root->addProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="foo.xsl"');
		$this->assertTrue($return);

		$return = $root->addProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="bar.xsl"');
		$this->assertTrue($return);

		$this->assertXmlStringEqualsXmlString($root->asXML(), $expected_xml);
	}

	public function testInvalidTarget()
	{
		$root = new SXE('<root />');

		try
		{
			$root->addProcessingInstruction('$$$', 'type="text/xsl" href="foo.xsl"');
			$fail = true;
		}
		catch (DOMException $e)
		{
			$this->assertSame($e->code, DOM_INVALID_CHARACTER_ERR);
			$fail = false;
		}

		if ($fail)
		{
			self::fail();
		}
	}

	public function testInvalidArgumentType1()
	{
		$root = new SXE('<root />');

		try
		{
			$root->addProcessingInstruction(false, 'type="text/xsl" href="foo.xsl"');
			$fail = true;
		}
		catch (Exception $e)
		{
			$fail = false;
		}

		if ($fail)
		{
			self::fail();
		}
	}

	public function testInvalidArgumentType2()
	{
		$root = new SXE('<root />');

		try
		{
			$root->addProcessingInstruction('xml-stylesheet', false);
			$fail = true;
		}
		catch (Exception $e)
		{
			$fail = false;
		}

		if ($fail)
		{
			self::fail();
		}
	}
}