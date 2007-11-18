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

		$return = $root->getElementById('bar');

		$this->assertTrue($return instanceof SXE);
		$this->assertSame(
			dom_import_simplexml($root->child2),
			dom_import_simplexml($return)
		);
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

		$return = $root->getElementById('quux');

		$this->assertTrue($return instanceof SXE);
		$this->assertSame(
			dom_import_simplexml($root->child3->grandchild),
			dom_import_simplexml($return)
		);
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

		$return = $root->getElementById('foo');

		$this->assertTrue($return instanceof SXE);
		$this->assertSame(
			dom_import_simplexml($root->child1),
			dom_import_simplexml($return)
		);
	}

	/**
	* @expectedException InvalidArgumentException
	*/
	public function testInvalidArgumentType()
	{
		$root = new SXE('<root />');
		$root->getElementById(false);
	}
}