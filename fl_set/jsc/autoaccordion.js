var AutoAccordion = Accordion.extend({
	initialize: function(handles, drawers, options) {
		this.addEvent('onActive', function(handle, i) {
			handle.addClass('selected');
		});
		this.addEvent('onBackground', function(handle, i) {
			handle.removeClass('selected');
		});
		
		// run parent initializer
		this.parent.apply(this, arguments);
		
		// this next part adds the automatic opening magic to each â€œHandleâ€
		this.togglers.each(function(handle, index, array) {
			// and the magic hover opening dealie!
			handle.hoverOpenTimer = null
			handle.getElement('a').addEvents({
				
				mouseover: function(thisHandle) {
					thisHandle.hoverOpenTimer = $clear(thisHandle.hoverOpenTimer);
					thisHandle.hoverOpenTimer = this.display.delay(100, this, index);
				}.bind(this, handle),
				
				mouseout: function(thisHandle) {
					thisHandle.hoverOpenTimer = $clear(thisHandle.hoverOpenTimer);
				}.bind(this, handle),
				
				focus: this.display.pass(index, this) // supports tab based keyboard navigation
			});
		}.bind(this));
	}
});

// Public domain donationware, see http://creativepony.com/journal/scripts/cookieautoaccordion/
// Version 1.0

var CookieAutoAccordion = AutoAccordion.extend({
  initialize: function(togglers, elements, options) {
    this.options.cookieName = 'accordion-place';
    this.options.cookieOptions = {path: '/', duration: 30};
    
    this.setOptions(options);
    
    var cookieValue = Cookie.get(this.options.cookieName);
    
    if (cookieValue != false) {
      this.options.show = cookieValue.toInt();
    } //else {  this.options.show = 0; }
    
    // run parent initializer
		this.parent.apply(this, arguments);
		
		this.addEvent('onActive', function(toggler, element) {
		  Cookie.set(this.options.cookieName, this.togglers.indexOf(toggler), this.options.cookieOptions);
		});
  }
});