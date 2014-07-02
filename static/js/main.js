
//-----------------------------------------------------------------------------

$(document).ready(function()
{

//-----------------------------------------------------------------------------

	//Style selects, checkboxes, etc
	//$("select, input:checkbox, input:radio, input:file").uniform();

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
        }/* else if (input.hasClass('find')) {
            window.location.href = weburl + 'admin/' + s.data('type') + '/view/' + s.data('id');
        }*/
        /******************************************************************************************/
        //modify redirect of URL, implement for search boxes those are in normal user pages -- people/research/publications
        else if (input.hasClass('find')) {
            if(window.location.href.indexOf("admin") > -1){
                window.location.href = weburl + 'admin/' + s.data('type') + '/view/' + s.data('id');
            }else if(window.location.href.indexOf("publication") > -1){
                window.location.href = weburl + s.data('type') + 's/' + s.data('id');
            }else {
                window.location.href = weburl + s.data('type') + '/' + s.data('id');
            }

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

/*add on show/hide js code*/
    //research
    var research_count = $(".research_list li.research_li").length;//count the number of research
    var x=3;//number of originally show
    $('.research_list li.research_li:lt('+x+')').show();//the originally display
    //$('.test').text(research_count+" of research.");
    if(research_count<=3)//if the number of research is less than this number
        $('#loadAllResearch').hide();//then hide the load all link
    //load all rest research
    $('#loadAllResearch').click(function () {
        x=research_count;//set the number of displaying to the number of researches
        $('.research_list li.research_li:lt('+x+')').show();//show content
        $('#showLessResearch').show();//show the show less link
        $('#loadAllResearch').hide();//hide the load all link
    });
    //show less/change to orignal display
    $('#showLessResearch').click(function () {
        x=3;//set the number to orignal one
        $('.research_list li.research_li').not(':lt('+x+')').hide();//hide the overflow contents
        $('#loadAllResearch').show();//show the load all link
        $('#showLessResearch').hide();//hide the show less link
    });
    //publication
    var publication_count = $(".publication_list li.publication_li").length;//count the number of publication
    var x=3;//number of originally show
    $('.publication_list li.publication_li:lt('+x+')').show();//the originally display
    if(publication_count<=3)//if the number of publication is less than this number
        $('#loadAllPublication').hide();//then hide the load all link
    //load all rest research
    $('#loadAllPublication').click(function () {
        x=publication_count;//set the number of displaying to the number of publications
        $('.publication_list li.publication_li:lt('+x+')').show();//show content
        $('#showLessPublication').show();//show the show less link
        $('#loadAllPublication').hide();//hide the load all link
    });
    //show less/change to orignal display
    $('#showLessPublication').click(function () {
        x=3;//set the number to orignal one
        $('.publication_list li.publication_li').not(':lt('+x+')').hide();//hide the overflow contents
        $('#loadAllPublication').show();//show the load all link
        $('#showLessPublication').hide();//hide the show less link
    });
//-----------------------------------------------------------------------------
//insert form element input on demand
    $('.addImage').click(function (){
        //$('.image_list').append('<li><div class="uploader"><input type="file" name="image[]" accept="image/*" style="opacity: 0;"><span class="filename">No file selected</span><span class="action">Choose File</span></div><button class="removeButton" id="remove">Remove</button></li>');
        $('.image_list').append('<li class="addtional_image"><input type="file" name="image[]" accept="image/*"/><a class="rmImage"><font size="2">Remove<font></a></li>');
        var number = $('.addtional_image').length;
        //limit number of addtional images
        if(number>=5){
            $('.addImage').hide();//hide add link
        }

        
        $('.rmImage').unbind("click",removeImageHandler);
        $('.rmImage').click(removeImageHandler);

    
    });
    
    var removeImageHandler = function(){
        $(this).parent("li").remove();  
        var number = $('.addtional_image').length; 
        //limit number of addtional images    
        if(number<5){
            $('.addImage').show();//show add link
        }
    };

    $('.addVideo').click(function (){
        //$('.image_list').append('<li><div class="uploader"><input type="file" name="image[]" accept="image/*" style="opacity: 0;"><span class="filename">No file selected</span><span class="action">Choose File</span></div><button class="removeButton" id="remove">Remove</button></li>');
        $('.video_list').append('<li class="addtional_video"><input type="file" name="video[]" accept="video/*"/><a class="rmVideo"><font size="2">Remove<font></a></li>');
        var number = $('.addtional_video').length;
        //limit number of addtional videos
        if(number>=5){
            $('.addVideo').hide();//hide add link
        }

        $('.rmVideo').unbind("click",removeVideoHandler);
        $('.rmVideo').click(removeVideoHandler);

    });
    var removeVideoHandler = function(){
        $(this).parent("li").remove();
        var number = $('.addtional_video').length;
        //limit number of addtional videos
        if(number<5){
            $('.addVideo').show();//show add link
        }
    };
    $('.addDoc').click(function (){
        //$('.image_list').append('<li><div class="uploader"><input type="file" name="image[]" accept="image/*" style="opacity: 0;"><span class="filename">No file selected</span><span class="action">Choose File</span></div><button class="removeButton" id="remove">Remove</button></li>');
        $('.doc_list').append('<li class="addtional_doc"><input type="file" name="video[]" accept="video/*"/><a class="rmDoc"><font size="2">Remove<font></a></li>');
        var number = $('.addtional_doc').length;
        //$('.info').text(number+" li.");
        //limit number of addtional docs
        if(number>=5){
            $('.addDoc').hide();//hide add link
        }

        $('.rmDoc').unbind("click",removeDocHandler);
        $('.rmDoc').click(removeDocHandler);

    });
    var removeDocHandler = function(){
        $(this).parent("li").remove();
        var number = $('.addtional_doc').length;
        //$('.info').text(number+" li.");
        //limit number of addtional docs
        if(number<5){
            $('.addDoc').show();//show add link
        }
    };
//-----------------------------------------------------------------------------
    // check password length while typing
    $("#people_password").keyup(function() {
        var password = $(this).val();
        var length = password.length;
        if(length<6)
            $("#password_check_result").text("Password length is " + length + ", is too short.");
        else if(length>32)
            $("#password_check_result").text("Password length is " + length + ", is too long.");
        else
            $("#password_check_result").text("Password length is " + length + ", is good to go.");
        //$("#password_check_result").text(password+"    "+length);
    });
//-----------------------------------------------------------------------------
 // check nsid is following format while typing
    $("#people_nsid").keyup(function() {
        var nsid = $(this).val();
        var patt = /[a-zA-Z]{3}[0-9]{3}/;
        if(patt.test(nsid))
            $("#nsid_check_result").text("NSID is good to go.");
        else
            $("#nsid_check_result").text("NSID should be 3 letters plus 3 numbers");
    });
//-----------------------------------------------------------------------------
});


