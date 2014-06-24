
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

    function error(message) {
        alert(message);
    }

//-----------------------------------------------------------------------------

    function selectSuggest(sug, id) {
        var s = sug.data('selected');
        var child_rows = sug.children().eq(0).children(s);
        child_rows.eq(s).removeClass('focused');

        sug.data('selected', id);
        child_rows.eq(id).addClass('focused');

        if (sug.parent().children('.refrence').length > 0) {
            input = sug.parent().children('.refrence');
        } else if (sug.parent().children('.find').length > 0) {
            input = sug.parent().children('.find');
        };

        input.data('selected', child_rows.eq(id));
    };

//-----------------------------------------------------------------------------

    function insertSuggest(sug) {
        input = null;

        if (sug.parent().children('.refrence').length > 0) {
            input = sug.parent().children('.refrence');
        } else if (sug.parent().children('.find').length > 0) {
            input = sug.parent().children('.find');
        };

        sug.remove();
        s = input.data('selected');
        input.removeData('selected');
        input.attr('value', '');

        if (input.hasClass('refrence')) {
            // if not inserted before insert to db and append to reference-list
            var frm = input.closest('.TTWForm-container');
            var v = {
                'referer_type'  : frm.data('type'),
                'referer_id'    : frm.data('id'),
                'referred_type' : s.data('type'),
                'referred_id'   : s.data('id'),
            };
            $.post(weburl + 'ajax/add-reference', 'params=' + JSON.stringify(v), function(data){
                data = $.parseJSON(data);
                if('success' == data.status){
                    // get referred teaser display and add to the teaser-list
                    var d = {
                        'content' : input.attr('name'),
                        'id'      : v.referred_id,
                        'display' : 'teaser',
                    };
                    v.list    = false;

                    $.post(weburl + 'ajax/get', 'params=' + JSON.stringify(d), function(data){
                        data = $.parseJSON(data);
                        if('success' == data.status && 0 < data.count){
                            var r = $(data.html);
                            var tl = input.parent().find('.' + v.referred_type + '-refrence-list');
                            tl.append(r);
                            r.hover(on_hover_of_removable_refrence_teaser);
                            r.mouseleave(on_mouseleave_of_removable_refrence_teaser);
                            r.trigger("create");
                        } else if('error' == data.status){
                            error(data.message);
                        }
                    });
                } else if('error' == data.status){
                    error(data.message);
                }
            });
        } else if (input.hasClass('find')) {
            window.location.href = weburl + 'admin/' + s.data('type') + '/view/' + s.data('id');
        }
    }

//-----------------------------------------------------------------------------

	var keypress_timeout = null
    var keypress_timeout_ms = 500;
    var suggest_max = 5;
	$(".TTWForm .refrence, .TTWForm .find").keypress(function(e) {
		var input = $(this);
		if (null != keypress_timeout)
			clearTimeout(keypress_timeout);

        if (13 == e.keyCode) { // enter
            e.preventDefault();
            insertSuggest(input.parent().children('.suggest'));
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

                    d.children().eq(0).children()
                        .mouseover(function (e){
                            selectSuggest(d, $(e.srcElement).closest('.tiny').index());
                        }).mousedown(function (e){
                            if (1 == e.which)
                                insertSuggest(d);
                        });
                } else if('error' == data.status){
                    error(data.message);
                }
			});
		}, keypress_timeout_ms);
	});

    $(".TTWForm .refrence, .TTWForm .find").focusout(function() {
        var input = $(this);
        if (input.data('suggest')) {
            input.parent().children('.suggest').remove();
        };
    });

    $(".TTWForm .refrence, .TTWForm .find").keyup(function(e) {
        if (8 == e.keyCode ||  // backspace
            46 == e.keyCode ) { // delete
            $(this).trigger('keypress');
        }
    });

//-----------------------------------------------------------------------------

    $(".TTWForm .refrence, .TTWForm .find").keydown(function(e) {
        var input = $(this);
        if (undefined != input.data('suggest') &&
            true      == input.data('suggest') &&
            (38 == e.keyCode || 40 == e.keyCode || 27 == e.keyCode)) {

            var suggest = input.parent().children('.suggest');

            if (27 == e.keyCode) { // escape
                suggest.remove();
                input.removeData('selected');
                input.attr('value', '');
            } else {
                var s = suggest.data('selected');
                if (38 == e.keyCode) { // move up
                    if (0 <= s)
                        --s;
                } else if (40 == e.keyCode) { // move down
                    ++s;
                }
                s = (s + suggest_max) % suggest_max;
                selectSuggest(suggest, s);
            }
        }
    });

//-----------------------------------------------------------------------------

    //$(".yearpicker").each(function() {
    //    var yp = $(this);
    //    for (i = new Date().getFullYear() + 1; i > 1900; --i)
    //    {
    //        yp.append($('<option />').val(i).html(i));
    //    }
    //});

//-----------------------------------------------------------------------------

    //$(".monthpicker").each(function() {
    //    var months = [ "January", "February", "March", "April", "May", "June",
    //                   "July", "August", "September", "October", "November", "December" ];
    //    var mp = $(this);
    //    for (i = 0; i < 12; ++i)
    //    {
    //        mp.append($('<option />').val(months[i]).html(months[i]));
    //    }
    //});

//-----------------------------------------------------------------------------

    $(".removable-refrence-list").children().sortable({
        stop: function(event, ui) {

            var frm = ui.item.closest('.TTWForm-container');
            var v = {
                'referer_type'    : frm.data('type'),
                'referer_id'      : frm.data('id'),
                'referred_type'   : ui.item.data('type'),
                'referred_orders' : {},
            };

            ui.item.parent().children().each(function() {
                v.referred_orders[$(this).data('id')] = $(this).index();
            });

            $.post(weburl + 'ajax/order-reference', 'params=' + JSON.stringify(v), function(data){
                data = $.parseJSON(data);
                if('success' == data.status){
                    // don't do any thing
                } else if('error' == data.status){
                    error(data.message);
                }
            });

        }
    });
    $(".removable-refrence-list").children().disableSelection();

    function on_hover_of_removable_refrence_teaser() {
        var elm = $(this);
        var img = $('#remove-refrence-button');
        elm.append(img);
        img.show();
    }
    $(".removable-refrence-list .teaser").hover(on_hover_of_removable_refrence_teaser);

    function on_mouseleave_of_removable_refrence_teaser() {
        var elm = $(this);
        var img = $('#remove-refrence-button');
        img.hide();
    }
    $(".removable-refrence-list .teaser").mouseleave(on_mouseleave_of_removable_refrence_teaser);

//-----------------------------------------------------------------------------

    $("#remove-refrence-button").hover(function(event) {
        var elm = $(this);
        elm.css('opacity', 1);
    });

    $("#remove-refrence-button").mouseleave(function(event) {
        var elm = $(this);
        elm.css('opacity', 0.3);
    });

    $("#remove-refrence-button").click(function(event) {
        event.preventDefault();
        event.stopPropagation();

        var elm = $(this);
        var frm = elm.closest('.TTWForm-container');
        var s = elm.closest('.teaser');
        var v = {
            'referer_type'  : frm.data('type'),
            'referer_id'    : frm.data('id'),
            'referred_type' : s.data('type'),
            'referred_id'   : s.data('id'),
        };

        s.hide();

        $.post(weburl + 'ajax/remove-reference', 'params=' + JSON.stringify(v), function(data){
            data = $.parseJSON(data);
            if('success' == data.status){
                // detach the remove-button from this teaser
                var img = $('#remove-refrence-button');
                img.css('opacity', 0.3);
                img.hide();
                img.detach().appendTo(frm);

                // remove referred node teaser from the teaser-list
                s.remove();
            } else if('error' == data.status){
                s.show();
                error(data.message);
            }
        });
    });

//-----------------------------------------------------------------------------

});

//-----------------------------------------------------------------------------

