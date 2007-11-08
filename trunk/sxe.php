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
class SXE extends SimpleXMLElement
{
	/**
	* Add new child at the end of the children
	*
	* @see http://php.net/manual/function.dom-domnode-appendchild.php
	*
	* @param	SimpleXMLElement	$new	New node
	* @return	SXE							The inserted node on success or FALSE on failure
	*/
	public function appendChild(SimpleXMLElement $new)
	{
		$tmp = dom_import_simplexml($this);
		$new = $tmp->ownerDocument->importNode(dom_import_simplexml($new), true);

		$node = $tmp->appendChild($new);
		return ($node instanceof DOMElement) ? simplexml_import_dom($node, get_class($this)) : false;
	}

	/**
	* Add a new child before a reference node
	*
	* @see http://php.net/manual/function.dom-domnode-insertbefore.php
	*
	* @param	SimpleXMLElement	$new	New node
	* @param	SimpleXMLElement	$ref	Reference node
	* @return	SXE							The inserted node on success or FALSE on failure
	*/
	public function insertBefore(SimpleXMLElement $new, SimpleXMLElement $ref = null)
	{
		$tmp = dom_import_simplexml($this);
		$new = $tmp->ownerDocument->importNode(dom_import_simplexml($new), true);

		assert($tmp->ownerDocument === $new->ownerDocument);

		if (isset($ref))
		{
			$ref = dom_import_simplexml($ref);

			assert($tmp->ownerDocument === $ref->ownerDocument);
			assert($ref->parentNode === $tmp);

			$node = $tmp->insertBefore($new, $ref);
		}
		else
		{
			$node = $tmp->insertBefore($new);
		}

		return ($node instanceof DOMElement) ? simplexml_import_dom($node, get_class($this)) : false;
	}

	/**
	* Replace a child
	*
	* @see http://php.net/manual/function.dom-domnode-replacechild.php
	*
	* @param	SimpleXMLElement	$new	New node
	* @param	SimpleXMLElement	$old	Old node
	* @return	SXE							The replaced node on success or FALSE on failure
	*/
	public function replaceChild(SimpleXMLElement $new, SimpleXMLElement $old)
	{
		$tmp = dom_import_simplexml($this);
		$old = dom_import_simplexml($old);
		$new = $tmp->ownerDocument->importNode(dom_import_simplexml($new), true);

		assert($tmp->ownerDocument === $old->ownerDocument);
		assert($tmp->ownerDocument === $new->ownerDocument);
		assert($old->parentNode === $tmp);

		$node = $tmp->replaceChild($new, $old);
		return ($node instanceof DOMElement) ? simplexml_import_dom($node, get_class($this)) : false;
	}

	/**
	* Remove child from list of children
	*
	* @see http://php.net/manual/function.dom-domnode-removechild.php
	*
	* @param	SimpleXMLElement	$old	Old node
	* @return	SXE							The removed node on success or FALSE on failure
	*/
	public function removeChild(SimpleXMLElement $old)
	{
		$tmp = dom_import_simplexml($this);
		$old = dom_import_simplexml($old);

		assert($tmp->ownerDocument === $old->ownerDocument);
		assert($old->parentNode === $tmp);

		$node = $tmp->removeChild($old);
		return ($node instanceof DOMElement) ? simplexml_import_dom($node, get_class($this)) : false;
	}

	/**
	* Append raw XML data
	*
	* @see http://php.net/manual/function.dom-domdocumentfragment-appendxml.php
	*
	* @param	string	$xml	XML to append
	* @return	SXE				The created node on success or FALSE on failure
	*/
	public function appendXML($xml)
	{
		assert(is_string($xml));

		$tmp = dom_import_simplexml($this);
		$fragment = $tmp->ownerDocument->createDocumentFragment();

		if (!$fragment->appendXML($xml))
		{
			return false;
		}

		$node = $tmp->appendChild($fragment);
		return ($node instanceof DOMElement) ? simplexml_import_dom($node, get_class($this)) : false;
	}

	/**
	* Return current node's parent
	*
	* @return	SXE							Parent node if applicable, or current node otherwise
	*/
	public function parentNode()
	{
		$tmp = dom_import_simplexml($this);
		return simplexml_import_dom($tmp->parentNode, get_class($this));
	}

	/**
	* Return the first child of this node
	*
	* @return	SXE							SXE node if applicable, NULL otherwise
	*/
	public function firstChild()
	{
		$tmp = dom_import_simplexml($this);
		return (isset($tmp->firstChild)) ? simplexml_import_dom($tmp->firstChild, get_class($this)) : null;
	}

	/**
	* Return the last child of this node
	*
	* @return	SXE							SXE node if applicable, NULL otherwise
	*/
	public function lastChild()
	{
		$tmp = dom_import_simplexml($this);
		return (isset($tmp->lastChild)) ? simplexml_import_dom($tmp->lastChild, get_class($this)) : null;
	}

	/**
	* Return the node immediately preceding this node
	*
	* @return	SXE							SXE node if applicable, NULL otherwise
	*/
	public function previousSibling()
	{
		$tmp = dom_import_simplexml($this);
		return (isset($tmp->previousSibling)) ? simplexml_import_dom($tmp->previousSibling, get_class($this)) : null;
	}

	/**
	* Return the node immediately following this node
	*
	* @return	SXE							SXE node if applicable, NULL otherwise
	*/
	public function nextSibling()
	{
		$tmp = dom_import_simplexml($this);
		return (isset($tmp->nextSibling)) ? simplexml_import_dom($tmp->nextSibling, get_class($this)) : null;
	}

	/**
	* Remove all elements matching a XPath expression
	*
	* @param	string	$xpath	XPath expression
	* @return	array			Array of removed nodes on success or FALSE on failure
	*/
	public function removeNodes($xpath)
	{
		assert(is_string($xpath));

		$nodes = $this->xpath($xpath);

		if (!$nodes)
		{
			return false;
		}

		$ret = array();
		foreach ($nodes as $node)
		{
			$ret[] = $node->remove();
		}

		return $ret;
	}

	/**
	* Remove all elements matching a XPath expression
	*
	* @param	string				$xpath	XPath expression
	* @param	SimpleXMLElement	$new	Replacement node
	* @return	array						Array of replaced nodes on success or FALSE on failure
	*/
	public function replaceNodes($xpath, SimpleXMLElement $new)
	{
		assert(is_string($xpath));

		$nodes = $this->xpath($xpath);

		if (!$nodes)
		{
			return false;
		}

		$ret = array();
		foreach ($nodes as $node)
		{
			$ret[] = $node->replace($new);
		}

		return $ret;
	}

	/**
	* Delete all elements matching a XPath expression
	*
	* @param	string	$xpath	XPath expression
	* @return	integer			Number of nodes removed
	*/
	public function deleteNodes($xpath)
	{
		assert(is_string($xpath));

		$cnt = 0;
		if ($nodes = $this->xpath($xpath))
		{
			foreach ($nodes as $node)
			{
				if ($node->delete())
				{
					++$cnt;
				}
			}
		}

		return $cnt;
	}

	/**
	* Remove current node from document
	*
	* @return	SXE				Removed node on success or FALSE on failure
	*/
	public function remove()
	{
		$tmp = dom_import_simplexml($this);

		assert(isset($tmp->parentNode));

		$node = $tmp->parentNode->removeChild($tmp);
		return ($node instanceof DOMElement) ? simplexml_import_dom($node, get_class($this)) : false;
	}

	/**
	* Replace current node
	*
	* @param	SimpleXMLElement	$new	New node
	* @return	SXE							Replaced node on success or FALSE on failure
	*/
	public function replace(SimpleXMLElement $new)
	{
		$old = dom_import_simplexml($this);
		$new = $old->ownerDocument->importNode(dom_import_simplexml($new), true);

		assert(isset($old->parentNode));
		assert($old->ownerDocument === $new->ownerDocument);

		$node = $old->parentNode->replaceChild($new, $old);
		return ($node instanceof DOMElement) ? simplexml_import_dom($node, get_class($this)) : false;
	}

	/**
	* Delete current node from document
	*
	* @return	bool						TRUE on success, FALSE otherwise
	*/
	public function delete()
	{
		$tmp = dom_import_simplexml($this);

		assert(isset($tmp->parentNode));

		return (bool) ($tmp->parentNode->removeChild($tmp));
	}

	/**
	* Add a new sibling before the current node
	*
	* @param	SimpleXMLElement	$new	New node
	* @return	SXE							The inserted node on success or FALSE on failure
	*/
	public function insertBeforeCurrent(SimpleXMLElement $new)
	{
		$tmp = dom_import_simplexml($this);
		$new = $tmp->ownerDocument->importNode(dom_import_simplexml($new), true);

		/**
		* We don't want to insert anything before the root node
		*/
		assert($tmp->parentNode instanceof DOMElement);
		assert($tmp->ownerDocument === $new->ownerDocument);

		$node = $tmp->parentNode->insertBefore($new, $tmp);
		return ($node instanceof DOMElement) ? simplexml_import_dom($node, get_class($this)) : false;
	}

	/**
	* Add a new sibling after the current node
	*
	* @param	SimpleXMLElement	$new	New node
	* @return	SXE							The inserted node on success or FALSE on failure
	*/
	public function insertAfterCurrent(SimpleXMLElement $new)
	{
		$tmp = dom_import_simplexml($this);
		$new = $tmp->ownerDocument->importNode(dom_import_simplexml($new), true);

		/**
		* We don't want to insert anything after the root node
		*/
		assert(!$tmp->parentNode instanceof DOMElement);
		assert($tmp->ownerDocument === $new->ownerDocument);

		if (isset($tmp->nextSibling))
		{
			$node = $tmp->parentNode->insertBefore($new, $tmp->nextSibling);
		}
		else
		{
			$node = $tmp->parentNode->appendChild($new);
		}

		return ($node instanceof DOMElement) ? simplexml_import_dom($node, get_class($this)) : false;
	}

	/**
	* Add a Processing Instruction at the top of the document
	*
	* Processing Instructions are inserted in order right before the root node.
	* The content of the PI can be passed either as string or as an associative array.
	*
	* @param	string			$target		Target of the processing instruction
	* @param	string|array	$data		Content of the processing instruction
	* @return	bool						TRUE on success, FALSE on failure
	*/
	public function addProcessingInstruction($target, $data = null)
	{
		$tmp = dom_import_simplexml($this);
		$doc = $tmp->ownerDocument;

		if (isset($data))
		{
			if (is_array($data))
			{
				$str = '';
				foreach ($data as $k => $v)
				{
					$str .= $k . '="' . htmlspecialchars($v) . '" ';
				}

				$data = substr($str, 0, -1);
			}

			assert(is_string($data));

			$pi = $doc->createProcessingInstruction($target, $data);
		}
		else
		{
			$pi = $doc->createProcessingInstruction($target);
		}

		if ($pi === false)
		{
			return false;
		}

		return (bool) $doc->insertBefore($pi, $doc->lastChild);
	}
}