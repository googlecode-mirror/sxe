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
 
class SXE_TestCase_parentNode extends PHPUnit_Framework_TestCase
{
	public function testRoot()
	{
		$xml = '<root><child /></root>';
		$root = new SXE($xml);

		/**
		* When asked for the root node's parent, DOM returns the root node itself
		*/
		$this->assertXmlStringEqualsXmlString(
			$root->parentNode()->asXML(),
			$xml
		);
	}

	public function testChild()
	{
		$xml = '<root><child /></root>';
		$root = new SXE($xml);

		$this->assertXmlStringEqualsXmlString(
			$root->parentNode()->asXML(),
			$xml
		);
	}

	public function testGrandchild()
	{
		$xml = '<root><child><grandchild /></child></root>';
		$root = new SXE($xml);

		$this->assertXmlStringEqualsXmlString(
			$root->child->grandchild->parentNode()->asXML(),
			$root->child->asXML()
		);
	}

	public function testReturn()
	{
		$xml = '<root><child /></root>';
		$root = new SXE($xml);

		$this->assertTrue($root->child->parentNode() instanceof SXE);
	}
}