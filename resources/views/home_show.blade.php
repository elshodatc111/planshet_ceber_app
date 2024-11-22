@extends('layouts.app')

@section('content')
<meta property="og:image" content="https://gojs.net/latest/assets/images/screenshots/orgChartEditor.png" />
<meta name="csrf-token" content="{{ csrf_token() }}">
  <meta itemprop="image" content="https://gojs.net/latest/assets/images/screenshots/orgChartEditor.png" />
  <meta name="twitter:image" content="https://gojs.net/latest/assets/images/screenshots/orgChartEditor.png" />
  <meta property="og:url" content="https://gojs.net/latest/samples/orgChartEditor.html" />
  <meta property="twitter:url" content="https://gojs.net/latest/samples/orgChartEditor.html" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script>
    window.addEventListener("DOMContentLoaded", function () {
      var topButton = document.getElementById("topnavButton");
      var topnavList = document.getElementById("topnavList");
      if (topButton && topnavList) {
        topButton.addEventListener("click", function (e) {
          topnavList
            .classList
            .toggle("hidden");
          e.stopPropagation();
        });
        document.addEventListener("click", function (e) {
          if (!topnavList.classList.contains("hidden") && !e.target.closest("#topnavList")) {
            topButton.click();
          }
        });
        var url = window
          .location
          .href
          .toLowerCase();
        var aTags = topnavList.getElementsByTagName('a');
        for (var i = 0; i < aTags.length; i++) {
          var lowerhref = aTags[i]
            .href
            .toLowerCase();
          if (url.startsWith(lowerhref)) {
            aTags[i]
              .classList
              .add('active');
            break;
          }
        }
      }
    });
  </script>
  <div class="flex flex-col prose">
    <div class="w-full max-w-screen-xl mx-auto">
      <script src="../release/go.js"></script>
      <div id="allSampleContent" class="p-4 w-full">
        <link href="https://fonts.googleapis.com/css?family=Inter:400,500,600&subset=latin,latin-ext" rel="stylesheet" type="text/css" />
        <style>
          :modal {
            padding: 2rem;
            border-radius: 0.25rem;
            border-width: 0;
            box-shadow:
              0 0 #0000,
              0 0 #0000,
              0 1px 3px 0 rgb(0 0 0 / 0.1);
          }
          #hidden {
            font: 500 18px Inter;
            opacity: 0;
          }
        </style>
        <script src="../extensions/DataInspector.js"></script>
        <script id="code">
          function init() {
            myDiagram = new go.Diagram('myDiagramDiv', {
              allowCopy: false,
              allowDelete: false,
              initialAutoScale: go.AutoScale.UniformToFill,
              maxSelectionCount: 1, 
              validCycle: go.CycleMode.DestinationTree, 
              'clickCreatingTool.archetypeNodeData': {
                name: '(New person)',
                card: '(Card Number)',
                passnumber: '(Pasport Number)',
                phone: '(Phone Number)',
                description: '(There are description)' 
              },
              'clickCreatingTool.insertPart': function (loc) {
                const node = go.ClickCreatingTool.prototype.insertPart.call(this, loc);
                if (node !== null) {
                  this.diagram.select(node);
                  this.diagram.commandHandler.scrollToPart(node);
                  this.diagram.commandHandler.editTextBlock(node.findObject('NAMETB'));
                }
                return node;
              },
              layout: new go.TreeLayout({
                treeStyle: go.TreeStyle.LastParents,
                arrangement: go.TreeArrangement.Horizontal,
                angle: 90,
                layerSpacing: 35,
                alternateAngle: 90,
                alternateLayerSpacing: 35,
                alternateAlignment: go.TreeAlignment.Bus,
                alternateNodeSpacing: 20
              }),
              'undoManager.isEnabled': true, // enable undo & redo
              'themeManager.changesDivBackground': true,
              'themeManager.currentTheme': document.getElementById('theme').value
            });
            myDiagram.addDiagramListener('Modified', (e) => {
              const button = document.getElementById('SaveButton');
              if (button) button.disabled = !myDiagram.isModified;
              const idx = document.title.indexOf('*');
              if (myDiagram.isModified) {
                if (idx < 0) document.title += '*';
              } else {
                if (idx >= 0) document.title = document.title.slice(0, idx);
              }
            });
            myDiagram.themeManager.set('dark', {
              colors: {
                background: '#fff',
                text: '#111827',
                textHighlight: '#11a8cd',
                subtext: '#6b7280',
                badge: '#f0fdf4',
                badgeBorder: '#16a34a33',
                badgeText: '#15803d',
                divider: '#6b7280',
                shadow: '#9ca3af',
                tooltip: '#1f2937',
                levels: [
                  '#AC193D',
                  '#2672EC',
                  '#8C0095',
                  '#5133AB',
                  '#008299',
                  '#D24726',
                  '#008A00',
                  '#094AB2'
                ],
                dragOver: '#f0f9ff',
                link: '#9ca3af',
                div: '#f3f4f6'
              },
              fonts: {
                name: '500 0.875rem InterVariable, sans-serif',
                normal: '0.875rem InterVariable, sans-serif',
                badge: '500 0.75rem InterVariable, sans-serif',
                link: '600 0.875rem InterVariable, sans-serif'
              }
            });
            myDiagram.themeManager.set('light', {
              colors: {
                background: '#111827',
                text: '#fff',
                subtext: '#d1d5db',
                badge: '#22c55e19',
                badgeBorder: '#22c55e21',
                badgeText: '#4ade80',
                shadow: '#111827',
                dragOver: '#082f49',
                link: '#6b7280',
                div: '#1f2937'
              }
            });
            function mayWorkFor(node1, node2) {
              if (!(node1 instanceof go.Node)) return false; 
              if (node1 === node2) return false; 
              if (node2.isInTreeOf(node1)) return false; 
              return true;
            }
            function findHeadShot(pic) {
              if (!pic) return '../samples/images/user.svg';
              return '../samples/images/HS' + pic;
            }
            function findLevelColor(node) {
              return node.findTreeLevel();
            }
            function toolTipTextConverter(obj) {
              if (obj.name === 'EMAIL') return obj.part.data.email;
              if (obj.name === 'PHONE') return obj.part.data.phone;
            }
            function toolTipAlignConverter(obj, tt) {
              const d = obj.diagram;
              const bot = obj.getDocumentPoint(go.Spot.Bottom);
              const viewPt = d.transformDocToView(bot).offset(0, 35);
              const align =
                d.viewportBounds.height >= viewPt.y / d.scale
                  ? new go.Spot(0.5, 1, 0, 6)
                  : new go.Spot(0.5, 0, 0, -6);

              tt.alignment = align;
              tt.alignmentFocus = align.y === 1 ? go.Spot.Top : go.Spot.Bottom;
            }
            const toolTip = new go.Adornment(go.Panel.Spot, {
              isShadowed: true,
              shadowOffset: new go.Point(0, 2)
            })
              .add(
                new go.Placeholder(),
                new go.Panel(go.Panel.Auto)
                  .add(
                    new go.Shape('RoundedRectangle', { strokeWidth: 0, shadowVisible: true }).theme('fill', 'background'),
                    new go.TextBlock({ margin: 2 })
                      .bindObject('text', 'adornedObject', toolTipTextConverter)
                      .theme('stroke', 'text')
                      .theme('font', 'normal')
                  )
                  .bindObject('', 'adornedObject', toolTipAlignConverter)
              )
              .theme('shadowColor', 'shadow');
            myDiagram.nodeTemplate = new go.Node(go.Panel.Spot, {
              isShadowed: true,
              shadowOffset: new go.Point(0, 2),
              selectionObjectName: 'BODY',
              mouseEnter: (e, node) =>
                (node.findObject('BUTTON').opacity = node.findObject('BUTTONX').opacity = 1),
              mouseLeave: (e, node) => {
                if (node.isSelected) return; // if selected dont hide buttons
                node.findObject('BUTTON').opacity = node.findObject('BUTTONX').opacity = 0;
              },
              mouseDragEnter: (e, node, prev) => {
                const diagram = node.diagram;
                const selnode = diagram.selection.first();
                if (!mayWorkFor(selnode, node)) return;
                const shape = node.findObject('SHAPE');
                if (shape) {
                  shape._prevFill = shape.fill; 
                  shape.fill = diagram.themeManager.findValue('dragOver', 'colors'); 
                }
              },
              mouseDragLeave: (e, node, next) => {
                const shape = node.findObject('SHAPE');
                if (shape && shape._prevFill) {
                  shape.fill = shape._prevFill; 
                }
              },
              mouseDrop: (e, node) => {
                const diagram = node.diagram;
                const selnode = diagram.selection.first();
                if (mayWorkFor(selnode, node)) {
                  const link = selnode.findTreeParentLink();
                  if (link !== null) {
                    link.fromNode = node;
                  } else {
                    diagram.toolManager.linkingTool.insertLink(node, node.port, selnode, selnode.port);
                  }
                }
              }
            })
              .add(
                new go.Panel(go.Panel.Auto, { name: 'BODY' })
                  .add(
                    new go.Shape('RoundedRectangle', {
                      name: 'SHAPE',
                      strokeWidth: 0,
                      portId: '',
                      spot1: go.Spot.TopLeft,
                      spot2: go.Spot.BottomRight
                    }).theme('fill', 'background'),
                    new go.Panel(go.Panel.Table, { margin: 0.5, defaultRowSeparatorStrokeWidth: 0.5 })
                      .theme('defaultRowSeparatorStroke', 'divider')
                      .add(
                        new go.Panel(go.Panel.Table, { padding: new go.Margin(18, 18, 18, 24) })
                          .addColumnDefinition(0, { width: 230 }) //Kartani enini belgilash
                          .add(
                            new go.Panel(go.Panel.Table, {
                              column: 0,
                              alignment: go.Spot.Left,
                              stretch: go.Stretch.Vertical,
                              defaultAlignment: go.Spot.Left
                            })
                              .add(
                                new go.Panel(go.Panel.Horizontal, { row: 0 })
                                  .add(
                                    new go.TextBlock({ editable: true, minSize: new go.Size(10, 14) })
                                      .bindTwoWay('text', 'name')
                                      .theme('stroke', 'text')
                                      .theme('font', 'name'),
                                    new go.Panel(go.Panel.Auto, { margin: new go.Margin(0, 0, 0, 10) })

                                  ),
                                new go.TextBlock({
                                  row: 1,
                                  editable: true,
                                  minSize: new go.Size(10, 14),
                                  font: "bold 20px Arial"
                                })
                                  .bindTwoWay('text', 'card'),
                                new go.TextBlock({
                                  row: 2,
                                  editable: true,
                                  minSize: new go.Size(12, 14),
                                  font: "bold 16px Arial"
                                })
                                  .bindTwoWay('text', 'phone'),
                                  new go.TextBlock({
                                  row: 3,
                                  editable: true,
                                  minSize: new go.Size(12, 14),
                                  font: "bold 16px Arial"
                                })
                                  .bindTwoWay('text', 'passnumber')
                              )
                          ),
                        new go.Panel(go.Panel.Table, {
                          row: 1,
                          stretch: go.Stretch.Horizontal,
                          defaultColumnSeparatorStrokeWidth: 0.5
                        })
                          .theme('defaultColumnSeparatorStroke', 'divider')
                          .add(new go.TextBlock({ desiredSize: new go.Size(200, NaN), editable: true, minSize: new go.Size(14, 18), wrap: go.TextBlock.WrapDesiredSize, overflow: go.TextBlock.OverflowClip })
                            .bindTwoWay('text', 'description')
                            .theme('stroke', 'text')
                            .theme('font', 'name'),)
                      )
                  ), 
                new go.Shape('RoundedLeftRectangle', {
                  alignment: go.Spot.Left,
                  alignmentFocus: go.Spot.Left,
                  stretch: go.Stretch.Vertical,
                  width: 6,
                  strokeWidth: 0
                }).themeObject('fill', '', 'levels', findLevelColor),
                go.GraphObject.build('Button', {
                  name: 'BUTTON',
                  alignment: go.Spot.Right,
                  opacity: 0, 
                  click: (e, button) => addEmployee(button.part)
                })
                  .bindObject('opacity', 'isSelected', (s) => (s ? 1 : 0))
                  .add(
                    new go.Shape('PlusLine', { width: 8, height: 8, stroke: '#0a0a0a', strokeWidth: 2 })
                  ),
                go.GraphObject.build('TreeExpanderButton', {
                  _treeExpandedFigure: 'LineUp',
                  _treeCollapsedFigure: 'LineDown',
                  name: 'BUTTONX',
                  alignment: go.Spot.Bottom,
                  opacity: 0 
                })
                  .bindObject('opacity', 'isSelected', (s) => (s ? 1 : 0))
              )
              .theme('shadowColor', 'shadow')
              .bind('text', 'name')
              .bindObject('layerName', 'isSelected', (sel) => (sel ? 'Foreground' : ''))
              .bindTwoWay('isTreeExpanded');
            function makeBottomButton(name) {
              const phonePath =
                'F M2 3.5A1.5 1.5 0 013.5 2h1.148a1.5 1.5 0 011.465 1.175l.716 3.223a1.5 1.5 0 01-1.052 1.767l-.933.267c-.41.117-.643.555-.48.95a11.542 11.542 0 006.254 6.254c.395.163.833-.07.95-.48l.267-.933a1.5 1.5 0 011.767-1.052l3.223.716A1.5 1.5 0 0118 15.352V16.5a1.5 1.5 0 01-1.5 1.5H15c-1.149 0-2.263-.15-3.326-.43A13.022 13.022 0 012.43 8.326 13.019 13.019 0 012 5V3.5z';
              const emailPath =
                'F M3 4a2 2 0 00-2 2v1.161l8.441 4.221a1.25 1.25 0 001.118 0L19 7.162V6a2 2 0 00-2-2H3zM19 8.839l-7.77 3.885a2.75 2.75 0 01-2.46 0L1 8.839V14a2 2 0 002 2h14a2 2 0 002-2V8.839z';
              const convertSelectedToThemeProp = (s) => (s ? 'textHighlight' : 'text');
              const isEmail = name === 'EMAIL';
              return new go.Panel(go.Panel.Table, {
                mouseEnter: (e, obj) => myDiagram.model.set(obj.part.data, name, true),
                mouseLeave: (e, obj) => myDiagram.model.set(obj.part.data, name, false),
                name,
                background: 'transparent',
                cursor: 'Pointer',
                column: isEmail ? 0 : 1,
                width: 140,
                height: 40,
                toolTip: toolTip,
                click: (e, obj) => {
                  dialog.firstElementChild.firstElementChild.innerHTML =
                    `You clicked to ${isEmail ? 'send email to' : 'call'} ${obj.part.data.name} at ${obj.part.data[name.toLowerCase()]}`;
                  dialog.showModal();
                }
              })
                .add(
                  new go.Panel(go.Panel.Horizontal)
                    .add(
                      new go.Shape({
                        geometryString: isEmail ? emailPath : phonePath,
                        strokeWidth: 0,
                        desiredSize: isEmail ? new go.Size(20, 16) : new go.Size(20, 20),
                        margin: new go.Margin(0, 12, 0, 0)
                      })
                        .theme('fill', 'text')
                        .themeData('fill', name, null, convertSelectedToThemeProp),
                      new go.TextBlock(isEmail ? 'Email' : 'Phone')
                        .theme('stroke', 'text')
                        .themeData('stroke', name, null, convertSelectedToThemeProp)
                        .theme('font', 'link')
                    )
                );
            }
            function addEmployee(node) {
              if (!node) return;
              const thisemp = node.data;
              let newnode;
              myDiagram.model.commit((m) => {
                const newemp = {
                  name: '(New person)',
                  card: '(Card Number)',
                  passnumber: '(Pasport Number)',
                  phone: '(Phone Number)',
                  description: '(There are description)',
                  parent: thisemp.key
                };
                m.addNodeData(newemp);
                newnode = myDiagram.findNodeForData(newemp);
                if (newnode) newnode.location = node.location;
              }, 'add employee');
              myDiagram.commandHandler.scrollToPart(newnode);
            }
            myDiagram.nodeTemplate.contextMenu = go.GraphObject.build('ContextMenu')
              .add(
                go.GraphObject.build('ContextMenuButton', {
                  click: (e, button) => addEmployee(button.part.adornedPart)
                }).add(new go.TextBlock('Keyingi qadam kiritish')),
                go.GraphObject.build('ContextMenuButton', {
                  click: (e, button) => {
                    const node = button.part.adornedPart;
                    if (node !== null) {
                      const thisemp = node.data;
                      myDiagram.model.commit((m) => {
                        m.set(thisemp, 'name', '(Vacant)');
                        m.set(thisemp, 'pic', '');
                        m.set(thisemp, 'email', 'none');
                        m.set(thisemp, 'phone', 'none');
                      }, 'vacate');
                    }
                  }
                }).add(new go.TextBlock('Vacate Position')),
                go.GraphObject.build('ContextMenuButton', {
                  click: (e, button) => {
                    const node = button.part.adornedPart;
                    if (node !== null) {
                      myDiagram.model.commit((m) => {
                        const chl = node.findTreeChildrenNodes();
                        while (chl.next()) {
                          const emp = chl.value;
                          m.setParentKeyForNodeData(emp.data, node.findTreeParentNode().data.key);
                        }
                        m.removeNodeData(node.data);
                      }, 'reparent remove');
                    }
                  }
                }).add(new go.TextBlock('Remove Role')),
                go.GraphObject.build('ContextMenuButton', {
                  click: (e, button) => {
                    const node = button.part.adornedPart;
                    if (node !== null) {
                      myDiagram.commit((d) => d.removeParts(node.findTreeParts()), 'remove dept');
                    }
                  }
                }).add(new go.TextBlock('Remove Department'))
              );
            myDiagram.linkTemplate = new go.Link({
              routing: go.Routing.Orthogonal,
              layerName: 'Background',
              corner: 5
            }).add(new go.Shape({ strokeWidth: 2 }).theme('stroke', 'link')); 
            load();
            myInspector = new Inspector('myInspector', myDiagram, {
              properties: {
                key: { readOnly: true },
                EMAIL: { show: false },
                PHONE: { show: false }
              }
            });
            document
              .getElementById('zoomToFit')
              .addEventListener('click', () => myDiagram.commandHandler.zoomToFit());
            document.getElementById('centerRoot').addEventListener('click', () => {
              myDiagram.scale = 1;
              myDiagram.commandHandler.scrollToPart(myDiagram.findNodeForKey(1));
            });
          } 
          function save() {
            document.getElementById('mySavedModel').value = myDiagram.model.toJson();
            myDiagram.isModified = false;
          }
          function load() {
            myDiagram.model = go.Model.fromJson(document.getElementById('mySavedModel').value);
            let lastkey = 1;
            myDiagram.model.makeUniqueKeyFunction = (model, data) => {
              let k = data.key || lastkey;
              while (model.findNodeDataForKey(k)) k++;
              data.key = lastkey = k;
              return k;
            };
          }
          function changeTheme() {
            const myDiagram = go.Diagram.fromDiv('myDiagramDiv');
            if (myDiagram) {
              myDiagram.themeManager.currentTheme = document.getElementById('theme').value;
            }
          }
          window.addEventListener('DOMContentLoaded', () => {
            dialog = document.querySelector('dialog');
            dialog.addEventListener('click', (e) => {
              dialog.close();
            });
            setTimeout(() => {
              init();
            }, 300);
          });
        </script>
        <div id="sample">
          
        <div class="row mb-2">
          <div class="col-12 text-center h3">{{ $Planshet['name'] }}</div>
            <div class="col-lg-4">
                <button id="zoomToFit" class="btn btn-success w-100">Fit uchun kattalashtirish</button> 
            </div>
            <div class="col-lg-4">
                <button id="centerRoot" class="btn btn-primary w-100">Markazga joylashtirish</button>
            </div>
            <div class="col-lg-4">
                <button id="exportButton" name="exportButton" class="btn btn-warning w-100">Export PDF</button>
            </div>
          </div>
          <div id="myDiagramDiv" style="border: solid 1px black; height: 1000px; weight: 100%"></div>
          Theme:
          <select id="theme" class="form-select" style="width:100px;" onchange="changeTheme()">
            <option value="system">System</option>
            <option value="light">Light</option>
            <option value="dark" selected>Dark</option>
          </select>
          <div class="mt-3">
            <div id="myInspector"></div>
          </div>
          <div>
            <div class="row my-3">
              <div class="col-6">
                <button id="SaveButton" onclick="save()" class="btn btn-success w-100">Saqlash</button>
              </div>
              <div class="col-6">
                <button onclick="load()" class="btn btn-primary w-100">Yuklash</button>
              </div>
            </div>
            <input type="hidden" name="id_id" id="id_id" value="{{ $Planshet['id'] }}">
            <textarea name="mySavedModel" id="mySavedModel" style="width: 100%; height: 270px">
              {{ $Planshet['json'] }}
            </textarea>
            <div class="w-100 text-center">
            @if(auth()->user()->type=='admin')
            <button class="btn btn-primary w-50" id="submit_button">O'zgarishlarni saqlash</button>
            @endif
            <div id="response_message"></div>
            </div>
            <script>
              document.getElementById('submit_button').addEventListener('click', function () {
              const inputField = document.getElementById('id_id').value;
              const textareaField = document.getElementById('mySavedModel').value;
              fetch('/update_post', {
                  method: 'POST',
                  headers: {
                      'Content-Type': 'application/json',
                      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                  },
                  body: JSON.stringify({
                      input_field: inputField,
                      textarea_field: textareaField
                  })
              })
              .then(response => response.json())
              .then(data => {
                  if (data.success) {
                      document.getElementById('response_message').innerHTML = "<p>O'zgarishlar saqlandi</p>";
                  } else {
                      document.getElementById('response_message').innerHTML = "<p>O'zgarishlar saqlamnadi</p>";
                  }
              })
              .catch(error => console.error('Error:', error));
          });
            </script>
          </div>
          <dialog>
            <div style="display: flex; flex-direction: column">
              <p></p>
              <p>Click anywhere to close</p>
            </div>
          </dialog>
          <p id="hidden">this forces the font to load in Chromium browsers</p>
        </div>
      </div>
    </div>
  </div>
  <script src="../assets/js/goSamples.js"></script>
  <script>
    async function exportToPDF() {
      // Diagrammani rasmga aylantirish
      const imageData = myDiagram.makeImageData({
        scale: 1,           // Rasm o'lchami (1 = 100%)
        background: "white" // Orqa fon rangi
      });
      const { jsPDF } = window.jspdf;
      const pdf = new jsPDF({
        orientation: "landscape"
      });
      const imgProps = pdf.getImageProperties(imageData);
      const pdfWidth = pdf.internal.pageSize.getWidth();
      const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;
      pdf.addImage(imageData, "PNG", 0, 0, pdfWidth, pdfHeight, undefined, "FAST");
      pdf.save("diagram.pdf");
    }
    document.getElementById("exportButton").addEventListener("click", exportToPDF);
  </script>
@endsection
