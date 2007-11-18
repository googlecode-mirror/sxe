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
 
class SXE_TestCase_appendXML extends PHPUnit_Framework_TestCase
{
	public function testChild()
	{
		$root = new SXE('<root><child /></root>');
		$new = '<new />';

		$return = $root->appendXML($new);

		$this->assertXmlStringEqualsXmlString('<root><child /><new /></root>', $root->asXML());
		$this->assertXmlStringEqualsXmlString($new, $return->asXML());
	}

	public function testGrandchild()
	{
		$root = new SXE('<root><child /></root>');
		$new = '<new />';

		$return = $root->child->appendXML($new);

		$this->assertXmlStringEqualsXmlString('<root><child><new /></child></root>', $root->asXML());
		$this->assertXmlStringEqualsXmlString($new, $return->asXML());
	}

	public function testTextNode()
	{
		$root = new SXE('<root><child /></root>');

		$return = $root->appendXML('my text node');

		$this->assertXmlStringEqualsXmlString('<root><child />my text node</root>', $root->asXML());
		$this->assertSame(
			dom_import_simplexml($root),
			dom_import_simplexml($return)
		);
	}

	/**
	* @expectedException InvalidArgumentException
	*/
	public function testInvalidArgumentType()
	{
		$root = new SXE('<root><child /></root>');
		$root->appendXML(false);
	}

	/**
	* @expectedException InvalidArgumentException
	*/
	public function testInvalidXML()
	{
		$root = new SXE('<root><child /></root>');
		$root->appendXML('<bad><xml>');
	}
}