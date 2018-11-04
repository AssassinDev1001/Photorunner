/**
 * jQuery plugin custom checkbox 
 * created with love by putra.surya.h69@gmail.com | twitter @PutraSoerya9
 *
 * @Version 1.0.2
 * @License MIT License
 *
 * - v 1.0.2 change list
 * 		- have new properties on which element will prevent the label to active/inactive
 */


(function($){

	var Init = function () {

		var _ = this,
			b = 0,
			uniq_ = 'custom_checkbox_' + (Math.random() * 99999999).toString(),
			op_disable = {
				"zoom": 	"1",
				"filter": 	"alpha(opacity=50)",
				"opacity": 	"0.5"
			},
			op_enable = {
				"zoom": 	"1",
				"filter": 	"alpha(opacity=100)",
				"opacity": 	"1"
			};

		function active () {
			if (_.group) {
				_.p.find('input[name="'+_.radio+'"]:checked').prop('checked', false)
					.prev().attr('src', _.opt.check);
			}
			_.holder.attr('src', _.opt.checked); 
			_.i.prop('checked', true);
		}

		function unactive () {
			_.holder.attr('src', _.opt.check);
			_.i.prop('checked', false);
		}

		function status () { 
			return _.inputed.is(':checked'); 
		}

		function clicked (e) {  
			if ( _.opt.prevent && $(e.target).is( _.opt.prevent ) ) { return; }

			e.preventDefault();

			if (status()) { 
				if (!_.group) {
					unactive();
				}
			}
			else {
				active();
			}
			_.opt.onChange(_.i, p);
			_.i.trigger("change");
		}

		// Handle focus event in element and make space button work 
		function focusHandle(e) { 
			var self = $(this);

			$(document).unbind('keydown.'+uniq_).bind('keydown.'+uniq_, function(e) {
				if (e.keyCode==32) {
					self.trigger('click.'+uniq_);
					return false;
				}
			});
		}

		// Destroy keydown event 
		function focusDestroy(e) {
			$(document).unbind('keydown.'+uniq_);
		}

		// Disable input 
		_.disableIt = function () {
			$(_._).unbind('click.'+uniq_)
					.unbind('focusin.'+uniq_)
					.unbind('focusout.'+uniq_);
				
			if (_.opt.disable!='') {
				m.find("img").attr("src", o.disable);
				return;
			}
			$(_._).css(op_disable);
		}

		// Enable input
		_.activeIt = function () {
			$(_._).unbind('click.'+uniq_).bind('click.'+uniq_, clicked)
					.unbind('focusin.'+uniq_).bind('focusin.'+uniq_, focusHandle)
					.unbind('focusout.'+uniq_).bind('focusout.'+uniq_, focusDestroy);

			if (_.opt.disable!='') {
				m.find("img").attr("src", o.check);
				return;
			}
			$(_._).css(op_enable);
		}


		_.initialize = function (i, o, p, t) {

			_.opt = o;
			_.p = p;
			_.i = i;

			var m = $("<div/>", {
						'tabindex': 	(i.attr('tabindex')!=='') ? i.attr('tabindex') : 0,
						'role': 		'button',
						'class': 		_.opt.classof
					}),
				h = $("<img/>", { 'src': _.opt.check });
			
			_._ = ( ! t ) ? m : t; 
			
			if (_.i.is(':checked')) {
				h.attr('src', _.opt.checked);
			}
				
			m.append(h);
			_.i.before(m);
			_.i.css('display', 'none');
			m.append(i);
			_.holder = h; 
			_.inputed = i;
				
			if (i.attr('type')=='radio') {
				_.group = true;
				_.radio = i.attr('name');
			}
			else {
				_.group = false;
			}
				
			if (i.prop("disabled")) {
				
				_.disableIt();
			}
			else {
				
				$(_._).unbind('click.'+uniq_).bind('click.'+uniq_, clicked)
						.unbind('focusin.'+uniq_).bind('focusin.'+uniq_, focusHandle)
						.unbind('focusout.'+uniq_).bind('focusout.'+uniq_, focusDestroy);
			}

			_.opt.onLoad(i, p);

			return _;
		}
	};




	$.fn.checkbox = function(o) {
			
		var len = this.length;

		return this.each(function(index){ 

			var _ = $(this);
				opt = $.extend({
					check: 'images/custom-check.jpg', // path image of default checkbox/radio button
					checked: 'images/custom-checked.jpg', // // path image of selected checkbox/radio button
					disable: '', // path image of disable checkbox/radio button
					classof: 'custom-checkbox',
					onChange: function(i, p){},
					onLoad: function(i, p){},
					prevent: false // string of the element example: a <- for <a> html syntax
				}, o);
				
			if ( ! _.is('input')) {
				var i = _.find('input'),
					__ = _;
			}
			else {
				var i = _,
					__ = false;
			}

				p = _.parent();

			if ( (i.attr('type')!='checkbox') && (i.attr('type')!='radio') ) {
				return;
			}
			else if(i.attr('type')=='radio') {
				var nm = i.attr('name');
				while (1==1) {
					if (p.is("body")) {
						break;
					}
					if (p.find('input[type="radio"][name="'+nm+'"]').length > 1) {
						break;
					}
					p = p.parent();
				}
			}
			
			_[0].cCheckbox = (new Init).initialize(i, opt, p, __);;
		});
	};



}) (jQuery);
