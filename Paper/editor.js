(function() {
// Install some useful jQuery extensions that we use a lot

$.extend($.fn, {
	orNull: function() {
		return this.length > 0 ? this : null;
	},

	findAndSelf: function(selector) {
		return this.find(selector).add(this.filter(selector));
	}
});

// Import...

var Base = paper.Base,
	PaperScope = paper.PaperScope,
	PaperScript = paper.PaperScript,
	Item = paper.Item,
	Path = paper.Path,
	Group = paper.Group,
	Layer = paper.Layer,
	Segment = paper.Segment,
	Raster = paper.Raster,
	Tool = paper.Tool,
	Component = paper.Component;

// Tell the color component to use a normal text input, so it can receive rgba()
// values. We're going to replace it with spectrum.js anyhow.
Component.prototype._types.color.type = 'text';

// URL Encoding

function decode(string) {
	return RawDeflate.inflate(window.atob(string));
}

function encode(string) {
	return window.btoa(RawDeflate.deflate(string));
}

var script = {
	name: 'Sketch',
	code: ''
};

function getScriptId(script) {
	return script.name + '.sketch.paperjs.org';
}

function getBlobURL(content, type) {
	return URL.createObjectURL(new Blob([content], {
		type: type
	}));
}

function getTimeStamp() {
	var parts = new Date().toJSON().toString().replace(/[-:]/g, '').match(
			/^20(.*)T(.*)\.\d*Z$/);
	return parts[1] + '_' + parts[2]; 
}

function updateHash() {
	window.location.hash = '#S/' + encode(JSON.stringify(script));
}

if (window.location.hash) {
	var hash = window.location.hash.substr(1),
		version = hash.substr(0, 2),
		string = hash.substr(2),
		error = true;
	if (version == 'T/') {
		script.code = decode(string) || '';
		error = false;
	} else if (version == 'S/') {
		try {
			script = JSON.parse(decode(string));
			error = false;
		} catch (e) {
			if (console.error)
				console.error(e);
		}
	}
	if (error) {
		alert('That shared link format is not supported.');
	}
} 
if (!script.code) {
	// Support only one script for now, named 'Untitled'. Later on we'll have
	// a document switcher.
	script.code = Base.pick(
			localStorage[getScriptId(script)],
			// Try legacy storage
			localStorage['paperjs_'
				+ window.location.pathname.match(/\/([^\/]*)$/)[1]],
			''
	);
}

if (!script.name || script.name == 'First Script')
	script.name = 'Sketch';

var scripts = [];
scripts.push(script);

function createPaperScript(element) {
	var runButton = $('.button.script-run', element),
		canvas = $('canvas', element),
		consoleContainer = $('.console', element).orNull(),
		editor,
		session,
		tools = $('.tools', element),
		inspectorInfo = $('.toolbar .info', element),
		source = $('.source', element),
		scope,
		customAnnotations = [],
		ignoreAnnotation = false;

	editor = ace.edit(source.find('.editor')[0]);
	editor.setTheme('ace/theme/bootstrap');
	editor.setShowInvisibles(false);
	editor.setDisplayIndentGuides(true);
	session = editor.getSession();
	session.setValue(script.code);
	session.setMode('ace/mode/javascript');
	session.setUseSoftTabs(true);
	session.setTabSize(4);

	editor.commands.addCommands([{
		name: 'execute',
		bindKey: {
			mac: 'Command-E',
			win: 'Ctrl-E'
		},
		exec: function(editor) {
			$('.button.script-run').trigger('click');
		}
	}, {
		// Dispable settings menu
		name: 'showSettingsMenu',
		bindKey: {
			mac: 'Command-,',
			win: 'Ctrl-,'
		},
		exec: function() {},
		readOnly: true
	}/*, {
		name: "download",
		bindKey: {
			mac: 'Command-S',
			win: 'Ctrl-S'
		},
		exec: function(editor) {
			var link = $('.button.script-download');
			link.trigger('click');
			window.open(link.attr('href'));
		}
	}*/]);

	editor.setKeyboardHandler({
		handleKeyboard: function(data, hashId, keyString, keyCode, event) {
			if (event)
				event.stopPropagation();
		}
	});

	session.on('change', function() {
		// Clear custom annotations whenever the code changes, until next
		// execution.
		if (customAnnotations.length > 0) {
			removeAnnotations(customAnnotations);
			customAnnotations = [];
		}
		script.code = editor.getValue();
		localStorage[getScriptId(script)] = script.code;
	});

	session.on('changeMode', function() {
		// Use the same linting settings as the Paper.js project
		session.$worker.send('setOptions', {
			evil: true,
			regexdash: true,
			browser: true,
			wsh: true,
			trailing: false,
			smarttabs: true,
			sub: true,
			supernew: true,
			laxbreak: true,
			eqeqeq: false,
			eqnull: true,
			loopfunc: true,
			boss: true,
			shadow: true
		});
	});

	// We need to listen to changes in annotations, since the javascript
	// worker changes annotations asynchronously, and would get rid of
	// annotations that we added ourselves (customAnnotations)
	session.on('changeAnnotation', function() {
		if (ignoreAnnotation)
			return;
		var annotations = getAnnotations();
		filterAnnotations(annotations);
		if (customAnnotations.length > 0)
			annotations = annotations.concat(customAnnotations);
		setAnnotations(annotations);
		updateHash();
	});

	function getAnnotations() {
		return session.getAnnotations();
	}

	function setAnnotations(annotations) {
		ignoreAnnotation = true;
		session.setAnnotations(annotations);
		ignoreAnnotation = false;
	}

	function filterAnnotations(annotations) {
		for (var i = annotations.length - 1; i >= 0; i--) {
			var text = annotations[i].text;
			if (/^Use '[=!]=='/.test(text) 
					|| /is already defined/.test(text)
					|| /Missing semicolon/.test(text)
					|| /'debugger' statement/.test(text)) {
				annotations.splice(i, 1);
			}
		}
	}

	function removeAnnotations(list) {
		var annotations = getAnnotations();
		for (var i = annotations.length - 1; i >= 0; i--) {
			if (list.indexOf(annotations[i]) !== -1)
				annotations.splice(i, 1);
		}
		setAnnotations(annotations);
	}

	function evaluateCode() {
		scope.setup(canvas[0]);
		scope.execute(script.code);
		createInspector();
		setupTools();
		setupPalettes();
	}

	function runCode() {
		// Update the hash each time the code is run also.
		updateHash();
		removeAnnotations(customAnnotations);
		customAnnotations = [];
		// In order to be able to install our own error handlers first, we are
		// not relying on automatic script loading, which is disabled by the use
		// of data-paper-ignore="true". So we need to create a new paperscope
		// each time.
		if (scope)
			scope.remove();
		scope = new PaperScope();
		setupConsole();
		extendScope();
		// parseInclude() triggers evaluateCode() in the right moment for us.
		parseInclude();
	}

	if (consoleContainer) {
		// Append to a container inside the console, so css can use :first-child
		consoleContainer = $('<div class="content">').appendTo(consoleContainer);
	}

	var realConsole = window.console;

	function setupConsole() {
		if (!consoleContainer)
			return;
		// Override the console object with one that logs to our new console.

		// Use ower own toString function that's smart about how to log things:
		function toString(obj, indent, asValue) {
			var type = typeof obj;
			if (obj == null) {
				return type === 'object' ? 'null' : 'undefined';
			} else if (type === 'string') {
				return asValue ? "'" + obj.replace(/'/g, "\\'") + "'" : obj;
			} else if (type === 'object') {
				// If the object provides it's own toString, use it, except for
				// objects and arrays, since we override those.
				if (obj.toString !== Object.prototype.toString
					&& obj.toString !== Array.prototype.toString) {
					return obj.toString();
				} else if (Base.isPlainObject(obj)) {
					if (indent != null)
						indent += '  ';
					return (indent ? '{\n' : '{')
							+ Base.each(obj, function(value, key) {
								this.push(indent + key + ': '
										+ toString(value, indent, true));
							}, []).join(indent != null ? ',\n' : ', ')
							+ (indent
								? '\n' + indent.substring(0, indent.length - 2)
									+ '}'
								: ' }');
				} else if (typeof obj.length === 'number') {
					return '[ ' + Base.each(obj, function(value, index) {
						this[index] = toString(value, indent, true);
					}, []).join(', ') + ' ]';
				}
			}
			return obj.toString();
		}

		function print(action, args) {
			// Log to the real console as well
			var func = realConsole[action];
			if (func)
				func.apply(realConsole, args);
			$('<div>')
				.addClass('line ' + action)
				.text(Base.each(args, function(arg) {
						this.push(toString(arg, ''));
					}, []).join(' '))
				.appendTo(consoleContainer);
			consoleContainer.scrollTop(consoleContainer.prop('scrollHeight'));
		}

		scope.console = {
			log: function() {
				print('log', arguments);
			},

			error: function() {
				print('error', arguments);
			},

			warn: function() {
				print('warn', arguments);
			},

			clear: function() {
				consoleContainer.children().remove();
			}
		};
	}

	function clearConsole() {
		if (scope.console)
			scope.console.clear();
	}

	function updateView() {
		if (scope && scope.view)
			scope.view.draw(true);
	}

	// Install an error handler to log the errors in our log too:
	window.onerror = function(error, url, lineNumber) {
		var columNumber = 0,
			match;
		if (match = error.match(/(.*)\s*\((\d*):(\d*)\)/)) { // Acorn
			error = match[1];
			lineNumber = match[2];
			columNumber = match[3];
		} else if (match = error.match(/(.*)Line (\d*):\s*(.*)/i)) { // Esprima
			error = match[1] + match[3];
			lineNumber = match[2];
		}
		if (lineNumber) {
			var annotation = { 
				row: lineNumber - 1, 
				column: columNumber, 
				text: error, 
				type: 'error'
			};
			var annotations = getAnnotations();
			annotations.push(annotation);
			setAnnotations(annotations);
			customAnnotations.push(annotation);
			editor.gotoLine(lineNumber, columNumber);
		}
		scope.console.error('Line ' + lineNumber + ': ' + error);
		updateView();
	};

	function extendScope() {
		scope.Http = { 
			request: function(options) {
				var url = options.url,
					nop = function() {};
				return $.ajax($.extend({
					dataType: (url.match(/\.(json|xml|html)$/) || [])[1],
					success: function(data) {
						(options.onSuccess || nop)(data);
					},
					error: function(xhr, error) {
						(options.onError || nop)(error);
					},
					complete: function() {
						(options.onComplete || nop)();
						updateView();
					}
				}, options));
			}
		};
	}

	function parseInclude() {
		var includes = [];
		// Parse code for includes, and load them synchronously, if present
		script.code.replace(
			/(?:^|[\n\r])include\(['"]([^)]*)['"]\)/g,
			function(all, url) {
				includes.push(url);
			}
		);

		// Install empty include() function, so code can execute include()
		// statements, which we process separately above.
		scope.include = function(url) {
		};

		// Load all includes sequentially, and finally evaluate code, since 
		// the code will probably be interdependent.
		function load() {
			var path = includes.shift();
			if (path) {
				var url = /^\/lib\//.test(path) ? path.substring(1) : path;
				// Switch to the editor console globally so loaded libraries use
				// our own console too:
				window.console = scope.console;
				$.getScript(url, load).fail(function(xhr, error) {
					scope.console.error('Cannot load ' + path + ': ' + error);
				});
			} else {
				evaluateCode();
				window.console = realConsole;
			}
		}
		cleanupLibraries();
		load();
	}

	function cleanupLibraries() {
		// TODO: Use IFRAME instead!
		if (window.soundManager) {
			$('#' + soundManager.id).remove();
			delete window.soundManager;
		}
	}

	var inspectorTool,
		prevSelection;

	function createInspector() {
		inspectorTool = new Tool();
		inspectorTool.buttonClass = 'icon-cursor';
		prevSelection = null;

		function deselect() {
			if (prevSelection) {
				// prevSelection can be an Item or a Segment
				var item = prevSelection.path || prevSelection;
				item.bounds.selected = false;
				item.selected = false;
				prevSelection.selected = false;
				prevSelection = null;
			}
		}

		inspectorTool.on({
			mousedown: function(event) {
				deselect();
				var selection = event.item;
				if (selection) {
					var handle = selection.hitTest(event.point, {
						segments: true,
						tolerance: 4
					});
					if (handle && handle.type !== 'segment')
						handle = null;
					selection.bounds.selected = !handle;
					if (handle)
						selection = handle.segment;
					selection.selected = true;
				}
				inspectorInfo.toggleClass('hidden', !selection);
				inspectorInfo.html('');
				if (selection) {
					var text;
					if (selection instanceof Segment) {
						text = 'Segment';
						text += '<br>point: ' + selection.point;
						if (!selection.handleIn.isZero())
							text += '<br>handleIn: ' + selection.handleIn;
						if (!selection.handleOut.isZero())
							text += '<br>handleOut: ' + selection.handleOut;
					} else {
						text = selection.constructor.name;
						text += '<br>position: ' + selection.position;
						text += '<br>bounds: ' + selection.bounds;
					}
					inspectorInfo.html(text);
				}
				prevSelection = selection;
			},

			deactivate: function() {
				deselect();
				inspectorInfo.addClass('hidden');
				inspectorInfo.html('');
				updateView();
			}
		});

		var lastPoint;
		var body = $('body');
		zoomTool = new Tool();
		zoomTool.buttonClass = 'icon-zoom-in';
		zoomTool.on({
			mousedown: function(event) {
				if (event.modifiers.space) {
					lastPoint = paper.view.projectToView(event.point);
					return;
				}
				var factor = 1.25;
				if (event.modifiers.option)
					factor = 1 / factor;
				paper.view.center = event.point;
				paper.view.zoom *= factor;
			},
			keydown: function(event) {
				if (event.key === 'option')
					body.addClass('zoom-out');
				else if (event.key === 'space')
					body.addClass('zoom-move');
			},
			keyup: function(event) {
				if (event.key === 'option')
					body.removeClass('zoom-out');
				else if (event.key === 'space')
					body.removeClass('zoom-move');
			},
			mousedrag: function(event) {
				if (event.modifiers.space) {
					body.addClass('zoom-grab');
					// In order to have coordinate changes not mess up the
					// dragging, we need to convert coordinates to view space,
					// and then back to project space after the view space has
					// changed.
					var point = paper.view.projectToView(event.point),
						last = paper.view.viewToProject(lastPoint);
					paper.view.scrollBy(last.subtract(event.point));
					lastPoint = point;
				}
			},
			mouseup: function(event) {
				body.removeClass('zoom-grab');
			},
			activate: function() {
				body.addClass('zoom');
			},
			deactivate: function() {
				body.removeClass('zoom');
			}
		});
	}

	function setupPalettes() {
		// Loop through all palettes and components, and replace simple HTML5
		// color choosers with much improved spectrum.js ones.
		var palettes = paper.palettes;
		for (var i = 0, l = palettes.length; i < l; i ++) {
			var palette = palettes[i],
				components = palette.components;
			paper.Base.each(components, function(component) {
				if (component.type == 'color') {
					var input = $(component._input);
					input.spectrum({
						appendTo: $('.canvas'),
						flat: false,
						allowEmpty: false,
						showButtons: true,
						showInitial: true,
						showPalette: true,
						showSelectionPalette: true,
						showAlpha: true,
						clickoutFiresChange: true,
						change: function(value) {
							component.value = value + '';
						}
					});
					// Hide on mousedown already, not just on click
					canvas.on('mousedown', function(event) {
						input.spectrum('hide', event);
					});
				}
			});
		}
	}

	function setupTools() {
		$('.tool', tools).remove();
		for (var i = paper.tools.length - 1; i >= 0; i--) {
			// Use an iteration closure so we have private variables.
			(function(tool) {
				var title = tool.buttonTitle || '',
					button = $('<a class="button tool">' + title + '</a>')
						.prependTo(tools);
				button.addClass(tool.buttonClass || 'icon-pencil');
				button.click(function() {
					tool.activate();
				}).mousedown(function() {
					return false;
				});
				tool.on({
					activate: function() {
						button.addClass('active');
					},
					deactivate: function() {
						button.removeClass('active');
					}
				});
			})(paper.tools[i]);
		}
		// Activate first tool now, so it gets highlighted too
		var tool = paper.tools[0];
		if (tool)
			tool.activate();
	}

	var panes = element.findAndSelf('.split-pane');
	panes.each(function() {
		var pane = $(this);
		pane.split({
			orientation: pane.attr('data-orientation') == 'hor'
				? 'vertical'
				: 'horizontal',
			position: pane.attr('data-percentage'),
			limit: 100
		});
	});

	// Refresh editor if parent gets resized
	$('.editor', element).parents('.split-pane').on('splitter.resize', function() {
		editor.resize();
	});

	canvas.parents('.split-pane').on('splitter.resize', function() {
		var pane = $('.canvas', element);
		if (scope && scope.view) {
			scope.view.setViewSize(pane.width(), pane.height());
		}
	});

	$(window).resize(function() {
		// Do not have .paperscript automatically resize to 100%, instead
		// resize it in the resize handler, for much smoother redrawing,
		// since the splitter panes are aligning using right: 0 / bottom: 0.
		element.width($(window).width()).height($(window).height());
		if (editor)
			panes.trigger('splitter.resize');
	}).trigger('resize');

	// Run the script once the window is loaded
	if (window.location.search != '?fix')
		$(window).load(runCode);

	$('.button', element).mousedown(function() {
		return false;
	});

	runButton.click(function() {
		runCode();
		return false;
	});

	$('.button.canvas-export-svg', element).click(function() {
		var svg = scope.project.exportSVG({ asString: true });
		this.href = getBlobURL(svg, 'image/svg+xml');
		this.download = 'Export_' + getTimeStamp() + '.svg';
	});

	$('.button.canvas-export-json', element).click(function() {
		var svg = scope.project.exportJSON();
		this.href = getBlobURL(svg, 'text/json');
		this.download = 'Export_' + getTimeStamp() + '.json';
	});

	$('.button.script-download', element).click(function() {
		this.href = getBlobURL(script.code, 'text/javascript');
		this.download = script.name + '_' + getTimeStamp() + '.js';
	});

	$('.button.canvas-clear', element).click(function() {
		if (!paper.project.isEmpty() && confirm(
				'This clears the whole canvas.\nAre you sure to proceed?')) {
			scope.project.clear();
			new Layer();
			updateView();
		}
	});

	$('.button.console-clear', element).click(function() {
		clearConsole();
	});
}

$(function() {
	if (window.location.search === '?large')
		$('body').addClass('large');
	createPaperScript($('.paperscript'));
});

})();