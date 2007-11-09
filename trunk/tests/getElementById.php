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
 
class SXE_TestCase_getElementById extends PHPUnit_Framework_TestCase
{
	public function testChild()
	{
		$root = new SXE(
			'<root>
				<child1 id="foo" />
				<child2 id="bar" />
				<child3 id="baz">
					<grandchild id="quux" />
				</child3>
			</root>'
		);

		$expected_return = clone $root->child2;
		$return = $root->getElementById('bar');

		$this->assertTrue($return instanceof SXE);
		$this->assertXmlStringEqualsXmlString($return->asXML(), $expected_return->asXML());
	}

	public function testGrandchild()
	{
		$root = new SXE(
			'<root>
				<child1 id="foo" />
				<child2 id="bar" />
				<child3 id="baz">
					<grandchild id="quux" />
				</child3>
			</root>'
		);

		$expected_return = clone $root->child3->grandchild;
		$return = $root->getElementById('quux');

		$this->assertTrue($return instanceof SXE);
		$this->assertXmlStringEqualsXmlString($return->asXML(), $expected_return->asXML());
	}

	public function testNotFound()
	{
		$root = new SXE(
			'<root>
				<child1 id="foo" />
				<child2 id="bar" />
				<child3 id="baz">
					<grandchild id="quux" />
				</child3>
			</root>'
		);

		$return = $root->getElementById('inexistent');

		$this->assertFalse($return);
	}

	public function testDupe()
	{
		$root = new SXE(
			'<root>
				<child1 id="foo" />
				<child2 id="foo" />
			</root>'
		);

		$expected_return = clone $root->child1;
		$return = $root->getElementById('foo');

		$this->assertTrue($return instanceof SXE);
		$this->assertXmlStringEqualsXmlString($return->asXML(), $expected_return->asXML());
	}

	public function testInvalidArgumentType()
	{
		$root = new SXE('<root />');

		try
		{
			$root->getElementById(false);
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