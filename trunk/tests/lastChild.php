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
 
class SXE_TestCase_lastChild extends PHPUnit_Framework_TestCase
{
	public function testChild()
	{
		$root = new SXE('<root><child1 /><child2 /><child3 /></root>');
		$child3 = $root->lastChild();

		$this->assertXmlStringEqualsXmlString($child3->asXML(), $root->child3->asXML());
	}

	public function testGrandchild()
	{
		$root = new SXE('<root><child1 /><child2 /><child3><grandchild /></child3></root>');
		$grandchild = $root->child3->lastChild();

		$this->assertXmlStringEqualsXmlString(
			$grandchild->asXML(),
			$root->child3->grandchild->asXML()
		);
	}

	public function testNoChild()
	{
		$root = new SXE('<root />');
		$this->assertNull($root->lastChild());
	}

	public function testNoGrandchild()
	{
		$root = new SXE('<root><child1 /><child2 /><child3 /></root>');
		$grandchild = $root->child3->lastChild();

		$this->assertNull($grandchild);
	}

	public function testReturn()
	{
		$root = new SXE('<root><child1 /><child2 /><child3 /></root>');
		$child3 = $root->lastChild();

		$this->assertTrue($child3 instanceof SXE);
	}
}