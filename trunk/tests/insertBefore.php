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
 
class SXE_TestCase_insertBefore extends PHPUnit_Framework_TestCase
{
	public function testBeforeFirstChild()
	{
		$root = new SXE('<root><child /></root>');
		$new = new SXE('<new />');

		$root->insertBefore($new, $root->child);

		$this->assertXmlStringEqualsXmlString($root->asXML(), '<root><new /><child /></root>');
	}

	public function testBeforeLastChild()
	{
		$root = new SXE('<root><child /><otherchild /></root>');
		$new = new SXE('<new />');

		$root->insertBefore($new, $root->otherchild);

		$this->assertXmlStringEqualsXmlString($root->asXML(), '<root><child /><new /><otherchild /></root>');
	}

	public function testNotFound()
	{
		$root = new SXE('<root><child><grandchild /></child></root>');
		$new = new SXE('<new />');

		try
		{
			$root->insertBefore($new, $root->child->grandchild);
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

	public function testNoRef()
	{
		$root = new SXE('<root><child /></root>');
		$new = new SXE('<new />');

		$root->insertBefore($new);

		$this->assertXmlStringEqualsXmlString($root->asXML(), '<root><child /><new /></root>');
	}

	public function testReturn()
	{
		$root = new SXE('<root><child /></root>');
		$new = new SXE('<new />');

		$return = $root->insertBefore($new, $root->child);

		$this->assertTrue($return instanceof SXE);
		$this->assertXmlStringEqualsXmlString($return->asXML(), $new->asXML());
	}
}