
//-----------------------------------------------------------------------------

$(document).ready(function()
{

//-----------------------------------------------------------------------------

	//Style selects, checkboxes, etc
	$("select, input:checkbox, input:radio, input:file").uniform();

	//Date and Range Inputs
	$("input.date").dateinput();

	/**
	 * Get the jQuery Tools Validator to validate checkbox and
	 * radio groups rather than each individual input
	 */

	$('[type=checkbox]').bind('change', function(){
		clearCheckboxError($(this));
	});

//-----------------------------------------------------------------------------

	//validate checkbox and radio groups
	function validateCheckRadio(){
		var err = {};

		$('.radio-group, .checkbox-group').each(function(){
			 if($(this).hasClass('required'))
				if (!$(this).find('input:checked').length)
					err[$(this).find('input:first').attr('name')] = 'Please complete this mandatory field.';
		});

		if (!$.isEmptyObject(err)){
			validator.invalidate(err);
			return false
		}
		else return true;

	}

//-----------------------------------------------------------------------------

	//clear any checkbox errors
	function clearCheckboxError(input){
		var parentDiv = input.parents('.field');

		if (parentDiv.hasClass('required'))
			if (parentDiv.find('input:checked').length > 0){
				validator.reset(parentDiv.find('input:first'));
				parentDiv.find('.error').remove();
			}
	}

//-----------------------------------------------------------------------------

	//Position the error messages next to input labels
	$.tools.validator.addEffect("labelMate", function(errors, event){
		$.each(errors, function(index, error){
			error.input.first().parents('.field').find('.error').remove().end().find('label').after('<span class="error">' + error.messages[0] + '</span>');
		});

	}, function(inputs){
		inputs.each(function(){
			$(this).parents('.field').find('.error').remove();
		});

	});


//-----------------------------------------------------------------------------

	/**
	 * Handle the form submission, display success message if
	 * no errors are returned by the server. Call validator.invalidate
	 * otherwise.
	 */

	$(".TTWForm").validator({effect:'labelMate'});
	/*/
	.submit(function(e){
	   var form = $(this), checkRadioValidation = validateCheckRadio();

		if(!e.isDefaultPrevented() && checkRadioValidation){
			$.post(form.attr('action'), form.serialize(), function(data){
				data = $.parseJSON(data);

				if(data.status == 'success'){
					form.fadeOut('fast', function(){
						$('.TTWForm-container').append('<h2 class="success-message">Success!</h2>');
					});
				}
				else validator.invalidate(data.errors);

			});
		}

		return false;
	});
	//*/

//-----------------------------------------------------------------------------

	var validator = $('.TTWForm').data('validator');

//-----------------------------------------------------------------------------

	var keypress_timeout = null
    var suggest_max = 5;
	$(".TTWForm .refrence").keypress(function(e) {
		var input = $(this);
		if (null != keypress_timeout)
			clearTimeout(keypress_timeout);

        if (13 == e.keyCode) { // enter
            e.preventDefault();
            input.parent().children('.suggest').remove();
            s = input.data('selected');
            s.css('background', 'white');
            input.removeData('selected');
            input.attr('value', '');

            // if not inserted before insert to db and append to reference-list
            var v = new Object();
            var frm = $(".TTWForm-container");
            v.referer_type  = frm.data('type');
            v.referer_id    = frm.data('id');
            v.referred_type = s.data('type');
            v.referred_id   = s.data('id');
            $.post(weburl + 'ajax/refer', 'params=' + JSON.stringify(v), function(data){
                data = $.parseJSON(data);
                if('success' == data.status){
                    // get referred teaser display and add to the teaser-list
                    var d     = new Object();
                    d.content = input.attr('name');
                    d.id      = v.referred_id;
                    d.display = 'teaser';
                    v.list    = false;

                    $.post(weburl + 'ajax/get', 'params=' + JSON.stringify(d), function(data){
                        data = $.parseJSON(data);
                        if('success' == data.status && 0 < data.count){
                            var r = $(data.html);
                            var tl = input.parent().children('.' + v.referred_type + '-teaser-list');
                            tl.append(r);
                            r.trigger("create");
                        }
                    });
                }
            });
            return;
        }

        // on timeout show suggest list
		keypress_timeout = setTimeout(function() {
			var v = new Object();
			v.content = input.attr('name');
			v.name    = input.attr('value');
            v.display = 'tiny';
            v.list    = true;

			$.post(weburl + 'ajax/get', 'params=' + JSON.stringify(v), function(data){
                input.parent().children('.suggest').remove();
                input.data('suggest', false);

				data = $.parseJSON(data);
				if('success' == data.status && 0 < data.count){
                    suggest_max = data.count
                    var d = $('<div class="suggest">' + data.html + '</div>');
                    input.data('suggest', true);
					input.parent().append(d);
                    d.trigger("create");
                    d.css('left', input.position().left + 'px');
                    d.css('top', input.outerHeight() + input.position().top + 'px');
                    d.css('width', input.outerWidth() + 'px');
                    d.data('selected', -1);
				}
			});
		}, 500);
	});

    $(".TTWForm .refrence").keyup(function(e) {
        if (8 == e.keyCode ||  // backspace
            46 == e.keyCode ) { // delete
            $(this).trigger('keypress');
        }
    });

//-----------------------------------------------------------------------------

    $(".TTWForm .refrence").keydown(function(e) {
        var input = $(this);
        if (undefined != input.data('suggest') &&
            true      == input.data('suggest') &&
            (38 == e.keyCode || 40 == e.keyCode)) {
            var suggest = input.parent().children('.suggest');
            var s = suggest.data('selected');
            var child_rows = suggest.children().eq(0).children(s);
            child_rows.eq(s).css('background', 'white');
            if (38 == e.keyCode) { // move up
                if (0 <= s)
                    --s;
            } else if (40 == e.keyCode) { // move down
                ++s;
            }
            s = (s + suggest_max) % suggest_max;
            suggest.data('selected', s);
            input.data('selected', child_rows.eq(s));
            child_rows.eq(s).css('background', 'rgb(209, 229, 223)');
        }
    });

//-----------------------------------------------------------------------------

});

//-----------------------------------------------------------------------------

