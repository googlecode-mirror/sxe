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
 
class SXE_TestCase_removeNodes extends PHPUnit_Framework_TestCase
{
	public function testRootContext()
	{
		$xpath = '//*[@remove="1"]';

		$root = new SXE(
			'<root>
				<child1 remove="1" />
				<child2 remove="0" />
				<child3>
					<grandchild remove="1" />
				</child3>
			</root>',

			LIBXML_NOBLANKS
		);

		$expected_result = new SXE(
			'<root>
				<child2 remove="0" />
				<child3 />
			</root>',

			LIBXML_NOBLANKS
		);

		$expected_return = array(
			clone $root->child1,
			clone $root->child3->grandchild
		);

		$return = $root->removeNodes($xpath);

		$this->assertXmlStringEqualsXmlString($root->asXML(), $expected_result->asXML());
		$this->assertEquals($return, $expected_return);
	}

	public function testChildContext()
	{
		$xpath = './/*[@remove="1"]';

		$root = new SXE(
			'<root>
				<child1 remove="1" />
				<child2 remove="0" />
				<child3>
					<grandchild>
						<grandgrandchild remove="1" />
					</grandchild>
				</child3>
			</root>',

			LIBXML_NOBLANKS
		);

		$expected_result = new SXE(
			'<root>
				<child1 remove="1" />
				<child2 remove="0" />
				<child3>
					<grandchild />
				</child3>
			</root>',

			LIBXML_NOBLANKS
		);

		$expected_return = array(
			clone $root->child3->grandchild->grandgrandchild
		);

		$return = $root->child3->removeNodes($xpath);

		$this->assertXmlStringEqualsXmlString($root->asXML(), $expected_result->asXML());
		$this->assertEquals($return, $expected_return);
	}

	public function testChildContextNoMatches()
	{
		$xpath = './*[@remove="1"]';

		$root = new SXE(
			'<root>
				<child1 remove="1" />
				<child2 remove="0" />
				<child3>
					<grandchild>
						<grandgrandchild remove="1" />
					</grandchild>
				</child3>
			</root>',

			LIBXML_NOBLANKS
		);

		$expected_result = clone $root;
		$expected_return = array();

		$return = $root->child3->removeNodes($xpath);

		$this->assertXmlStringEqualsXmlString($root->asXML(), $expected_result->asXML());
		$this->assertEquals($return, $expected_return);
	}

	public function testInvalidArgumentType()
	{
		$root = new SXE('<root />');

		try
		{
			$root->removeNodes(false);
			$fail = true;
		}
		catch (InvalidArgumentException $e)
		{
			$fail = false;
		}

		if ($fail)
		{
			self::fail();
		}
	}

	public function testInvalidXPath()
	{
		$root = new SXE('<root />');

		if (!libxml_use_internal_errors())
		{
			$restore = true;
			libxml_use_internal_errors(true);
		}

		$return = $root->removeNodes('????');

		if (isset($restore))
		{
			libxml_use_internal_errors(false);
		}

		$this->assertFalse($return);
	}
}