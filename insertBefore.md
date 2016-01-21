#####  #####
## Description ##
_SXE_ **insertBefore** ( _SimpleXMLElement_ $new [, _SimpleXMLElement_ $ref] )

This function inserts a child right before a reference node.

#####  #####
## Parameters ##

_new_
> The new node

_ref_
> The reference node. If not supplied, _newnode_ is appended to the children

#####  #####
## Return Values ##

The inserted node.

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
$new = new SXE('<firstchild />');

$root->insertBefore($new, $root->child);
echo $root->asXML();
```
will output
```
<root>
	<firstchild /><child />
</root>
```

#####  #####
## See also ##
[SXE->appendChild()](appendChild.md)

## DOM ##
[DOMNode->insertBefore()](http://php.net/manual/function.dom-domnode-insertbefore.php)