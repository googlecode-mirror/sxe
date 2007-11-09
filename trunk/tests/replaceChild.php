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
 
class SXE_TestCase_replaceChild extends PHPUnit_Framework_TestCase
{
	public function testReplaceFirstChild()
	{
		$root = new SXE('<root><child1 /><child2 /><child3 /></root>');
		$new = new SXE('<new />');

		$root->replaceChild($new, $root->child1);

		$this->assertXmlStringEqualsXmlString($root->asXML(), '<root><new /><child2 /><child3 /></root>');
	}

	public function testReplaceMiddleChild()
	{
		$root = new SXE('<root><child1 /><child2 /><child3 /></root>');
		$new = new SXE('<new />');

		$root->replaceChild($new, $root->child2);

		$this->assertXmlStringEqualsXmlString($root->asXML(), '<root><child1 /><new /><child3 /></root>');
	}

	public function testReplaceLastChild()
	{
		$root = new SXE('<root><child1 /><child2 /><child3 /></root>');
		$new = new SXE('<new />');

		$root->replaceChild($new, $root->child3);

		$this->assertXmlStringEqualsXmlString($root->asXML(), '<root><child1 /><child2 /><new /></root>');
	}

	public function testNotFound()
	{
		$root = new SXE('<root><child><grandchild /></child></root>');
		$new = new SXE('<new />');

		try
		{
			$root->replaceChild($new, $root->child->grandchild);
		}
		catch (DOMException $e)
		{
			$this->assertSame($e->code, DOM_NOT_FOUND_ERR);
		}
		catch (Exception $e)
		{
			self::fail('Unexpected exception thrown: ' . get_class($e) . '(' . $e->getMessage() . ')');
		}

		if (!isset($e))
		{
			self::fail('No exception thrown');
		}
	}

	public function testReturn()
	{
		$root = new SXE('<root><child /></root>');
		$new = new SXE('<new />');

		$expected_return = clone $root->child;
		$return = $root->replaceChild($new, $root->child);

		$this->assertTrue($return instanceof SXE);
		$this->assertXmlStringEqualsXmlString($return->asXML(), $expected_return->asXML());
	}
}