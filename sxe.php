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
		return simplexml_import_dom($node, get_class($this));
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

		if (isset($ref))
		{
			$ref = dom_import_simplexml($ref);
			$node = $tmp->insertBefore($new, $ref);
		}
		else
		{
			$node = $tmp->insertBefore($new);
		}

		return simplexml_import_dom($node, get_class($this));
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

		$node = $tmp->replaceChild($new, $old);
		return simplexml_import_dom($node, get_class($this));
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

		$node = $tmp->removeChild($old);
		return simplexml_import_dom($node, get_class($this));
	}

	/**
	* Append raw XML data
	*
	* @see http://php.net/manual/function.dom-domdocumentfragment-appendxml.php
	*
	* @param	string	$xml	XML to append
	* @return	SXE				The appended node on success or FALSE on failure
	*/
	public function appendXML($xml)
	{
		if (!is_string($xml))
		{
			throw new Exception('Argument 1 passed to appendXML() must be a string, ' . gettype($xml) . ' given');
		}

		$tmp = dom_import_simplexml($this);
		$fragment = $tmp->ownerDocument->createDocumentFragment();

		if (!$fragment->appendXML($xml))
		{
			return false;
		}

		$node = $tmp->appendChild($fragment);
		return simplexml_import_dom($node, get_class($this));
	}

	/**
	* Search for an element with a certain ID
	*
	* NOTE: in case of multiple elements having the same ID, only the first one is returned.
	* Also, this method does NOT check whether the given ID would be valid value.
	*
	* @param	string	$id		Element ID
	* @return	SXE				The node on success, or FALSE otherwise
	*/
	public function getElementById($id)
	{
		if (!is_string($id))
		{
			throw new Exception('Argument 1 passed to getElementsByTagName() must be a string, ' . gettype($id) . ' given');
		}

		$nodes = $this->xpath('//*[@id="' . htmlspecialchars($id) . '"]');

		if (empty($nodes))
		{
			return false;
		}

		return $nodes[0];
	}

	/**
	* Search for all elements with given tag name
	*
	* @param	string	$tag	Tag name
	* @return	array			Array of SXE nodes
	*/
	public function getElementsByTagName($tag)
	{
		if (!is_string($tag))
		{
			throw new Exception('Argument 1 passed to getElementsByTagName() must be a string, ' . gettype($tag) . ' given');
		}

		if (!preg_match('#^(?:[a-z_0-9]+:)?[a-z0-9]+$#iD', $tag))
		{
			throw new Exception('Invalid tag name passed to getElementsByTagName()');
		}

		return $this->xpath('//' . $tag);
	}

	/**
	* Return current node's parent
	*
	* @return	SXE				Parent node if applicable, or current node otherwise
	*/
	public function parentNode()
	{
		$tmp = dom_import_simplexml($this);
		return simplexml_import_dom($tmp->parentNode, get_class($this));
	}

	/**
	* Return the first child of this node
	*
	* @return	SXE				SXE node if applicable, NULL otherwise
	*/
	public function firstChild()
	{
		$tmp = dom_import_simplexml($this);

		if (isset($tmp->firstChild))
		{
			return simplexml_import_dom($tmp->firstChild, get_class($this));
		}
		else
		{
			return null;
		}
	}

	/**
	* Return the last child of this node
	*
	* @return	SXE				SXE node if applicable, NULL otherwise
	*/
	public function lastChild()
	{
		$tmp = dom_import_simplexml($this);

		if (isset($tmp->lastChild))
		{
			return simplexml_import_dom($tmp->lastChild, get_class($this));
		}
		else
		{
			return null;
		}
	}

	/**
	* Return the node immediately preceding this node
	*
	* @return	SXE				SXE node if applicable, NULL otherwise
	*/
	public function previousSibling()
	{
		$tmp = dom_import_simplexml($this);

		if (isset($tmp->previousSibling))
		{
			return simplexml_import_dom($tmp->previousSibling, get_class($this));
		}
		else
		{
			return null;
		}
	}

	/**
	* Return the node immediately following this node
	*
	* @return	SXE				SXE node if applicable, NULL otherwise
	*/
	public function nextSibling()
	{
		$tmp = dom_import_simplexml($this);

		if (isset($tmp->nextSibling))
		{
			return simplexml_import_dom($tmp->nextSibling, get_class($this));
		}
		else
		{
			return null;
		}
	}

	/**
	* Remove all elements matching a XPath expression
	*
	* @param	string	$xpath	XPath expression
	* @return	array			Array of removed nodes on success or FALSE on failure
	*/
	public function removeNodes($xpath)
	{
		if (!is_string($xpath))
		{
			throw new Exception('Argument 1 passed to removeNodes() must be a string, ' . gettype($xpath) . ' given');
		}

		$nodes = $this->xpath($xpath);

		if ($nodes === false)
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
		if (!is_string($xpath))
		{
			throw new Exception('Argument 1 passed to deleteNodes() must be a string, ' . gettype($xpath) . ' given');
		}

		$nodes = $this->xpath($xpath);

		if ($nodes === false)
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
		if (!is_string($xpath))
		{
			throw new Exception('Argument 1 passed to deleteNodes() must be a string, ' . gettype($xpath) . ' given');
		}

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

		$node = $tmp->parentNode->removeChild($tmp);
		return simplexml_import_dom($node, get_class($this));
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

		$node = $old->parentNode->replaceChild($new, $old);
		return simplexml_import_dom($node, get_class($this));
	}

	/**
	* Delete current node from document
	*
	* @return	bool						TRUE on success, FALSE otherwise
	*/
	public function delete()
	{
		$tmp = dom_import_simplexml($this);
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
		if (!$tmp->parentNode instanceof DOMElement)
		{
			throw new Exception('Cannot insert nodes outside of root node');
		}

		$node = $tmp->parentNode->insertBefore($new, $tmp);
		return simplexml_import_dom($node, get_class($this));
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
		if (!$tmp->parentNode instanceof DOMElement)
		{
			throw new Exception('Cannot insert nodes outside of root node');
		}

		if (isset($tmp->nextSibling))
		{
			$node = $tmp->parentNode->insertBefore($new, $tmp->nextSibling);
		}
		else
		{
			$node = $tmp->parentNode->appendChild($new);
		}

		return simplexml_import_dom($node, get_class($this));
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
			elseif (!is_string($data))
			{
				throw new Exception('Argument 2 passed to addProcessingInstruction() must be an array or a string, ' . gettype($xml) . ' given');
			}

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