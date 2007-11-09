<?php
/*

Copyright 2007 The SXE Working Group Initiative

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Lesser General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

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