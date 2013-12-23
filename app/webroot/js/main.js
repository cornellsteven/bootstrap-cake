/**
 * Define all variables up front as per best practices
 */
var animateAlert;

/**
 * Method for handling animated alert messages logic
 */
animateAlert = function(el) {
	var alerts, _animateAlert;

	alerts = $('.alert-brief');

	// No alerts? Exit early
	if ( ! alerts.length && el === undefined) {
		return;
	}

	/**
	 * Method to animate alert messages (called by animateAlert())
	 */
	_animateAlert = function(el) {
		var $this, _height, _minus_height;

		$this = $(el);
		_height = $this.outerHeight();
		_minus_height = _height - (_height * 2);

		$this.css('top', _minus_height).show().animate({
			top: '0px'
		}, 400).delay(4600).animate({
			top: _minus_height + 'px'
		}, 400, function() {
			$this.remove();
		});
	};

	if (typeof el === 'object') {
		_animateAlert(el);
	} else {
		alerts.each(function() {
			_animateAlert(this);
		});
	}
};

$(function() {
	
	animateAlert();
	
});