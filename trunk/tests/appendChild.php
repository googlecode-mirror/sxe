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
 
class SXE_TestCase_appendChild extends PHPUnit_Framework_TestCase
{
	public function testChild()
	{
		$root = new SXE('<root><child /></root>');
		$new = new SXE('<new />');

		$root->appendChild($new);

		$this->assertXmlStringEqualsXmlString($root->asXML(), '<root><child /><new /></root>');
	}

	public function testGrandchild()
	{
		$root = new SXE('<root><child /></root>');
		$new = new SXE('<new />');

		$root->child->appendChild($new);

		$this->assertXmlStringEqualsXmlString($root->asXML(), '<root><child><new /></child></root>');
	}

	public function testReturn()
	{
		$root = new SXE('<root><child /></root>');
		$new = new SXE('<new />');

		$return = $root->child->appendChild($new);

		$this->assertTrue($return instanceof SXE);
		$this->assertXmlStringEqualsXmlString($return->asXML(), $new->asXML());
	}
}