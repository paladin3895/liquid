
function init() {
  var GO = go.GraphObject.make;  // for conciseness in defining templates
  myDiagram =
    GO(go.Diagram, "myDiagram",  // must name or refer to the DIV HTML element
      {
        initialContentAlignment: go.Spot.Center,
        layout: GO(go.LayeredDigraphLayout, { direction: 90, layerSpacing: 10 }),
        allowDrop: true,  // must be true to accept drops from the Palette
        "LinkDrawn": showLinkLabel,  // this DiagramEvent listener is defined below
        "LinkRelinked": showLinkLabel,
        "animationManager.duration": 800, // slightly longer than default (600ms) animation
        "undoManager.isEnabled": true  // enable undo & redo
      });
  // when the document is modified, add a "*" to the title and enable the "Save" button
  myDiagram.addDiagramListener("Modified", function(e) {
    // var button = document.getElementById("SaveButton");
    // if (button) button.disabled = !myDiagram.isModified;
    save();
    var idx = document.title.indexOf("*");
    if (myDiagram.isModified) {
      if (idx < 0) document.title += "*";
    } else {
      if (idx >= 0) document.title = document.title.substr(0, idx);
    }
  });

  // helper definitions for node templates
  function nodeStyle() {
    return [
      // The Node.location comes from the "loc" property of the node data,
      // converted by the Point.parse static method.
      // If the Node.location is changed, it updates the "loc" property of the node data,
      // converting back using the Point.stringify static method.
      // new go.Binding("location", "loc", go.Point.parse).makeTwoWay(go.Point.stringify),
      {
        // the Node.location is at the center of each node
        locationSpot: go.Spot.Center,
        //isShadowed: true,
        //shadowColor: "#888",
        // handle mouse enter/leave events to show/hide the ports
        mouseEnter: function (e, obj) { showPorts(obj.part, true); },
        mouseLeave: function (e, obj) { showPorts(obj.part, false); },
        click: function (e, obj) {
          showDetail(obj);
          var button = document.getElementById("saveDetailButton");
          if (button) button.disabled = false;
        },
      }
    ];
  }

  function nodeColor(type) {
    switch (type) {
      case 'continous': return 'lightgreen';
      case 'parallel': return 'lightblue';
      case 'merging': return 'yellow';
      case 'executive': return 'lightcoral';
      default: return 'black';
    }
  }

  function setKey() {
    var count = myDiagram.nodes.count;
    return count++;
  }
  // Define a function for creating a "port" that is normally transparent.
  // The "name" is used as the GraphObject.portId, the "spot" is used to control how links connect
  // and where the port is positioned on the node, and the boolean "output" and "input" arguments
  // control whether the user can draw links from or to the port.
  function makePort(name, spot, output, input) {
    // the port is basically just a small circle that has a white stroke when it is made visible
    return GO(go.Shape, "Circle",
             {
                fill: "transparent",
                stroke: null,  // this is changed to "white" in the showPorts function
                desiredSize: new go.Size(8, 8),
                alignment: spot, alignmentFocus: spot,  // align the port on the main Shape
                portId: name,  // declare this object to be a "port"
                fromSpot: spot, toSpot: spot,  // declare where links may connect at this port
                fromLinkable: output, toLinkable: input,  // declare whether the user may draw links to/from here
                cursor: "pointer"  // show a different cursor to indicate potential link point
             });
  }
  // define the Node templates for regular nodes
  var lightText = 'black';
  myDiagram.nodeTemplateMap.add("continous",  // the default category
    GO(go.Node, "Spot", nodeStyle(),
      // the main object is a Panel that surrounds a TextBlock with a rectangular Shape
      GO(go.Panel, "Auto",
        GO(go.Shape, "Rectangle",
          { fill: "lightgreen", stroke: null }),
        GO(go.TextBlock,
          {
            font: "bold 11pt Helvetica, Arial, sans-serif",
            stroke: lightText,
            margin: 8,
            maxSize: new go.Size(160, NaN),
            wrap: go.TextBlock.WrapFit,
            editable: true
          },
          new go.Binding("text", "name").makeTwoWay())
      ),
      // four named ports, one on each side:
      makePort("T", go.Spot.Top, false, true),
      // makePort("L", go.Spot.Left, true, true),
      // makePort("R", go.Spot.Right, true, true)
      makePort("B", go.Spot.Bottom, true, true)
    ));
  myDiagram.nodeTemplateMap.add("parallel",  // the default category
    GO(go.Node, "Spot", nodeStyle(),
      // the main object is a Panel that surrounds a TextBlock with a rectangular Shape
      GO(go.Panel, "Auto",
        GO(go.Shape, "Rectangle",
          { fill: "lightblue", stroke: null }),
        GO(go.TextBlock,
          {
            font: "bold 11pt Helvetica, Arial, sans-serif",
            stroke: lightText,
            margin: 8,
            maxSize: new go.Size(160, NaN),
            wrap: go.TextBlock.WrapFit,
            editable: true
          },
          new go.Binding("text", "name").makeTwoWay())
      ),
      // four named ports, one on each side:
      makePort("T", go.Spot.Top, false, true),
      // makePort("L", go.Spot.Left, true, true),
      // makePort("R", go.Spot.Right, true, true)
      makePort("B", go.Spot.Bottom, true, true)
    ));
  myDiagram.nodeTemplateMap.add("merging",  // the default category
    GO(go.Node, "Spot", nodeStyle(),
      // the main object is a Panel that surrounds a TextBlock with a rectangular Shape
      GO(go.Panel, "Auto",
        GO(go.Shape, "Rectangle",
          { fill: "yellow", stroke: null }),
        GO(go.TextBlock,
          {
            font: "bold 11pt Helvetica, Arial, sans-serif",
            stroke: lightText,
            margin: 8,
            maxSize: new go.Size(160, NaN),
            wrap: go.TextBlock.WrapFit,
            editable: true
          },
          new go.Binding("text", "name").makeTwoWay())
      ),
      // four named ports, one on each side:
      makePort("T", go.Spot.Top, false, true),
      // makePort("L", go.Spot.Left, true, true),
      // makePort("R", go.Spot.Right, true, true)
      makePort("B", go.Spot.Bottom, true, true)
    ));
  myDiagram.nodeTemplateMap.add("executive",  // the default category
    GO(go.Node, "Spot", nodeStyle(),
      // the main object is a Panel that surrounds a TextBlock with a rectangular Shape
      GO(go.Panel, "Auto",
        GO(go.Shape, "Rectangle",
          { fill: "lightcoral", stroke: null }),
        GO(go.TextBlock,
          {
            font: "bold 11pt Helvetica, Arial, sans-serif",
            stroke: lightText,
            margin: 8,
            maxSize: new go.Size(160, NaN),
            wrap: go.TextBlock.WrapFit,
            editable: true
          },
          new go.Binding("text", "name").makeTwoWay()),
          GO(go.TextBlock,
            {
              font: "bold 11pt Helvetica, Arial, sans-serif",
              stroke: lightText,
              margin: 8,
              maxSize: new go.Size(160, NaN),
              wrap: go.TextBlock.WrapFit,
              editable: true
            },
          new go.Binding("text", "description").makeTwoWay())
      ),
      // four named ports, one on each side:
      makePort("T", go.Spot.Top, false, true),
      // makePort("L", go.Spot.Left, true, true),
      // makePort("R", go.Spot.Right, true, true)
      makePort("B", go.Spot.Bottom, true, true)
    ));
  // myDiagram.nodeTemplateMap.add("Comment",
  //   GO(go.Node, "Auto", nodeStyle(),
  //     GO(go.Shape, "File",
  //       { fill: "#EFFAB4", stroke: null }),
  //     GO(go.TextBlock,
  //       {
  //         margin: 5,
  //         maxSize: new go.Size(200, NaN),
  //         wrap: go.TextBlock.WrapFit,
  //         textAlign: "center",
  //         editable: true,
  //         font: "bold 12pt Helvetica, Arial, sans-serif",
  //         stroke: '#454545'
  //       },
  //       new go.Binding("text", "config").makeTwoWay())
  //     // no ports, because no links are allowed to connect with a comment
  //   ));
  // replace the default Link template in the linkTemplateMap
  myDiagram.linkTemplate =
    GO(go.Link,  // the whole link panel
      {
        routing: go.Link.AvoidsNodes,
        curve: go.Link.JumpOver,
        corner: 5, toShortLength: 4,
        relinkableFrom: true,
        relinkableTo: true,
        reshapable: true,
        resegmentable: true,
        // mouse-overs subtly highlight links:
        mouseEnter: function(e, link) { link.findObject("HIGHLIGHT").stroke = "rgba(30,144,255,0.2)"; },
        mouseLeave: function(e, link) { link.findObject("HIGHLIGHT").stroke = "transparent"; }
      },
      new go.Binding("points").makeTwoWay(),
      GO(go.Shape,  // the highlight shape, normally transparent
        { isPanelMain: true, strokeWidth: 8, stroke: "transparent", name: "HIGHLIGHT" }),
      GO(go.Shape,  // the link path shape
        { isPanelMain: true, stroke: "gray", strokeWidth: 2 }),
      GO(go.Shape,  // the arrowhead
        { toArrow: "standard", stroke: null, fill: "gray"}),
      GO(go.Panel, "Auto",  // the link label, normally not visible
        { visible: false, name: "LABEL", segmentIndex: 2, segmentFraction: 0.5},
        new go.Binding("visible", "visible").makeTwoWay(),
        GO(go.Shape, "RoundedRectangle",  // the label shape
          { fill: "#F8F8F8", stroke: null }),
        GO(go.TextBlock, "Yes",  // the label
          {
            textAlign: "center",
            font: "10pt helvetica, arial, sans-serif",
            stroke: "#333333",
            editable: true
          },
          new go.Binding("text", "text").makeTwoWay())
      )
    );

  // temporary links used by LinkingTool and RelinkingTool are also orthogonal:
  myDiagram.toolManager.linkingTool.temporaryLink.routing = go.Link.Orthogonal;
  myDiagram.toolManager.relinkingTool.temporaryLink.routing = go.Link.Orthogonal;
  load();  // load an initial diagram from some JSON text
  // initialize the Palette that is on the left side of the page
  myPalette =
    GO(go.Palette, "myPalette",  // must name or refer to the DIV HTML element
      {
        initialContentAlignment: go.Spot.Center,
        "animationManager.duration": 800, // slightly longer than default (600ms) animation
        nodeTemplateMap: myDiagram.nodeTemplateMap,  // share the templates used by myDiagram
        model: new go.GraphLinksModel([  // specify the contents of the Palette
          // { category: "Start", text: "Start" },
          { name: "continous", category: "continous", units: [] },
          { name: "parallel", category: "parallel", units: [] },
          { name: "merging", category: "merging", units: [] },
          { name: "executive", category: "executive", units: [] },
          // { text: "???", figure: "Diamond" },
          // { category: "End", text: "End" },
          // { category: "Comment", text: "Comment" }
        ])
      });
  listSchema();
  getFormats();
}

// Make link labels visible if coming out of a "conditional" node.
// This listener is called by the "LinkDrawn" and "LinkRelinked" DiagramEvents.
function showLinkLabel(e) {
  var label = e.subject.findObject("LABEL");
  if (label !== null) label.visible = (e.subject.fromNode.data.figure === "Diamond");
}

// Make all ports on a node visible when the mouse is over the node
function showPorts(node, show) {
  var diagram = node.diagram;
  if (!diagram || diagram.isReadOnly || !diagram.allowLink) return;
  node.ports.each(function(port) {
      port.stroke = (show ? "gray" : null);
  });
}

function showDetail(node) {
  var units = node.data.units ? parseConfig(node.data.units) : [];
  document.getElementById("nodeDetail").value = units;
  document.getElementById("nodeDetail").dataset.key = (node.data.key);
}

function parseConfig(units) {
  return JSON.stringify(units);
}

function saveDetail() {
  myDiagram.startTransaction("modify node detail");
  var key = document.getElementById("nodeDetail").dataset.key;
  var units = document.getElementById("nodeDetail").value;
  var node = myDiagram.findNodeForKey(key);
  node.data.units = (units) ? JSON.parse(units) : [];
  myDiagram.isModified = true;
  save();
  myDiagram.commitTransaction("modify node detail");
}

// Show the diagram's model in JSON format that the user may edit
function save() {
  document.getElementById("nodeDataArray").value =    JSON.stringify(myDiagram.model.nodeDataArray);
  document.getElementById("linkDataArray").value =    JSON.stringify(myDiagram.model.linkDataArray);
  myDiagram.isModified = false;
}

function load() {
  var model = go.GraphObject.make(go.GraphLinksModel);
  model.nodeDataArray = JSON.parse(document.getElementById("nodeDataArray").value);
  model.linkDataArray = JSON.parse(document.getElementById("linkDataArray").value);
  myDiagram.model = model;
}

function ajax(method, url, data, callback) {
  var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (xhttp.readyState == 4) {
        if (xhttp.status == 200) {
          callback(xhttp.responseText);
        } else {
          alert("**error**");
        }
      }
    }
    xhttp.open(method, url, true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(serialize(data));
}

function serialize(data) {
  var format = ['id', 'name', 'description', 'nodes', 'links', 'test'];
  var string = '';
  for (var i = 0; i < format.length; i++) {
    if (data[format[i]]) string +=
      encodeURIComponent(format[i]) + '=' + encodeURIComponent(data[format[i]]) + '&';
  }
  return string;
}

function listSchema() {
  ajax('GET', 'backend/index.php', {},
    function (data) {
      data = JSON.parse(data);
      // get reference to select element
      var sel = document.getElementById('schemaList');
      sel.options.length = 0;
      sel.onchange = function () {
        var selected = this.options[this.selectedIndex].value;
        document.getElementById('schemaDetail').dataset.id = selected;
        getSchema(selected);
      };

      var opt_default = document.createElement('option');
      opt_default.selected = true;
      opt_default.disabled = true;
      opt_default.appendChild(document.createTextNode('-- select a schema --'));
      sel.appendChild(opt_default);

      for (var i = 0; i < data.length; i++) {
        var opt = document.createElement('option'); // create new option element
        // create text node to add to option element (opt)
        opt.appendChild( document.createTextNode(data[i].name) );
        opt.value = data[i].id; // set value property of opt
        sel.appendChild(opt); // add opt to end of select box (sel)
      }
    }
  )
}

function getSchema(id) {
  ajax('GET', 'backend/get.php?id=' + id, {},
    function (data) {
      data = JSON.parse(data);
      document.getElementById('schemaDetail').dataset.id = data.id;
      document.getElementById('schemaName').value = data.name;
      document.getElementById('schemaDescription').value = data.description;
      document.getElementById('nodeDataArray').value = data.nodes;
      document.getElementById('linkDataArray').value = data.links;
      document.getElementById('schemaDetail').dataset.id = data.id;
    }
  )
}

function createSchema() {
  ajax('POST', 'backend/create.php', {
    'name': 'anonymous',
    'description': 'new diagram',
    'nodes': '[]',
    'links': '[]'
  }, function (data) {
    listSchema();
  })
}

function updateSchema() {
  ajax('PUT', 'backend/update.php', {
    'id': document.getElementById('schemaDetail').dataset.id,
    'name': document.getElementById('schemaName').value,
    'description': document.getElementById('schemaDescription').value,
    'nodes': document.getElementById('nodeDataArray').value,
    'links': document.getElementById('linkDataArray').value
  }, function (data) {
    listSchema();
  })
}

function deleteSchema() {
  ajax('DELETE', 'backend/delete.php', {
    'id': document.getElementById('schemaDetail').dataset.id
  }, function (data) {
    listSchema();
  })
}

function getFormats() {
  ajax('GET', 'backend/formats.php', {},
  function (data) {
    parseFormats(data);
  })
}

function parseFormats(data) {
  data = JSON.parse(data);
  var sel = document.getElementById('selectClass');
  sel.onchange = function () {
    var selected = this.options[this.selectedIndex].value
    document.getElementById('unitDetail').value = JSON.stringify(data[selected]);
  };
  for (unit in data) {
    var opt = document.createElement('option');
    opt.value = unit;
    opt.appendChild(document.createTextNode(unit));
    sel.appendChild(opt);
  }
}

function createUnit() {
  var detail = JSON.parse(document.getElementById('nodeDetail').value);
  detail.push(JSON.parse(document.getElementById('unitDetail').value));
  document.getElementById('nodeDetail').value = JSON.stringify(detail);
}

function test() {
  var test = {
    "id": document.getElementById('schemaDetail').dataset.id,
    "test": document.getElementById('testData').value
  };
  ajax('POST', 'backend/test.php', test,
  function (data) {
    document.getElementById('testResult').innerHTML = data;
  });
}
