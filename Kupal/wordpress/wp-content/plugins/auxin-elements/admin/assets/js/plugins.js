/*! Phlox Core Plugin - v2.7.12 (2021-04)
 *  All required javascript plugins for admin 
 *  http://phlox.pro/
 *  Place any jQuery/helper plugins in here, instead of separate, slower script files!
 */

if( typeof Object.create !== 'function' ){ Object.create = function (obj){ function F(){} F.prototype = obj; return new F();}; }

/*! 
 * ================== admin/assets/js/libs/featherlight.js =================== 
 **/ 

/**
 * Featherlight - ultra slim jQuery lightbox
 * Version 1.7.12 - http://noelboss.github.io/featherlight/
 *
 * Copyright 2017, Noël Raoul Bossart (http://www.noelboss.com)
 * MIT Licensed.
**/
(function($) {
	"use strict";

	if('undefined' === typeof $) {
		if('console' in window){ window.console.info('Too much lightness, Featherlight needs jQuery.'); }
		return;
	}
	if($.fn.jquery.match(/-ajax/)) {
		if('console' in window){ window.console.info('Featherlight needs regular jQuery, not the slim version.'); }
		return;
	}
	/* Featherlight is exported as $.featherlight.
	   It is a function used to open a featherlight lightbox.

	   [tech]
	   Featherlight uses prototype inheritance.
	   Each opened lightbox will have a corresponding object.
	   That object may have some attributes that override the
	   prototype's.
	   Extensions created with Featherlight.extend will have their
	   own prototype that inherits from Featherlight's prototype,
	   thus attributes can be overriden either at the object level,
	   or at the extension level.
	   To create callbacks that chain themselves instead of overriding,
	   use chainCallbacks.
	   For those familiar with CoffeeScript, this correspond to
	   Featherlight being a class and the Gallery being a class
	   extending Featherlight.
	   The chainCallbacks is used since we don't have access to
	   CoffeeScript's `super`.
	*/

	function Featherlight($content, config) {
		if(this instanceof Featherlight) {  /* called with new */
			this.id = Featherlight.id++;
			this.setup($content, config);
			this.chainCallbacks(Featherlight._callbackChain);
		} else {
			var fl = new Featherlight($content, config);
			fl.open();
			return fl;
		}
	}

	var opened = [],
		pruneOpened = function(remove) {
			opened = $.grep(opened, function(fl) {
				return fl !== remove && fl.$instance.closest('body').length > 0;
			} );
			return opened;
		};

	// Removes keys of `set` from `obj` and returns the removed key/values.
	function slice(obj, set) {
		var r = {};
		for (var key in obj) {
			if (key in set) {
				r[key] = obj[key];
				delete obj[key];
			}
		}
		return r;
	}

	// NOTE: List of available [iframe attributes](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/iframe).
	var iFrameAttributeSet = {
		allowfullscreen: 1, frameborder: 1, height: 1, longdesc: 1, marginheight: 1, marginwidth: 1,
		name: 1, referrerpolicy: 1, scrolling: 1, sandbox: 1, src: 1, srcdoc: 1, width: 1
	};

	// Converts camelCased attributes to dasherized versions for given prefix:
	//   parseAttrs({hello: 1, hellFrozeOver: 2}, 'hell') => {froze-over: 2}
	function parseAttrs(obj, prefix) {
		var attrs = {},
			regex = new RegExp('^' + prefix + '([A-Z])(.*)');
		for (var key in obj) {
			var match = key.match(regex);
			if (match) {
				var dasherized = (match[1] + match[2].replace(/([A-Z])/g, '-$1')).toLowerCase();
				attrs[dasherized] = obj[key];
			}
		}
		return attrs;
	}

	/* document wide key handler */
	var eventMap = { keyup: 'onKeyUp', resize: 'onResize' };

	var globalEventHandler = function(event) {
		$.each(Featherlight.opened().reverse(), function() {
			if (!event.isDefaultPrevented()) {
				if (false === this[eventMap[event.type]](event)) {
					event.preventDefault(); event.stopPropagation(); return false;
			  }
			}
		});
	};

	var toggleGlobalEvents = function(set) {
			if(set !== Featherlight._globalHandlerInstalled) {
				Featherlight._globalHandlerInstalled = set;
				var events = $.map(eventMap, function(_, name) { return name+'.'+Featherlight.prototype.namespace; } ).join(' ');
				$(window)[set ? 'on' : 'off'](events, globalEventHandler);
			}
		};

	Featherlight.prototype = {
		constructor: Featherlight,
		/*** defaults ***/
		/* extend featherlight with defaults and methods */
		namespace:      'featherlight',        /* Name of the events and css class prefix */
		targetAttr:     'data-featherlight',   /* Attribute of the triggered element that contains the selector to the lightbox content */
		variant:        null,                  /* Class that will be added to change look of the lightbox */
		resetCss:       false,                 /* Reset all css */
		background:     null,                  /* Custom DOM for the background, wrapper and the closebutton */
		openTrigger:    'click',               /* Event that triggers the lightbox */
		closeTrigger:   'click',               /* Event that triggers the closing of the lightbox */
		filter:         null,                  /* Selector to filter events. Think $(...).on('click', filter, eventHandler) */
		root:           'body',                /* Where to append featherlights */
		openSpeed:      250,                   /* Duration of opening animation */
		closeSpeed:     250,                   /* Duration of closing animation */
		closeOnClick:   'background',          /* Close lightbox on click ('background', 'anywhere' or false) */
		closeOnEsc:     true,                  /* Close lightbox when pressing esc */
		closeIcon:      '&#10005;',            /* Close icon */
		loading:        '',                    /* Content to show while initial content is loading */
		persist:        false,                 /* If set, the content will persist and will be shown again when opened again. 'shared' is a special value when binding multiple elements for them to share the same content */
		otherClose:     null,                  /* Selector for alternate close buttons (e.g. "a.close") */
		beforeOpen:     $.noop,                /* Called before open. can return false to prevent opening of lightbox. Gets event as parameter, this contains all data */
		beforeContent:  $.noop,                /* Called when content is loaded. Gets event as parameter, this contains all data */
		beforeClose:    $.noop,                /* Called before close. can return false to prevent opening of lightbox. Gets event as parameter, this contains all data */
		afterOpen:      $.noop,                /* Called after open. Gets event as parameter, this contains all data */
		afterContent:   $.noop,                /* Called after content is ready and has been set. Gets event as parameter, this contains all data */
		afterClose:     $.noop,                /* Called after close. Gets event as parameter, this contains all data */
		onKeyUp:        $.noop,                /* Called on key up for the frontmost featherlight */
		onResize:       $.noop,                /* Called after new content and when a window is resized */
		type:           null,                  /* Specify type of lightbox. If unset, it will check for the targetAttrs value. */
		contentFilters: ['jquery', 'image', 'html', 'ajax', 'iframe', 'text'], /* List of content filters to use to determine the content */

		/*** methods ***/
		/* setup iterates over a single instance of featherlight and prepares the background and binds the events */
		setup: function(target, config){
			/* all arguments are optional */
			if (typeof target === 'object' && target instanceof $ === false && !config) {
				config = target;
				target = undefined;
			}

			var self = $.extend(this, config, {target: target}),
				css = !self.resetCss ? self.namespace : self.namespace+'-reset', /* by adding -reset to the classname, we reset all the default css */
				$background = $(self.background || [
					'<div class="'+css+'-loading '+css+'">',
						'<div class="'+css+'-content">',
							'<button class="'+css+'-close-icon '+ self.namespace + '-close" aria-label="Close">',
								self.closeIcon,
							'</button>',
							'<div class="'+self.namespace+'-inner">' + self.loading + '</div>',
						'</div>',
					'</div>'].join('')),
				closeButtonSelector = '.'+self.namespace+'-close' + (self.otherClose ? ',' + self.otherClose : '');

			self.$instance = $background.clone().addClass(self.variant); /* clone DOM for the background, wrapper and the close button */

			/* close when click on background/anywhere/null or closebox */
			self.$instance.on(self.closeTrigger+'.'+self.namespace, function(event) {
				if(event.isDefaultPrevented()) {
					return;
				}
				var $target = $(event.target);
				if( ('background' === self.closeOnClick  && $target.is('.'+self.namespace))
					|| 'anywhere' === self.closeOnClick
					|| $target.closest(closeButtonSelector).length ){
					self.close(event);
					event.preventDefault();
				}
			});

			return this;
		},

		/* this method prepares the content and converts it into a jQuery object or a promise */
		getContent: function(){
			if(this.persist !== false && this.$content) {
				return this.$content;
			}
			var self = this,
				filters = this.constructor.contentFilters,
				readTargetAttr = function(name){ return self.$currentTarget && self.$currentTarget.attr(name); },
				targetValue = readTargetAttr(self.targetAttr),
				data = self.target || targetValue || '';

			/* Find which filter applies */
			var filter = filters[self.type]; /* check explicit type like {type: 'image'} */

			/* check explicit type like data-featherlight="image" */
			if(!filter && data in filters) {
				filter = filters[data];
				data = self.target && targetValue;
			}
			data = data || readTargetAttr('href') || '';

			/* check explicity type & content like {image: 'photo.jpg'} */
			if(!filter) {
				for(var filterName in filters) {
					if(self[filterName]) {
						filter = filters[filterName];
						data = self[filterName];
					}
				}
			}

			/* otherwise it's implicit, run checks */
			if(!filter) {
				var target = data;
				data = null;
				$.each(self.contentFilters, function() {
					filter = filters[this];
					if(filter.test)  {
						data = filter.test(target);
					}
					if(!data && filter.regex && target.match && target.match(filter.regex)) {
						data = target;
					}
					return !data;
				});
				if(!data) {
					if('console' in window){ window.console.error('Featherlight: no content filter found ' + (target ? ' for "' + target + '"' : ' (no target specified)')); }
					return false;
				}
			}
			/* Process it */
			return filter.process.call(self, data);
		},

		/* sets the content of $instance to $content */
		setContent: function($content){
      this.$instance.removeClass(this.namespace+'-loading');

      /* we need a special class for the iframe */
      this.$instance.toggleClass(this.namespace+'-iframe', $content.is('iframe'));

      /* replace content by appending to existing one before it is removed
         this insures that featherlight-inner remain at the same relative
         position to any other items added to featherlight-content */
      this.$instance.find('.'+this.namespace+'-inner')
        .not($content)                /* excluded new content, important if persisted */
        .slice(1).remove().end()      /* In the unexpected event where there are many inner elements, remove all but the first one */
        .replaceWith($.contains(this.$instance[0], $content[0]) ? '' : $content);

      this.$content = $content.addClass(this.namespace+'-inner');

      return this;
		},

		/* opens the lightbox. "this" contains $instance with the lightbox, and with the config.
			Returns a promise that is resolved after is successfully opened. */
		open: function(event){
			var self = this;
			self.$instance.hide().appendTo(self.root);
			if((!event || !event.isDefaultPrevented())
				&& self.beforeOpen(event) !== false) {

				if(event){
					event.preventDefault();
				}
				var $content = self.getContent();

				if($content) {
					opened.push(self);

					toggleGlobalEvents(true);

					self.$instance.fadeIn(self.openSpeed);
					self.beforeContent(event);

					/* Set content and show */
					return $.when($content)
						.always(function($content){
							self.setContent($content);
							self.afterContent(event);
						})
						.then(self.$instance.promise())
						/* Call afterOpen after fadeIn is done */
						.done(function(){ self.afterOpen(event); });
				}
			}
			self.$instance.detach();
			return $.Deferred().reject().promise();
		},

		/* closes the lightbox. "this" contains $instance with the lightbox, and with the config
			returns a promise, resolved after the lightbox is successfully closed. */
		close: function(event){
			var self = this,
				deferred = $.Deferred();

			if(self.beforeClose(event) === false) {
				deferred.reject();
			} else {

				if (0 === pruneOpened(self).length) {
					toggleGlobalEvents(false);
				}

				self.$instance.fadeOut(self.closeSpeed,function(){
					self.$instance.detach();
					self.afterClose(event);
					deferred.resolve();
				});
			}
			return deferred.promise();
		},

		/* resizes the content so it fits in visible area and keeps the same aspect ratio.
				Does nothing if either the width or the height is not specified.
				Called automatically on window resize.
				Override if you want different behavior. */
		resize: function(w, h) {
			if (w && h) {
				/* Reset apparent image size first so container grows */
				this.$content.css('width', '').css('height', '');
				/* Calculate the worst ratio so that dimensions fit */
				 /* Note: -1 to avoid rounding errors */
				var ratio = Math.max(
					w  / (this.$content.parent().width()-1),
					h / (this.$content.parent().height()-1));
				/* Resize content */
				if (ratio > 1) {
					ratio = h / Math.floor(h / ratio); /* Round ratio down so height calc works */
					this.$content.css('width', '' + w / ratio + 'px').css('height', '' + h / ratio + 'px');
				}
			}
		},

		/* Utility function to chain callbacks
		   [Warning: guru-level]
		   Used be extensions that want to let users specify callbacks but
		   also need themselves to use the callbacks.
		   The argument 'chain' has callback names as keys and function(super, event)
		   as values. That function is meant to call `super` at some point.
		*/
		chainCallbacks: function(chain) {
			for (var name in chain) {
				this[name] = $.proxy(chain[name], this, $.proxy(this[name], this));
			}
		}
	};

	$.extend(Featherlight, {
		id: 0,                                    /* Used to id single featherlight instances */
		autoBind:       '[data-featherlight]',    /* Will automatically bind elements matching this selector. Clear or set before onReady */
		defaults:       Featherlight.prototype,   /* You can access and override all defaults using $.featherlight.defaults, which is just a synonym for $.featherlight.prototype */
		/* Contains the logic to determine content */
		contentFilters: {
			jquery: {
				regex: /^[#.]\w/,         /* Anything that starts with a class name or identifiers */
				test: function(elem)    { return elem instanceof $ && elem; },
				process: function(elem) { return this.persist !== false ? $(elem) : $(elem).clone(true); }
			},
			image: {
				regex: /\.(png|jpg|jpeg|gif|tiff?|bmp|svg)(\?\S*)?$/i,
				process: function(url)  {
					var self = this,
						deferred = $.Deferred(),
						img = new Image(),
						$img = $('<img src="'+url+'" alt="" class="'+self.namespace+'-image" />');
					img.onload  = function() {
						/* Store naturalWidth & height for IE8 */
						$img.naturalWidth = img.width; $img.naturalHeight = img.height;
						deferred.resolve( $img );
					};
					img.onerror = function() { deferred.reject($img); };
					img.src = url;
					return deferred.promise();
				}
			},
			html: {
				regex: /^\s*<[\w!][^<]*>/, /* Anything that starts with some kind of valid tag */
				process: function(html) { return $(html); }
			},
			ajax: {
				regex: /./,            /* At this point, any content is assumed to be an URL */
				process: function(url)  {
					var self = this,
						deferred = $.Deferred();
					/* we are using load so one can specify a target with: url.html #targetelement */
					var $container = $('<div></div>').load(url, function(response, status){
						if ( status !== "error" ) {
							deferred.resolve($container.contents());
						}
						deferred.fail();
					});
					return deferred.promise();
				}
			},
			iframe: {
				process: function(url) {
					var deferred = new $.Deferred();
					var $content = $('<iframe/>');
					var css = parseAttrs(this, 'iframe');
					var attrs = slice(css, iFrameAttributeSet);
					$content.hide()
						.attr('src', url)
						.attr(attrs)
						.css(css)
						.on('load', function() { deferred.resolve($content.show()); })
						// We can't move an <iframe> and avoid reloading it,
						// so let's put it in place ourselves right now:
						.appendTo(this.$instance.find('.' + this.namespace + '-content'));
					return deferred.promise();
				}
			},
			text: {
				process: function(text) { return $('<div>', {text: text}); }
			}
		},

		functionAttributes: ['beforeOpen', 'afterOpen', 'beforeContent', 'afterContent', 'beforeClose', 'afterClose'],

		/*** class methods ***/
		/* read element's attributes starting with data-featherlight- */
		readElementConfig: function(element, namespace) {
			var Klass = this,
				regexp = new RegExp('^data-' + namespace + '-(.*)'),
				config = {};
			if (element && element.attributes) {
				$.each(element.attributes, function(){
					var match = this.name.match(regexp);
					if (match) {
						var val = this.value,
							name = $.camelCase(match[1]);
						if ($.inArray(name, Klass.functionAttributes) >= 0) {  /* jshint -W054 */
							val = new Function(val);                           /* jshint +W054 */
						} else {
							try { val = JSON.parse(val); }
							catch(e) {}
						}
						config[name] = val;
					}
				});
			}
			return config;
		},

		/* Used to create a Featherlight extension
		   [Warning: guru-level]
		   Creates the extension's prototype that in turn
		   inherits Featherlight's prototype.
		   Could be used to extend an extension too...
		   This is pretty high level wizardy, it comes pretty much straight
		   from CoffeeScript and won't teach you anything about Featherlight
		   as it's not really specific to this library.
		   My suggestion: move along and keep your sanity.
		*/
		extend: function(child, defaults) {
			/* Setup class hierarchy, adapted from CoffeeScript */
			var Ctor = function(){ this.constructor = child; };
			Ctor.prototype = this.prototype;
			child.prototype = new Ctor();
			child.__super__ = this.prototype;
			/* Copy class methods & attributes */
			$.extend(child, this, defaults);
			child.defaults = child.prototype;
			return child;
		},

		attach: function($source, $content, config) {
			var Klass = this;
			if (typeof $content === 'object' && $content instanceof $ === false && !config) {
				config = $content;
				$content = undefined;
			}
			/* make a copy */
			config = $.extend({}, config);

			/* Only for openTrigger and namespace... */
			var namespace = config.namespace || Klass.defaults.namespace,
				tempConfig = $.extend({}, Klass.defaults, Klass.readElementConfig($source[0], namespace), config),
				sharedPersist;
			var handler = function(event) {
				var $target = $(event.currentTarget);
				/* ... since we might as well compute the config on the actual target */
				var elemConfig = $.extend(
					{$source: $source, $currentTarget: $target},
					Klass.readElementConfig($source[0], tempConfig.namespace),
					Klass.readElementConfig(event.currentTarget, tempConfig.namespace),
					config);
				var fl = sharedPersist || $target.data('featherlight-persisted') || new Klass($content, elemConfig);
				if(fl.persist === 'shared') {
					sharedPersist = fl;
				} else if(fl.persist !== false) {
					$target.data('featherlight-persisted', fl);
				}
				if (elemConfig.$currentTarget.blur) {
					elemConfig.$currentTarget.blur(); // Otherwise 'enter' key might trigger the dialog again
				}
				fl.open(event);
			};

			$source.on(tempConfig.openTrigger+'.'+tempConfig.namespace, tempConfig.filter, handler);

			return handler;
		},

		current: function() {
			var all = this.opened();
			return all[all.length - 1] || null;
		},

		opened: function() {
			var klass = this;
			pruneOpened();
			return $.grep(opened, function(fl) { return fl instanceof klass; } );
		},

		close: function(event) {
			var cur = this.current();
			if(cur) { return cur.close(event); }
		},

		/* Does the auto binding on startup.
		   Meant only to be used by Featherlight and its extensions
		*/
		_onReady: function() {
			var Klass = this;
			if(Klass.autoBind){
				/* Bind existing elements */
				$(Klass.autoBind).each(function(){
					Klass.attach($(this));
				});
				/* If a click propagates to the document level, then we have an item that was added later on */
				$(document).on('click', Klass.autoBind, function(evt) {
					if (evt.isDefaultPrevented()) {
						return;
					}
					/* Bind featherlight */
					var handler = Klass.attach($(evt.currentTarget));
					/* Dispatch event directly */
					handler(evt);
				});
			}
		},

		/* Featherlight uses the onKeyUp callback to intercept the escape key.
		   Private to Featherlight.
		*/
		_callbackChain: {
			onKeyUp: function(_super, event){
				if(27 === event.keyCode) {
					if (this.closeOnEsc) {
						$.featherlight.close(event);
					}
					return false;
				} else {
					return _super(event);
				}
			},

			beforeOpen: function(_super, event) {
				// Used to disable scrolling
				$(document.documentElement).addClass('with-featherlight');

				// Remember focus:
				this._previouslyActive = document.activeElement;

				// Disable tabbing:
				// See http://stackoverflow.com/questions/1599660/which-html-elements-can-receive-focus
				this._$previouslyTabbable = $("a, input, select, textarea, iframe, button, iframe, [contentEditable=true]")
					.not('[tabindex]')
					.not(this.$instance.find('button'));

				this._$previouslyWithTabIndex = $('[tabindex]').not('[tabindex="-1"]');
				this._previousWithTabIndices = this._$previouslyWithTabIndex.map(function(_i, elem) {
					return $(elem).attr('tabindex');
				});

				this._$previouslyWithTabIndex.add(this._$previouslyTabbable).attr('tabindex', -1);

				if (document.activeElement.blur) {
					document.activeElement.blur();
				}
				return _super(event);
			},

			afterClose: function(_super, event) {
				var r = _super(event);
				// Restore focus
				var self = this;
				this._$previouslyTabbable.removeAttr('tabindex');
				this._$previouslyWithTabIndex.each(function(i, elem) {
					$(elem).attr('tabindex', self._previousWithTabIndices[i]);
				});
				this._previouslyActive.focus();
				// Restore scroll
				if(Featherlight.opened().length === 0) {
					$(document.documentElement).removeClass('with-featherlight');
				}
				return r;
			},

			onResize: function(_super, event){
				this.resize(this.$content.naturalWidth, this.$content.naturalHeight);
				return _super(event);
			},

			afterContent: function(_super, event){
				var r = _super(event);
				this.$instance.find('[autofocus]:not([disabled])').focus();
				this.onResize(event);
				return r;
			}
		}
	});

	$.featherlight = Featherlight;

	/* bind jQuery elements to trigger featherlight */
	$.fn.featherlight = function($content, config) {
		Featherlight.attach(this, $content, config);
		return this;
	};

	/* bind featherlight on ready if config autoBind is set */
	$(document).ready(function(){ Featherlight._onReady(); });
}(jQuery));


/*! 
 * ================== admin/assets/js/libs/jquery.blockUI.js =================== 
 **/ 

/*!
 * jQuery blockUI plugin
 * Version 2.70.0-2014.11.23
 * Requires jQuery v1.7 or later
 *
 * Examples at: http://malsup.com/jquery/block/
 * Copyright (c) 2007-2013 M. Alsup
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 * Thanks to Amir-Hossein Sobhi for some excellent contributions!
 */

;(function() {
/*jshint eqeqeq:false curly:false latedef:false */
"use strict";

	function setup($) {
		$.fn._fadeIn = $.fn.fadeIn;

		var noOp = $.noop || function() {};

		// this bit is to ensure we don't call setExpression when we shouldn't (with extra muscle to handle
		// confusing userAgent strings on Vista)
		var msie = /MSIE/.test(navigator.userAgent);
		var ie6  = /MSIE 6.0/.test(navigator.userAgent) && ! /MSIE 8.0/.test(navigator.userAgent);
		var mode = document.documentMode || 0;
		var setExpr = $.isFunction( document.createElement('div').style.setExpression );

		// global $ methods for blocking/unblocking the entire page
		$.blockUI   = function(opts) { install(window, opts); };
		$.unblockUI = function(opts) { remove(window, opts); };

		// convenience method for quick growl-like notifications  (http://www.google.com/search?q=growl)
		$.growlUI = function(title, message, timeout, onClose) {
			var $m = $('<div class="growlUI"></div>');
			if (title) $m.append('<h1>'+title+'</h1>');
			if (message) $m.append('<h2>'+message+'</h2>');
			if (timeout === undefined) timeout = 3000;

			// Added by konapun: Set timeout to 30 seconds if this growl is moused over, like normal toast notifications
			var callBlock = function(opts) {
				opts = opts || {};

				$.blockUI({
					message: $m,
					fadeIn : typeof opts.fadeIn  !== 'undefined' ? opts.fadeIn  : 700,
					fadeOut: typeof opts.fadeOut !== 'undefined' ? opts.fadeOut : 1000,
					timeout: typeof opts.timeout !== 'undefined' ? opts.timeout : timeout,
					centerY: false,
					showOverlay: false,
					onUnblock: onClose,
					css: $.blockUI.defaults.growlCSS
				});
			};

			callBlock();
			var nonmousedOpacity = $m.css('opacity');
			$m.mouseover(function() {
				callBlock({
					fadeIn: 0,
					timeout: 30000
				});

				var displayBlock = $('.blockMsg');
				displayBlock.stop(); // cancel fadeout if it has started
				displayBlock.fadeTo(300, 1); // make it easier to read the message by removing transparency
			}).mouseout(function() {
				$('.blockMsg').fadeOut(1000);
			});
			// End konapun additions
		};

		// plugin method for blocking element content
		$.fn.block = function(opts) {
			if ( this[0] === window ) {
				$.blockUI( opts );
				return this;
			}
			var fullOpts = $.extend({}, $.blockUI.defaults, opts || {});
			this.each(function() {
				var $el = $(this);
				if (fullOpts.ignoreIfBlocked && $el.data('blockUI.isBlocked'))
					return;
				$el.unblock({ fadeOut: 0 });
			});

			return this.each(function() {
				if ($.css(this,'position') == 'static') {
					this.style.position = 'relative';
					$(this).data('blockUI.static', true);
				}
				this.style.zoom = 1; // force 'hasLayout' in ie
				install(this, opts);
			});
		};

		// plugin method for unblocking element content
		$.fn.unblock = function(opts) {
			if ( this[0] === window ) {
				$.unblockUI( opts );
				return this;
			}
			return this.each(function() {
				remove(this, opts);
			});
		};

		$.blockUI.version = 2.70; // 2nd generation blocking at no extra cost!

		// override these in your code to change the default behavior and style
		$.blockUI.defaults = {
			// message displayed when blocking (use null for no message)
			message:  '<h1>Please wait...</h1>',

			title: null,		// title string; only used when theme == true
			draggable: true,	// only used when theme == true (requires jquery-ui.js to be loaded)

			theme: false, // set to true to use with jQuery UI themes

			// styles for the message when blocking; if you wish to disable
			// these and use an external stylesheet then do this in your code:
			// $.blockUI.defaults.css = {};
			css: {
				padding:	0,
				margin:		0,
				width:		'30%',
				top:		'40%',
				left:		'35%',
				textAlign:	'center',
				color:		'#000',
				border:		'3px solid #aaa',
				backgroundColor:'#fff',
				cursor:		'wait'
			},

			// minimal style set used when themes are used
			themedCSS: {
				width:	'30%',
				top:	'40%',
				left:	'35%'
			},

			// styles for the overlay
			overlayCSS:  {
				backgroundColor:	'#000',
				opacity:			0.6,
				cursor:				'wait'
			},

			// style to replace wait cursor before unblocking to correct issue
			// of lingering wait cursor
			cursorReset: 'default',

			// styles applied when using $.growlUI
			growlCSS: {
				width:		'350px',
				top:		'10px',
				left:		'',
				right:		'10px',
				border:		'none',
				padding:	'5px',
				opacity:	0.6,
				cursor:		'default',
				color:		'#fff',
				backgroundColor: '#000',
				'-webkit-border-radius':'10px',
				'-moz-border-radius':	'10px',
				'border-radius':		'10px'
			},

			// IE issues: 'about:blank' fails on HTTPS and javascript:false is s-l-o-w
			// (hat tip to Jorge H. N. de Vasconcelos)
			/*jshint scripturl:true */
			iframeSrc: /^https/i.test(window.location.href || '') ? 'javascript:false' : 'about:blank',

			// force usage of iframe in non-IE browsers (handy for blocking applets)
			forceIframe: false,

			// z-index for the blocking overlay
			baseZ: 1000,

			// set these to true to have the message automatically centered
			centerX: true, // <-- only effects element blocking (page block controlled via css above)
			centerY: true,

			// allow body element to be stetched in ie6; this makes blocking look better
			// on "short" pages.  disable if you wish to prevent changes to the body height
			allowBodyStretch: true,

			// enable if you want key and mouse events to be disabled for content that is blocked
			bindEvents: true,

			// be default blockUI will supress tab navigation from leaving blocking content
			// (if bindEvents is true)
			constrainTabKey: true,

			// fadeIn time in millis; set to 0 to disable fadeIn on block
			fadeIn:  200,

			// fadeOut time in millis; set to 0 to disable fadeOut on unblock
			fadeOut:  400,

			// time in millis to wait before auto-unblocking; set to 0 to disable auto-unblock
			timeout: 0,

			// disable if you don't want to show the overlay
			showOverlay: true,

			// if true, focus will be placed in the first available input field when
			// page blocking
			focusInput: true,

            // elements that can receive focus
            focusableElements: ':input:enabled:visible',

			// suppresses the use of overlay styles on FF/Linux (due to performance issues with opacity)
			// no longer needed in 2012
			// applyPlatformOpacityRules: true,

			// callback method invoked when fadeIn has completed and blocking message is visible
			onBlock: null,

			// callback method invoked when unblocking has completed; the callback is
			// passed the element that has been unblocked (which is the window object for page
			// blocks) and the options that were passed to the unblock call:
			//	onUnblock(element, options)
			onUnblock: null,

			// callback method invoked when the overlay area is clicked.
			// setting this will turn the cursor to a pointer, otherwise cursor defined in overlayCss will be used.
			onOverlayClick: null,

			// don't ask; if you really must know: http://groups.google.com/group/jquery-en/browse_thread/thread/36640a8730503595/2f6a79a77a78e493#2f6a79a77a78e493
			quirksmodeOffsetHack: 4,

			// class name of the message block
			blockMsgClass: 'blockMsg',

			// if it is already blocked, then ignore it (don't unblock and reblock)
			ignoreIfBlocked: false
		};

		// private data and functions follow...

		var pageBlock = null;
		var pageBlockEls = [];

		function install(el, opts) {
			var css, themedCSS;
			var full = (el == window);
			var msg = (opts && opts.message !== undefined ? opts.message : undefined);
			opts = $.extend({}, $.blockUI.defaults, opts || {});

			if (opts.ignoreIfBlocked && $(el).data('blockUI.isBlocked'))
				return;

			opts.overlayCSS = $.extend({}, $.blockUI.defaults.overlayCSS, opts.overlayCSS || {});
			css = $.extend({}, $.blockUI.defaults.css, opts.css || {});
			if (opts.onOverlayClick)
				opts.overlayCSS.cursor = 'pointer';

			themedCSS = $.extend({}, $.blockUI.defaults.themedCSS, opts.themedCSS || {});
			msg = msg === undefined ? opts.message : msg;

			// remove the current block (if there is one)
			if (full && pageBlock)
				remove(window, {fadeOut:0});

			// if an existing element is being used as the blocking content then we capture
			// its current place in the DOM (and current display style) so we can restore
			// it when we unblock
			if (msg && typeof msg != 'string' && (msg.parentNode || msg.jquery)) {
				var node = msg.jquery ? msg[0] : msg;
				var data = {};
				$(el).data('blockUI.history', data);
				data.el = node;
				data.parent = node.parentNode;
				data.display = node.style.display;
				data.position = node.style.position;
				if (data.parent)
					data.parent.removeChild(node);
			}

			$(el).data('blockUI.onUnblock', opts.onUnblock);
			var z = opts.baseZ;

			// blockUI uses 3 layers for blocking, for simplicity they are all used on every platform;
			// layer1 is the iframe layer which is used to supress bleed through of underlying content
			// layer2 is the overlay layer which has opacity and a wait cursor (by default)
			// layer3 is the message content that is displayed while blocking
			var lyr1, lyr2, lyr3, s;
			if (msie || opts.forceIframe)
				lyr1 = $('<iframe class="blockUI" style="z-index:'+ (z++) +';display:none;border:none;margin:0;padding:0;position:absolute;width:100%;height:100%;top:0;left:0" src="'+opts.iframeSrc+'"></iframe>');
			else
				lyr1 = $('<div class="blockUI" style="display:none"></div>');

			if (opts.theme)
				lyr2 = $('<div class="blockUI blockOverlay ui-widget-overlay" style="z-index:'+ (z++) +';display:none"></div>');
			else
				lyr2 = $('<div class="blockUI blockOverlay" style="z-index:'+ (z++) +';display:none;border:none;margin:0;padding:0;width:100%;height:100%;top:0;left:0"></div>');

			if (opts.theme && full) {
				s = '<div class="blockUI ' + opts.blockMsgClass + ' blockPage ui-dialog ui-widget ui-corner-all" style="z-index:'+(z+10)+';display:none;position:fixed">';
				if ( opts.title ) {
					s += '<div class="ui-widget-header ui-dialog-titlebar ui-corner-all blockTitle">'+(opts.title || '&nbsp;')+'</div>';
				}
				s += '<div class="ui-widget-content ui-dialog-content"></div>';
				s += '</div>';
			}
			else if (opts.theme) {
				s = '<div class="blockUI ' + opts.blockMsgClass + ' blockElement ui-dialog ui-widget ui-corner-all" style="z-index:'+(z+10)+';display:none;position:absolute">';
				if ( opts.title ) {
					s += '<div class="ui-widget-header ui-dialog-titlebar ui-corner-all blockTitle">'+(opts.title || '&nbsp;')+'</div>';
				}
				s += '<div class="ui-widget-content ui-dialog-content"></div>';
				s += '</div>';
			}
			else if (full) {
				s = '<div class="blockUI ' + opts.blockMsgClass + ' blockPage" style="z-index:'+(z+10)+';display:none;position:fixed"></div>';
			}
			else {
				s = '<div class="blockUI ' + opts.blockMsgClass + ' blockElement" style="z-index:'+(z+10)+';display:none;position:absolute"></div>';
			}
			lyr3 = $(s);

			// if we have a message, style it
			if (msg) {
				if (opts.theme) {
					lyr3.css(themedCSS);
					lyr3.addClass('ui-widget-content');
				}
				else
					lyr3.css(css);
			}

			// style the overlay
			if (!opts.theme /*&& (!opts.applyPlatformOpacityRules)*/)
				lyr2.css(opts.overlayCSS);
			lyr2.css('position', full ? 'fixed' : 'absolute');

			// make iframe layer transparent in IE
			if (msie || opts.forceIframe)
				lyr1.css('opacity',0.0);

			//$([lyr1[0],lyr2[0],lyr3[0]]).appendTo(full ? 'body' : el);
			var layers = [lyr1,lyr2,lyr3], $par = full ? $('body') : $(el);
			$.each(layers, function() {
				this.appendTo($par);
			});

			if (opts.theme && opts.draggable && $.fn.draggable) {
				lyr3.draggable({
					handle: '.ui-dialog-titlebar',
					cancel: 'li'
				});
			}

			// ie7 must use absolute positioning in quirks mode and to account for activex issues (when scrolling)
			var expr = setExpr && (!$.support.boxModel || $('object,embed', full ? null : el).length > 0);
			if (ie6 || expr) {
				// give body 100% height
				if (full && opts.allowBodyStretch && $.support.boxModel)
					$('html,body').css('height','100%');

				// fix ie6 issue when blocked element has a border width
				if ((ie6 || !$.support.boxModel) && !full) {
					var t = sz(el,'borderTopWidth'), l = sz(el,'borderLeftWidth');
					var fixT = t ? '(0 - '+t+')' : 0;
					var fixL = l ? '(0 - '+l+')' : 0;
				}

				// simulate fixed position
				$.each(layers, function(i,o) {
					var s = o[0].style;
					s.position = 'absolute';
					if (i < 2) {
						if (full)
							s.setExpression('height','Math.max(document.body.scrollHeight, document.body.offsetHeight) - (jQuery.support.boxModel?0:'+opts.quirksmodeOffsetHack+') + "px"');
						else
							s.setExpression('height','this.parentNode.offsetHeight + "px"');
						if (full)
							s.setExpression('width','jQuery.support.boxModel && document.documentElement.clientWidth || document.body.clientWidth + "px"');
						else
							s.setExpression('width','this.parentNode.offsetWidth + "px"');
						if (fixL) s.setExpression('left', fixL);
						if (fixT) s.setExpression('top', fixT);
					}
					else if (opts.centerY) {
						if (full) s.setExpression('top','(document.documentElement.clientHeight || document.body.clientHeight) / 2 - (this.offsetHeight / 2) + (blah = document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop) + "px"');
						s.marginTop = 0;
					}
					else if (!opts.centerY && full) {
						var top = (opts.css && opts.css.top) ? parseInt(opts.css.top, 10) : 0;
						var expression = '((document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop) + '+top+') + "px"';
						s.setExpression('top',expression);
					}
				});
			}

			// show the message
			if (msg) {
				if (opts.theme)
					lyr3.find('.ui-widget-content').append(msg);
				else
					lyr3.append(msg);
				if (msg.jquery || msg.nodeType)
					$(msg).show();
			}

			if ((msie || opts.forceIframe) && opts.showOverlay)
				lyr1.show(); // opacity is zero
			if (opts.fadeIn) {
				var cb = opts.onBlock ? opts.onBlock : noOp;
				var cb1 = (opts.showOverlay && !msg) ? cb : noOp;
				var cb2 = msg ? cb : noOp;
				if (opts.showOverlay)
					lyr2._fadeIn(opts.fadeIn, cb1);
				if (msg)
					lyr3._fadeIn(opts.fadeIn, cb2);
			}
			else {
				if (opts.showOverlay)
					lyr2.show();
				if (msg)
					lyr3.show();
				if (opts.onBlock)
					opts.onBlock.bind(lyr3)();
			}

			// bind key and mouse events
			bind(1, el, opts);

			if (full) {
				pageBlock = lyr3[0];
				pageBlockEls = $(opts.focusableElements,pageBlock);
				if (opts.focusInput)
					setTimeout(focus, 20);
			}
			else
				center(lyr3[0], opts.centerX, opts.centerY);

			if (opts.timeout) {
				// auto-unblock
				var to = setTimeout(function() {
					if (full)
						$.unblockUI(opts);
					else
						$(el).unblock(opts);
				}, opts.timeout);
				$(el).data('blockUI.timeout', to);
			}
		}

		// remove the block
		function remove(el, opts) {
			var count;
			var full = (el == window);
			var $el = $(el);
			var data = $el.data('blockUI.history');
			var to = $el.data('blockUI.timeout');
			if (to) {
				clearTimeout(to);
				$el.removeData('blockUI.timeout');
			}
			opts = $.extend({}, $.blockUI.defaults, opts || {});
			bind(0, el, opts); // unbind events

			if (opts.onUnblock === null) {
				opts.onUnblock = $el.data('blockUI.onUnblock');
				$el.removeData('blockUI.onUnblock');
			}

			var els;
			if (full) // crazy selector to handle odd field errors in ie6/7
				els = $('body').children().filter('.blockUI').add('body > .blockUI');
			else
				els = $el.find('>.blockUI');

			// fix cursor issue
			if ( opts.cursorReset ) {
				if ( els.length > 1 )
					els[1].style.cursor = opts.cursorReset;
				if ( els.length > 2 )
					els[2].style.cursor = opts.cursorReset;
			}

			if (full)
				pageBlock = pageBlockEls = null;

			if (opts.fadeOut) {
				count = els.length;
				els.stop().fadeOut(opts.fadeOut, function() {
					if ( --count === 0)
						reset(els,data,opts,el);
				});
			}
			else
				reset(els, data, opts, el);
		}

		// move blocking element back into the DOM where it started
		function reset(els,data,opts,el) {
			var $el = $(el);
			if ( $el.data('blockUI.isBlocked') )
				return;

			els.each(function(i,o) {
				// remove via DOM calls so we don't lose event handlers
				if (this.parentNode)
					this.parentNode.removeChild(this);
			});

			if (data && data.el) {
				data.el.style.display = data.display;
				data.el.style.position = data.position;
				data.el.style.cursor = 'default'; // #59
				if (data.parent)
					data.parent.appendChild(data.el);
				$el.removeData('blockUI.history');
			}

			if ($el.data('blockUI.static')) {
				$el.css('position', 'static'); // #22
			}

			if (typeof opts.onUnblock == 'function')
				opts.onUnblock(el,opts);

			// fix issue in Safari 6 where block artifacts remain until reflow
			var body = $(document.body), w = body.width(), cssW = body[0].style.width;
			body.width(w-1).width(w);
			body[0].style.width = cssW;
		}

		// bind/unbind the handler
		function bind(b, el, opts) {
			var full = el == window, $el = $(el);

			// don't bother unbinding if there is nothing to unbind
			if (!b && (full && !pageBlock || !full && !$el.data('blockUI.isBlocked')))
				return;

			$el.data('blockUI.isBlocked', b);

			// don't bind events when overlay is not in use or if bindEvents is false
			if (!full || !opts.bindEvents || (b && !opts.showOverlay))
				return;

			// bind anchors and inputs for mouse and key events
			var events = 'mousedown mouseup keydown keypress keyup touchstart touchend touchmove';
			if (b)
				$(document).bind(events, opts, handler);
			else
				$(document).unbind(events, handler);

		// former impl...
		//		var $e = $('a,:input');
		//		b ? $e.bind(events, opts, handler) : $e.unbind(events, handler);
		}

		// event handler to suppress keyboard/mouse events when blocking
		function handler(e) {
			// allow tab navigation (conditionally)
			if (e.type === 'keydown' && e.keyCode && e.keyCode == 9) {
				if (pageBlock && e.data.constrainTabKey) {
					var els = pageBlockEls;
					var fwd = !e.shiftKey && e.target === els[els.length-1];
					var back = e.shiftKey && e.target === els[0];
					if (fwd || back) {
						setTimeout(function(){focus(back);},10);
						return false;
					}
				}
			}
			var opts = e.data;
			var target = $(e.target);
			if (target.hasClass('blockOverlay') && opts.onOverlayClick)
				opts.onOverlayClick(e);

			// allow events within the message content
			if (target.parents('div.' + opts.blockMsgClass).length > 0)
				return true;

			// allow events for content that is not being blocked
			return target.parents().children().filter('div.blockUI').length === 0;
		}

		function focus(back) {
			if (!pageBlockEls)
				return;
			var e = pageBlockEls[back===true ? pageBlockEls.length-1 : 0];
			if (e)
				e.focus();
		}

		function center(el, x, y) {
			var p = el.parentNode, s = el.style;
			var l = ((p.offsetWidth - el.offsetWidth)/2) - sz(p,'borderLeftWidth');
			var t = ((p.offsetHeight - el.offsetHeight)/2) - sz(p,'borderTopWidth');
			if (x) s.left = l > 0 ? (l+'px') : '0';
			if (y) s.top  = t > 0 ? (t+'px') : '0';
		}

		function sz(el, p) {
			return parseInt($.css(el,p),10)||0;
		}

	}


	/*global define:true */
	if (typeof define === 'function' && define.amd && define.amd.jQuery) {
		define(['jquery'], setup);
	} else {
		setup(jQuery);
	}

})();


/*! 
 * ================== admin/assets/js/libs/jquery.scrollTo.js =================== 
 **/ 

/*!
 * jQuery.scrollTo
 * Copyright (c) 2007-2015 Ariel Flesler - aflesler<a>gmail<d>com | http://flesler.blogspot.com
 * Licensed under MIT
 * http://flesler.blogspot.com/2007/10/jqueryscrollto.html
 * @projectDescription Lightweight, cross-browser and highly customizable animated scrolling with jQuery
 * @author Ariel Flesler
 * @version 2.1.2
 */
;(function(factory) {
	'use strict';
	if (typeof define === 'function' && define.amd) {
		// AMD
		define(['jquery'], factory);
	} else if (typeof module !== 'undefined' && module.exports) {
		// CommonJS
		module.exports = factory(require('jquery'));
	} else {
		// Global
		factory(jQuery);
	}
})(function($) {
	'use strict';

	var $scrollTo = $.scrollTo = function(target, duration, settings) {
		return $(window).scrollTo(target, duration, settings);
	};

	$scrollTo.defaults = {
		axis:'xy',
		duration: 0,
		limit:true
	};

	function isWin(elem) {
		return !elem.nodeName ||
			$.inArray(elem.nodeName.toLowerCase(), ['iframe','#document','html','body']) !== -1;
	}		

	$.fn.scrollTo = function(target, duration, settings) {
		if (typeof duration === 'object') {
			settings = duration;
			duration = 0;
		}
		if (typeof settings === 'function') {
			settings = { onAfter:settings };
		}
		if (target === 'max') {
			target = 9e9;
		}

		settings = $.extend({}, $scrollTo.defaults, settings);
		// Speed is still recognized for backwards compatibility
		duration = duration || settings.duration;
		// Make sure the settings are given right
		var queue = settings.queue && settings.axis.length > 1;
		if (queue) {
			// Let's keep the overall duration
			duration /= 2;
		}
		settings.offset = both(settings.offset);
		settings.over = both(settings.over);

		return this.each(function() {
			// Null target yields nothing, just like jQuery does
			if (target === null) return;

			var win = isWin(this),
				elem = win ? this.contentWindow || window : this,
				$elem = $(elem),
				targ = target, 
				attr = {},
				toff;

			switch (typeof targ) {
				// A number will pass the regex
				case 'number':
				case 'string':
					if (/^([+-]=?)?\d+(\.\d+)?(px|%)?$/.test(targ)) {
						targ = both(targ);
						// We are done
						break;
					}
					// Relative/Absolute selector
					targ = win ? $(targ) : $(targ, elem);
					/* falls through */
				case 'object':
					if (targ.length === 0) return;
					// DOMElement / jQuery
					if (targ.is || targ.style) {
						// Get the real position of the target
						toff = (targ = $(targ)).offset();
					}
			}

			var offset = $.isFunction(settings.offset) && settings.offset(elem, targ) || settings.offset;

			$.each(settings.axis.split(''), function(i, axis) {
				var Pos	= axis === 'x' ? 'Left' : 'Top',
					pos = Pos.toLowerCase(),
					key = 'scroll' + Pos,
					prev = $elem[key](),
					max = $scrollTo.max(elem, axis);

				if (toff) {// jQuery / DOMElement
					attr[key] = toff[pos] + (win ? 0 : prev - $elem.offset()[pos]);

					// If it's a dom element, reduce the margin
					if (settings.margin) {
						attr[key] -= parseInt(targ.css('margin'+Pos), 10) || 0;
						attr[key] -= parseInt(targ.css('border'+Pos+'Width'), 10) || 0;
					}

					attr[key] += offset[pos] || 0;

					if (settings.over[pos]) {
						// Scroll to a fraction of its width/height
						attr[key] += targ[axis === 'x'?'width':'height']() * settings.over[pos];
					}
				} else {
					var val = targ[pos];
					// Handle percentage values
					attr[key] = val.slice && val.slice(-1) === '%' ?
						parseFloat(val) / 100 * max
						: val;
				}

				// Number or 'number'
				if (settings.limit && /^\d+$/.test(attr[key])) {
					// Check the limits
					attr[key] = attr[key] <= 0 ? 0 : Math.min(attr[key], max);
				}

				// Don't waste time animating, if there's no need.
				if (!i && settings.axis.length > 1) {
					if (prev === attr[key]) {
						// No animation needed
						attr = {};
					} else if (queue) {
						// Intermediate animation
						animate(settings.onAfterFirst);
						// Don't animate this axis again in the next iteration.
						attr = {};
					}
				}
			});

			animate(settings.onAfter);

			function animate(callback) {
				var opts = $.extend({}, settings, {
					// The queue setting conflicts with animate()
					// Force it to always be true
					queue: true,
					duration: duration,
					complete: callback && function() {
						callback.call(elem, targ, settings);
					}
				});
				$elem.animate(attr, opts);
			}
		});
	};

	// Max scrolling position, works on quirks mode
	// It only fails (not too badly) on IE, quirks mode.
	$scrollTo.max = function(elem, axis) {
		var Dim = axis === 'x' ? 'Width' : 'Height',
			scroll = 'scroll'+Dim;

		if (!isWin(elem))
			return elem[scroll] - $(elem)[Dim.toLowerCase()]();

		var size = 'client' + Dim,
			doc = elem.ownerDocument || elem.document,
			html = doc.documentElement,
			body = doc.body;

		return Math.max(html[scroll], body[scroll]) - Math.min(html[size], body[size]);
	};

	function both(val) {
		return $.isFunction(val) || $.isPlainObject(val) ? val : { top:val, left:val };
	}

	// Add special hooks so that window scroll properties can be animated
	$.Tween.propHooks.scrollLeft = 
	$.Tween.propHooks.scrollTop = {
		get: function(t) {
			return $(t.elem)[t.prop]();
		},
		set: function(t) {
			var curr = this.get(t);
			// If interrupt is true and user scrolled, stop animating
			if (t.options.interrupt && t._last && t._last !== curr) {
				return $(t.elem).stop();
			}
			var next = Math.round(t.now);
			// Don't waste CPU
			// Browsers don't render floating point scroll
			if (curr !== next) {
				$(t.elem)[t.prop](next);
				t._last = this.get(t);
			}
		}
	};

	// AMD requirement
	return $scrollTo;
});


/*! 
 * ================== admin/assets/js/libs/wizard.js =================== 
 **/ 

(function($, window, document, undefined) {
    "use strict";

    // Create the defaults once
    var pluginName = "AuxWizard",
        defaults = {
            modalClass: ".aux-open-modal",
            loading: aux_setup_params.svg_loader
        };
    // The actual plugin constructor
    function Plugin(element, options) {
        this.element = element;
        this.$element = $(element);
        this.settings = $.extend({}, defaults, options);
        this._defaults = defaults;
        this._name = pluginName;

        this.$modalElement = null;
        this._modalButton = null;
        this._ajaxData = null;
        this._ajaxUrl = aux_setup_params.ajaxurl;
        this._elStorage = {};
        this._importData = {};

        // Isotope Elements
        this.$isotopeTemplate = null;
        this.$isotopeList = null;
        this.$isotopePlugins = null;

        this.init();
    }

    // Avoid Plugin.prototype conflicts
    $.extend(Plugin.prototype, {
        init: function() {
            // Create isotope elements
            this._callIsotope();
            // Isotope change group
            $(".aux-isotope-group").on("change", this._changeGroup.bind(this));

            // General Events
            this._openModal();
            this._manipulations();

            this._lazyloadConfig();

            // Steps Manager Event
            $(document).on(
                "click",
                ".aux-next-step",
                this._stepManager.bind(this)
            );

            // Install & Uninstall Demo Events
            $(document).on(
                "click",
                ".aux-install-demo",
                this._demoManager.bind(this)
            );
            $(document).on(
                "click",
                ".aux-uninstall-demo",
                this._uninstallDemo.bind(this)
            );

            // Template Manager Event
            $(document).on(
                "click",
                ".aux-copy-template",
                this._tempManager.bind(this)
            );

            // Install Plugins Event
            $(document).on(
                "click",
                ".install-plugins",
                this._pluginManager.bind(this)
            );

            // Install Plugins Event
            $(document).on(
                "click",
                ".aux-install-updates",
                this._updateManager.bind(this)
            );

            // Activate license
            $(document).on(
                "submit",
                ".auxin-check-purchase",
                this._activateLicense.bind(this)
            );

            // Refresh Page
            $(document).on(
                "click",
                ".aux-refresh-page",
                this._refresh.bind(this)
            );

            // Check envato elements email - get license code
            $(document).on(
                "click",
                ".aux-verify-elements-email",
                this._verifyEnvatoElementsEmail.bind(this)
            );

            // Check envato elements token
            $(document).on(
                "click",
                ".aux-verify-elements-token",
                this._verifyEnvatoElementsToken.bind(this)
            );

        },

        /**
         * global AJAX callback
         */
        _globalAJAX: function(callback) {
            // Do Ajax & update default value
            $.ajax({
                url: this._ajaxUrl,
                type: "post",
                data: this._ajaxData
            }).done(callback);
        },

        /**
         * refresh page
         */

        _refresh: function(e) {
            // check preventDefault existence
            if (typeof e.preventDefault !== "undefined") {
                e.preventDefault();
            }
            location.reload();
        },

        /**
         * Activate user license
         */
        _activateLicense: function(e) {
            // Check currentTarget existence
            if (!e.currentTarget) {
                return;
            }
            // check preventDefault existence
            if (typeof e.preventDefault !== "undefined") {
                e.preventDefault();
            }

            // Set variables
            var $formElemment = $(e.currentTarget),
                $buttonElement = $formElemment.find(".aux-activate-license"),
                $noticeElement = $formElemment.find(".aux-notice"),
                statusClass = null,
                getFormInputs = {};
            $.each($formElemment.serializeArray(), function(i, field) {
                getFormInputs[field.name] = field.value;
            });
            // If the notice container is not exist, we've to add it.
            if (!$noticeElement.length) {
                $noticeElement = $("<div>", { class: "aux-notice" }).appendTo(
                    $formElemment
                );
            }
            this._ajaxData = {
                action: getFormInputs.action,
                usermail: getFormInputs.usermail,
                purchase: getFormInputs.purchase,
                security: getFormInputs.security
            };

            // Manipulation
            $buttonElement.addClass("aux-button-loading");
            $noticeElement.removeClass("success warning").hide();

            this._controlActions("off");
            // Call AJAX with current _ajaxData value
            this._globalAJAX(
                function(response) {
                    // Check response status
                    if (response !== null && response.success) {
                        // Then do these actions
                        $buttonElement.addClass(
                            "aux-button-success aux-refresh-page"
                        );
                        $noticeElement.addClass("success");
                        statusClass = "aux-button-success aux-button-loading";
                        $(this._modalButton)
                            .closest(".aux-purchase-activation-notice")
                            .fadeOut();
                    } else {
                        // Then do these actions
                        $buttonElement.addClass("aux-button-error");
                        $noticeElement.addClass("warning");
                        statusClass = "aux-button-error aux-button-loading";
                    }
                    // Remove form progress class
                    $formElemment.removeClass("aux-form-in-progress");
                    // Actions
                    setTimeout(function() {
                        $buttonElement.removeClass(statusClass);
                        $buttonElement
                            .find("span")
                            .text(response.data.buttonText);
                    }, 1000);
                    $noticeElement.show().html(response.data.message);
                    this._controlActions("on");
                }.bind(this)
            );
        },

        /**
         * open modal box (Based on featherlight plugin)
         */
        _openModal: function() {
            var self = this;
            // Display modal demo on click button
            var $advancedAjaxModal = $(self.settings.modalClass).featherlight({
                targetAttr: "href",
                closeOnEsc: false,
                closeOnClick: false,
                contentFilters: ["ajax"],
                loading: this.settings.loading,
                otherClose: ".aux-pp-close",
                afterOpen: function(e) {
                    // init PerfectScrollbar
                    if ($(".featherlight .aux-wizard-plugins").length) {
                        var PScrollbar = new PerfectScrollbar(
                            ".featherlight .aux-wizard-plugins"
                        );
                    }
                    // Set golbal modal button
                    self._modalButton = e.currentTarget;
                    self.$modalElement = this.$instance;
                    // Run template manager function
                    if ($(self._modalButton).hasClass("aux-has-next-action")) {
                        self._tempManager({ currentTarget: e.currentTarget });
                    }
                }
            });
            var $simpleAjaxModal = $(".aux-ajax-open-modal").featherlight({
                targetAttr: "href",
                contentFilters: ["ajax"],
                otherClose: ".aux-pp-close",
                closeOnClick: false,
                loading: this.settings.loading,
                afterOpen: function(e) {
                    // Set golbal modal button
                    self._modalButton = e.currentTarget;
                    self.$modalElement = this.$instance;
                }
            });
            // Auto open modal
            if ($simpleAjaxModal.data("auto-open") === 1) {
                $simpleAjaxModal.click();
            }
        },

        /**
         * a callback to change the group of AuxIsotope (Used in the Template Kits Switcher to select modes between 'page' & 'section')
         */
        _changeGroup: function(e) {
            // Check currentTarget existence
            if (!e.currentTarget) {
                return;
            }
            // Set variables
            var groupName = e.currentTarget.checked ? "section" : "page";
            this._ajaxData = {
                action: "aux_isotope_group",
                group: groupName,
                nonce: $(e.currentTarget).data("nonce"),
                key: "templates_kit"
            };
            // Call AJAX with current _ajaxData value
            this._globalAJAX(
                function(response) {
                    if (response !== null && response.success) {
                        this.$isotopeTemplate.AuxIsotope(
                            "changeGroup",
                            groupName
                        );
                    } else {
                        console.log(response);
                    }
                }.bind(this)
            );
        },

        /* ------------------------------------------------------------------------------ */
        // Update Manager

        /**
         * Update manager main function
         */
        _updateManager: function(e) {
            // Check currentTarget existence
            if (!e.currentTarget) {
                return;
            }
            // check preventDefault existence
            if (typeof e.preventDefault !== "undefined") {
                e.preventDefault();
            }

            var $buttonElement = $(e.currentTarget);
            this.$buttonParentEl = $buttonElement.closest(".aux-updates-step");
            this.$updatesListEl = this.$buttonParentEl.find(".aux-update-list");
            this.$updatesList = this.$updatesListEl.find(".aux-item");
            this._itemsCompleted = 0;
            this._attemptsBuffer = 0;
            this._currentItem = null;
            this._itemType = null;
            this._dataNonce = $buttonElement.data("nonce");
            this._buttonTarget = e.currentTarget;
            this.$currentNode = null;

            // Manipulation
            this.$updatesListEl.addClass("installing");
            $buttonElement
                .text(aux_setup_params.btnworks_text)
                .addClass("disabled");

            this._controlActions("off");
            this._processUpdates();
        },

        /**
         * Process update elements
         */
        _processUpdates: function() {
            var self = this,
                doNext = false;

            if (this.$currentNode) {
                if (!this.$currentNode.data("done_item")) {
                    this._itemsCompleted++;
                    this.$currentNode.data("done_item", 1);
                }
            }

            this.$updatesList.each(function() {
                if (self._currentItem == null || doNext) {
                    $(this).addClass("work-in-progress");
                    self._currentItem = $(this).data("key");
                    self._itemType = $(this).data("type");
                    self.$currentNode = $(this);
                    self._installUpdate();
                    doNext = false;
                } else if ($(this).data("key") === self._currentItem) {
                    $(this).removeClass("work-in-progress");
                    doNext = true;
                }
            });

            // If all plugins finished, then
            if (this._itemsCompleted >= this.$updatesList.length) {
                // Activate control actions
                this._controlActions("on");
                // Remove installing class
                this.$updatesListEl.removeClass("installing");
                // Remove disable class from button
                $(this._buttonTarget)
                    .text(aux_setup_params.activate_text)
                    .removeClass("disabled");
                if (this.$updatesList.not(".aux-success").length == 0) {
                    // Refresh current page when all the plugins has been successfully updated.
                    this._refresh({ currentTarget: this._buttonTarget });
                }
            }
        },

        /**
         * Process update by type & key
         */
        _installUpdate: function() {
            if (this._currentItem) {
                this._ajaxData = {
                    action: "auxin_start_upgrading",
                    key: this._currentItem,
                    type: this._itemType,
                    nonce: this._dataNonce
                };
                this._globalAJAX(
                    function(response) {
                        this._updateActions(response);
                    }.bind(this)
                );
            }
        },

        /**
         * Item update events
         */
        _updateActions: function(response) {
            // Check response type
            if (typeof response === "object" && response.success) {
                // Update item status message
                this.$currentNode
                    .find(".column-status span")
                    .text(response.data.successMessage);
                // otherwise it's just installed and we should make a notify to user
                this.$currentNode
                    .addClass("aux-success")
                    .find(".aux-check-column")
                    .remove();
                this.$currentNode
                    .find(".check-column")
                    .append(
                        "<i class='aux-success-icon auxicon-check-mark-circle-outline'></i>"
                    );
            } else {
                // error & try again with next item
                this.$currentNode
                    .addClass("aux-error")
                    .find(".column-status span")
                    .text(response.data.errorMessage);
            }
            // Then jump to next item
            this._processUpdates();
        },

        /* ------------------------------------------------------------------------------ */
        // Step Manager

        /**
         * the step manager functionality (Used in modal box)
         */
        _stepManager: function(e) {
            // Check currentTarget existence
            if (!e.currentTarget) {
                return;
            }
            // check preventDefault existence
            if (typeof e.preventDefault !== "undefined") {
                e.preventDefault();
            }
            // Set variables
            var $buttonElement = $(e.currentTarget),
                $modalSectionElement = this.$modalElement.find(
                    ".aux-steps-col"
                );
            this._ajaxData = {
                action: "aux_step_manager",
                next_step: $buttonElement.data("next-step"),
                nonce: $buttonElement.data("step-nonce"),
                args: $buttonElement.data("args"),
                next_action: $buttonElement.data("next-action")
            };
            // Manipulation
            $modalSectionElement.addClass("aux-step-in-progress");
            this._controlActions("off");
            // Call AJAX with current _ajaxData value
            this._globalAJAX(
                function(response) {
                    if (response !== null && response.success) {
                        $modalSectionElement
                            .removeClass("aux-step-in-progress")
                            .html(response.data.markup);
                        // Run hidden action
                        if (this._ajaxData.next_action) {
                            this._tempManager({
                                currentTarget: this._modalButton
                            });
                        }
                    } else {
                        console.log(response);
                    }
                    this._controlActions("on");
                }.bind(this)
            );
        },

        /* ------------------------------------------------------------------------------ */
        // template Manager

        /**
         * Template kits manager functionality
         */
        _tempManager: function(e) {
            // Check currentTarget existence
            if (!e.currentTarget) {
                return;
            }
            // check preventDefault existence
            if (typeof e.preventDefault !== "undefined") {
                e.preventDefault();
            }
            // Set variables
            var $buttonElement = $(e.currentTarget),
                $modalSectionElement =
                    this.$modalElement != null
                        ? this.$modalElement.find(".aux-steps-col")
                        : null;
            this._ajaxData = {
                action: "auxin_templates_data",
                verify: $buttonElement.data("nonce"),
                ID: $buttonElement.data("template-id"),
                type: $buttonElement.data("template-type"),
                tmpl: $buttonElement.data("template-page-tmpl"),
                status: $buttonElement.data("status-type"),
                title: $buttonElement.data("template-title")
            };
            // Manipulation
            if (this._ajaxData.status === "copy") {
                $buttonElement.addClass("aux-button-loading");
            }
            this._controlActions("off");
            // Call AJAX with current _ajaxData value
            this._globalAJAX(
                function(response) {
                    if (response !== null && response.success) {
                        if (response.data.status === "copy") {
                            // Put our data in elementor localStorage
                            this._updateElementorLocalStorage(
                                this._ajaxData.type,
                                response.data.result.content
                            );
                            // Then do these actions
                            $buttonElement.addClass("aux-button-success");
                            setTimeout(function() {
                                $buttonElement.removeClass(
                                    "aux-button-success aux-button-loading"
                                );
                            }, 1000);
                        } else {
                            // Change button type to copy
                            $buttonElement
                                .data("status-type", "copy")
                                .prop("data-status-type", "copy")
                                .addClass("aux-copy-template aux-orange")
                                .removeClass(
                                    "aux-import-template aux-has-next-action aux-green2"
                                )
                                .removeAttr("href");
                            // Change button text to copy
                            $buttonElement
                                .find("span")
                                .text(response.data.label);
                            // Display the more button
                            $buttonElement
                                .next(".aux-more-button")
                                .removeClass("hide");
                            // Update modal section data with success template
                            $modalSectionElement.html(response.data.result);
                        }
                    } else {
                        // Update modal section data with error template
                        $modalSectionElement.html(response.data);
                    }
                    this._controlActions("on");
                }.bind(this)
            );
        },

        /**
         * Update elementor localStorage data with new custom elements
         */
        _updateElementorLocalStorage: function(elementsType, elements) {
            this._elStorage["transfer"] = {
                type: "copy",
                elementsType: elementsType,
                elements: elements
            };
            localStorage.setItem("elementor", JSON.stringify(this._elStorage));
        },

        /* ------------------------------------------------------------------------------ */
        // Demo Manager

        /**
         * Demo manager main function
         */
        _demoManager: function(e) {
            // Check currentTarget existence
            if (!e.currentTarget) {
                return;
            }
            // check preventDefault existence
            if (typeof e.preventDefault !== "undefined") {
                e.preventDefault();
            }
            // Set variable
            var $buttonElement = $(e.currentTarget),
                $buttonParentEl = $buttonElement.closest(
                    ".aux-setup-demo-actions"
                ),
                $buttonWrapperEl = $buttonParentEl.find(".aux-return-back"),
                $progressBarEl = $buttonParentEl.find(".aux-progress"),
                $optionsElement = this.$modalElement.find(".aux-install-demos"),
                $demoProgress = this.$modalElement.find(
                    ".aux-install-demos-waiting"
                );
            this._ajaxData = {
                action: "auxin_demo_data",
                ID: $buttonElement.data("import-id"),
                verify: $buttonElement.data("nonce"),
                options: $optionsElement
                    .find(".aux-import-parts")
                    .serializeArray()
            };
            this.$progressBarLabelEl = $progressBarEl.find(
                ".aux-progress-label"
            );

            // Actions
            $optionsElement.addClass("hide");
            $demoProgress.removeClass("hide");
            $buttonWrapperEl.addClass("hide");
            $progressBarEl.removeClass("hide");
            this.$progressBarLabelEl.text("Getting Demo Data ...");

            this._controlActions("off");
            this._globalAJAX(
                function(response) {
                    if (response !== null && response.success) {
                        this._demoImport({
                            target: e.currentTarget,
                            step: "download",
                            message: "Downloading Media ...",
                            index: null
                        });
                    } else {
                        console.log(response);
                        $optionsElement.removeClass("hide");
                        $demoProgress.addClass("hide");
                        $buttonWrapperEl.removeClass("hide");
                        $progressBarEl.addClass("hide");
                        this._controlActions("on");
                    }
                }.bind(this)
            );
        },

        /**
         * Import step by step
         */
        _demoImport: function(data) {
            // Set variable
            this._ajaxData = {
                action: "import_step",
                step: data.step,
                index: data.index
            };

            this.$progressBarLabelEl.text(data.message);

            this._globalAJAX(
                function(response) {
                    if (response !== null && response.success) {
                        if (response.data.next !== "final") {
                            this._demoImport({
                                target: data.target,
                                step: response.data.next,
                                message: response.data.message,
                                index: response.data.hasOwnProperty("index")
                                    ? response.data.index
                                    : ""
                            });
                        } else {
                            this.$progressBarLabelEl.text(
                                response.data.message
                            );
                            setTimeout(
                                function() {
                                    this._controlActions("on");
                                    // Next Step Trigger
                                    this._stepManager({
                                        currentTarget: data.target
                                    });
                                }.bind(this),
                                1000
                            );
                        }
                    } else {
                        console.log(response);
                    }
                }.bind(this)
            );
        },

        /**
         * Uninstall demo functionality
         */
        _uninstallDemo: function(e) {
            // Check currentTarget existence
            if (!e.currentTarget) {
                return;
            }
            // check preventDefault existence
            if (typeof e.preventDefault !== "undefined") {
                e.preventDefault();
            }
            // Set variable
            var $buttonElement = $(e.currentTarget),
                $buttonParentEl = $buttonElement.closest(
                    ".aux-setup-demo-actions"
                );
            this._ajaxData = {
                action: "aux_ajax_uninstall",
                id: $buttonElement.data("demo-id"),
                key: $(this._modalButton).data("demo-key"),
                nonce: $buttonElement.data("demo-nonce"),
                plugins: $buttonElement.data("demo-plugins")
            };

            // Actions
            $buttonParentEl.find(".aux-return-back").addClass("hide");
            $buttonParentEl.find(".aux-progress").removeClass("hide");

            this._controlActions("off");

            this._globalAJAX(
                function(response) {
                    $buttonParentEl
                        .find(".aux-return-back")
                        .removeClass("hide");
                    $buttonParentEl.find(".aux-progress").addClass("hide");

                    if (response !== null && response.success) {
                        this.$modalElement
                            .find(".aux-steps-col")
                            .html(response.data.markup);
                        $(this._modalButton)
                            .removeClass("aux-uninstall aux-orange")
                            .addClass("aux-green2")
                            .text(response.data.button)
                            .attr("href", response.data.url);
                    } else {
                        console.log(response);
                    }
                    this._controlActions("on");
                }.bind(this)
            );
        },

        /* ------------------------------------------------------------------------------ */
        // Plugin Manager

        /**
         * Install/Activate Plugin
         */
        _pluginManager: function(e) {
            // Check currentTarget existence
            if (!e.currentTarget) {
                return;
            }
            // check preventDefault existence
            if (typeof e.preventDefault !== "undefined") {
                e.preventDefault();
            }
            // Set variable
            var $buttonElement = $(e.currentTarget);
            this.$buttonParentEl = $buttonElement.closest(
                ".aux-has-required-plugins"
            );
            this.$pluginsListEl = this.$buttonParentEl.find(
                ".aux-wizard-plugins"
            );
            this._selectedPluginsNum = this.$buttonParentEl.find(
                '.aux-plugin input[name="plugin[]"]:checked'
            ).length;
            this._itemsCompleted = 0;
            this._attemptsBuffer = 0;
            this._currentItem = null;
            this._buttonTarget = e.currentTarget;
            this.$currentNode = null;

            // Manipulation
            this.$pluginsListEl.addClass("installing");
            $buttonElement
                .text(aux_setup_params.btnworks_text)
                .addClass("disabled");

            this._controlActions("off");
            this._processPlugins();
        },

        /**
         * Process selected plugins
         */
        _processPlugins: function() {
            var self = this,
                doNext = false,
                $pluginsList = this.$buttonParentEl.find(".aux-plugin");

            // Scroll on each progress in modal view
            this._pluginScrollTo();

            if (this.$currentNode) {
                if (!this.$currentNode.data("done_item")) {
                    this._itemsCompleted++;
                    this.$currentNode.data("done_item", 1);
                }
                this.$currentNode.find(".spinner").css("visibility", "hidden");
            }

            $pluginsList.each(function() {
                if (self._currentItem == null || doNext) {
                    if (
                        $(this)
                            .find('input[name="plugin[]"]')
                            .is(":checked")
                    ) {
                        $(this).addClass("work-in-progress");
                        self._currentItem = $(this).data("slug");
                        self.$currentNode = $(this);
                        self.$currentNode
                            .find(".spinner")
                            .css("visibility", "visible");
                        self._installPlugin();
                        doNext = false;
                    }
                } else if ($(this).data("slug") === self._currentItem) {
                    $(this).removeClass("work-in-progress");
                    doNext = true;
                }
            });

            // If all plugins finished, then
            if (this._itemsCompleted >= this._selectedPluginsNum) {
                // Activate control actions
                this._controlActions("on");
                // Remove installing class
                this.$buttonParentEl
                    .find(".aux-wizard-plugins")
                    .removeClass("installing");
                // Remove disable class from button
                $(this._buttonTarget).text(aux_setup_params.activate_text);
                // Change the text of "Skip This Step" button to "Next Step"
                this.$buttonParentEl
                    .find(".skip-next")
                    .text(aux_setup_params.nextstep_text);
                // Continue loading process
                if (
                    this.$buttonParentEl.find(".aux-plugin").not(".aux-success")
                        .length == 0 &&
                    this.$buttonParentEl.hasClass("aux-modal-item")
                ) {
                    // Change button text and data value if all required plugins has been installed & activated
                    this._stepManager({ currentTarget: this._buttonTarget });
                }
            }
        },

        /**
         * Process plugin by slug
         */
        _installPlugin: function() {
            if (this._currentItem) {
                var plugins = this.$buttonParentEl
                    .find('.aux-wizard-plugins input[name="plugin[]"]:checked')
                    .map(function() {
                        return $(this).val();
                    })
                    .get();
                this._ajaxData = {
                    action: "aux_setup_plugins",
                    wpnonce: aux_setup_params.wpnonce,
                    slug: this._currentItem,
                    plugins: plugins
                };
                this._globalAJAX(
                    function(response) {
                        this._pluginActions(response);
                    }.bind(this)
                );
            }
        },

        /**
         * Plugin activation events
         */
        _pluginActions: function(response) {
            // Check response type
            if (typeof response === "object" && response.success) {
                // Update plugin status message
                this.$currentNode
                    .find(".column-status span")
                    .text(response.data.message);
                // At this point, if the response contains the url, it means that we need to install/activate it.
                if (typeof response.data.url !== "undefined") {
                    if (this.currentItemHash == response.data.hash) {
                        this.$currentNode
                            .data("done_item", 0)
                            .find(".column-status span")
                            .text("failed");
                        this.currentItemHash = null;
                        this._installPlugin();
                    } else {
                        // we have an ajax url action to perform.
                        this._ajaxUrl = response.data.url;
                        this._ajaxData = response.data;
                        this.currentItemHash = response.data.hash;
                        this._globalAJAX(
                            function(html) {
                                // Reset ajax url to default admin ajax value
                                this._ajaxUrl = aux_setup_params.ajaxurl;
                                this.$currentNode
                                    .find(".column-status span")
                                    .text(response.data.message);
                                this._installPlugin();
                            }.bind(this)
                        );
                    }
                } else {
                    // otherwise it's just installed and we should make a notify to user
                    this.$currentNode
                        .addClass("aux-success")
                        .find(".aux-check-column")
                        .remove();
                    this.$currentNode
                        .find(".check-column")
                        .append(
                            "<i class='aux-success-icon auxicon-check-mark-circle-outline'></i>"
                        );
                    // Then jump to next plugin
                    this._processPlugins();
                }
            } else {
                // If there is an error, we will try to reinstall plugin twice with buffer checkup.
                if (this._attemptsBuffer > 1) {
                    // Reset buffer value
                    this._attemptsBuffer = 0;
                    // error & try again with next plugin
                    this.$currentNode
                        .addClass("aux-error")
                        .find(".column-status span")
                        .text("Ajax Error!");
                    this._processPlugins();
                } else {
                    // Try again & update buffer value
                    this.currentItemHash = null;
                    this._attemptsBuffer++;
                    this._installPlugin();
                }
            }
        },

        /**
         * Scroll to active plugin row
         */
        _pluginScrollTo: function() {
            $(".aux-modal-item .aux-wizard-plugins").each(function() {
                $(this).scrollTo($(this).find(".work-in-progress"), 400);
            });
        },

        /* ------------------------------------------------------------------------------ */
        // General Events

        /**
         * Enable/Disable some control actions
         */
        _controlActions: function(type) {
            switch (type) {
                case "on":
                    $(window).off("beforeunload");
                    $(document).on("keydown keypress keyup");
                    $(".aux-pp-close").removeClass("hide");
                    break;
                default:
                    $(window).on("beforeunload", function() {
                        return aux_setup_params.onbefore_text;
                    });
                    $(document).off("keydown keypress keyup");
                    $(".aux-pp-close").addClass("hide");
            }
        },

        /**
         * Isotope callbacks
         */
        _callIsotope: function() {
            // isotope for template page
            this.$isotopeTemplate = $(".aux-isotope-templates").AuxIsotope({
                itemSelector: ".aux-iso-item",
                revealTransitionDuration: 0,
                revealBetweenDelay: 0,
                revealTransitionDelay: 0,
                hideTransitionDuration: 0,
                hideBetweenDelay: 0,
                hideTransitionDelay: 0,
                updateUponResize: true,
                transitionHelper: true,
                filters: ".aux-filters",
                slug: "filter",
                imgSizes: true
            });

            // general isotope layout
            this.$isotopeList = $(".aux-isotope-list").AuxIsotope({
                itemSelector: ".aux-iso-item",
                revealTransitionDuration: 600,
                revealBetweenDelay: 50,
                revealTransitionDelay: 0,
                hideTransitionDuration: 300,
                hideBetweenDelay: 0,
                hideTransitionDelay: 0,
                updateUponResize: true,
                transitionHelper: true,
                filters: ".aux-filters",
                slug: "filter",
                imgSizes: true
            });
            // isotope for plugins list
            this.$isotopePlugins = $(".aux-isotope-plugins-list").AuxIsotope({
                itemSelector: ".aux-iso-item",
                revealTransitionDuration: 600,
                revealBetweenDelay: 50,
                revealTransitionDelay: 50,
                hideTransitionDuration: 100,
                hideBetweenDelay: 0,
                hideTransitionDelay: 0,
                updateUponResize: true,
                transitionHelper: true,
                filters: ".aux-filters",
                slug: "filter",
                imgSizes: true
            });
        },

        /**
         * Refresh the isotope layout on load of each image
         */
        _lazyloadConfig: function() {
            document.addEventListener('lazyloaded', function( e ){
                $(window).trigger('resize');
            });
        },

        /**
         * Global Manipulations
         */
        _manipulations: function() {
            // Auxin Toggle Select Plugin
            $(".aux-togglable").AuxinToggleSelected();

            // init plugins border effect
            $('.aux-wizard-plugins input[name="plugin[]"]').each(function() {
                if ($(this).is(":checked")) {
                    $(this)
                        .closest(".aux-table-row")
                        .addClass("is-checked");
                } else {
                    $(this)
                        .closest(".aux-table-row")
                        .removeClass("is-checked");
                }
                $(this).click(function() {
                    if ($(this).is(":checked")) {
                        $(this)
                            .closest(".aux-table-row")
                            .addClass("is-checked");
                    } else {
                        $(this)
                            .closest(".aux-table-row")
                            .removeClass("is-checked");
                    }
                });
            });

            // Install plugins button display depends on user's checkbox selection
            $(".aux-plugins-step input[type=checkbox]").change(function() {
                if (
                    $('.aux-wizard-plugins input[name="plugin[]"]').filter(
                        ":checked"
                    ).length > 0
                ) {
                    $(".install-plugins").removeClass("disabled");
                } else {
                    $(".install-plugins").addClass("disabled");
                }
            });

            // Install demos button display depends on user's checkbox selection
            $(document).on(
                "click",
                ".aux-install-demos input[type=checkbox]",
                function(e) {
                    if (
                        $(".featherlight-content")
                            .find("input[type=checkbox]")
                            .filter(":checked").length > 0
                    ) {
                        $(".featherlight-content")
                            .find(".button-next")
                            .removeClass("aux-next-step")
                            .data("callback", "install_demos")
                            .attr("data-callback", "install_demos")
                            .text(aux_setup_params.makedemo_text);
                    } else {
                        $(".featherlight-content")
                            .find(".button-next")
                            .addClass("aux-next-step")
                            .text(aux_setup_params.nextstep_text)
                            .data("callback", null)
                            .removeAttr("data-callback");
                    }
                }
            );

            // a simple event to select custom demo type
            $(document).on("click", ".aux-radio", function(e) {
                $(this)
                    .closest("form")
                    .find(".aux-border")
                    .removeClass("is-checked");
                $(this)
                    .parent(".aux-border")
                    .addClass("is-checked");
            });

            // Display/Hide the pophover box of more button (Used in template kits three dotted button)
            $(document).on("click", ".aux-more-button", function(e) {
                e.preventDefault();
                $(this)
                    .next(".aux-more-items")
                    .toggleClass("aux-display");
            });
        },

        /**
         * Get envato elements license code
         */
        _verifyEnvatoElementsEmail: function(e) {

            // Check currentTarget existence
            if (!e.currentTarget) {
                return;
            }

            var email = $('.email-field').val();
            var self = this;
            var $modalSectionElement = this.$modalElement.find(
                ".aux-steps-col"
            );
            this._buttonTarget = e.currentTarget
            this._ajaxData = {
                action: 'aux_verify_envato_elements_email',
                email: email,
            };
            $modalSectionElement.addClass("aux-step-in-progress");
            this._globalAJAX(
                function(response) {
                    if (response.status) {
                        self._stepManager({ currentTarget: self._buttonTarget });
                    } else {
                        $modalSectionElement.removeClass("aux-step-in-progress");
                        $('.token-wrapper .result').addClass('error').text(response.message);
                    }
                }
            );
        },

        /**
         * verify envato elements token
         */
        _verifyEnvatoElementsToken: function(e) {

            // Check currentTarget existence
            if (!e.currentTarget) {
                return;
            }

            var token = $('.token-field').val();
            var self = this;
            var $modalSectionElement = this.$modalElement.find(
                ".aux-steps-col"
            );
            this._buttonTarget = e.currentTarget
            this._ajaxData = {
                action: 'aux_verify_envato_elements_token',
                token: token,
            };
            $modalSectionElement.addClass("aux-step-in-progress");
            this._globalAJAX(
                function(response) {
                    if (response.status) {
                        $modalSectionElement.removeClass("aux-step-in-progress");
                        $('.token-wrapper .result').addClass('success').text(response.message);
                        self._stepManager({ currentTarget: self._buttonTarget });
                    } else {
                        $modalSectionElement.removeClass("aux-step-in-progress");
                        $('.token-wrapper .result').addClass('error').text(response.message);
                    }
                    
                }
            );
        }
    });

    // A really lightweight plugin wrapper around the constructor,
    // preventing against multiple instantiations
    $.fn[pluginName] = function(options) {
        return this.each(function() {
            new Plugin(this, options);
        });
    };
})(jQuery, window, document);