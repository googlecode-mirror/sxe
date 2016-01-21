#####  #####
## Description ##
_SXE_ **appendChild** ( _SimpleXMLElement_ $new )

This function appends a child to an existing list of children or creates a new list of children.

#####  #####
## Parameters ##

_new_

> The appended child

#####  #####
## Return Values ##

The node added.

#####  #####
## Errors/Exceptions ##
This function throws [DOMException](http://php.net/manual/ref.dom.php#dom.class.domexception)

#####  #####
## Example ##

```
$root = new SXE(
'<root>
	<child />
</root>'
);
$new = new SXE('<grandchild />');

$root->child->appendChild($new);
echo $root->asXML();
```
will output
```
<root>
	<child><grandchild /></child>
</root>
```

#####  #####
## See also ##
[SimpleXMLElement->addChild()](http://php.net/manual/function.simplexml-element-addChild.php)
## DOM ##
[DOMNode->appendChild()](http://php.net/manual/function.dom-domnode-appendchild.php)