$(function () {
	'use strict';

	// Dashboard 

	$('.toggle-info').click(function() {
		$(this).toggleClass('selected').parent().next('.panel-body').fadeToggle(100); 
		if($(this).hasClass('selected')) {
			$(this).html('<i class="fa fa-plus fa-lg"></i>');
		} else {
			$(this).html('<i class="fa fa-minus fa-lg"></i>');
		}
	}); 

	// Trigger The Select Box It

	$("select").selectBoxIt({

		autoWidth: false

	});

	// Hide Placeholder On Form Focus 
	$('[placeholder]').focus(function() {

		$(this).attr('data-text', $(this).attr('placeholder'));
		$(this).attr('placeholder', '');

	}).blur(function () {

		$(this).attr('placeholder', $(this).attr('data-text'));

	});

	// Add Asterisk On Required Field 

	$('input').each(function() {

		if ($(this).attr('required') == 'required') {
			$(this).after('<span class="asterisk">*</span>');
		}

	});
	// Convert Password Field To Text Field On Hover

	var passField = $('.password');

	$('.show-pass').hover(function () {

		passField.attr('type', 'text');

	}, function() {
		passField.attr('type', 'password');
	});

	// Confirmation Message On Button
	$('.confirm').click(function () {
		return confirm('Are You Sure To Delete?');

	});

	// Category View Option

	$('.cat h3').click(function () {
		$(this).next('.full-view').fadeToggle();
	});

	$('.option span').click(function () {
		$(this).addClass('active').siblings('span').removeClass('active');

		if($(this).data('view') === 'full') {
			$('.cat .full-view').fadeIn(250);
		} else {
			$('.cat .full-view').fadeOut();
		}

	});

	// Show Delete Button On Child Cats

	$('.child-link').hover(function() {
		$(this).find('.show-delete').fadeIn();

	}, function(){
		$(this).find('.show-delete').fadeOut();

	});


	// Show More Option 

	var showChar = 50;  // How many characters are shown by default
    var ellipsestext = "...";
    var moretext = "Show more <i class='fa fa-arrow-down' aria-hidden='true'></i>";
    var lesstext = "Show less <i class='fa fa-arrow-up' aria-hidden='true'></i>";
    $('.more').each(function() {
        var content = $(this).html();
 
        if(content.length > showChar) {
 
            var c = content.substr(0, showChar);
            var h = content.substr(showChar, content.length - showChar);
 
            var html = c + '<span class="moreellipses">' + ellipsestext+ '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">' + moretext + '</a></span>';
 
            $(this).html(html);
        }
 
    });
    $(".morelink").click(function(){
        if($(this).hasClass("less")) {
            $(this).removeClass("less");
            $(this).html(moretext);
        } else {
            $(this).addClass("less");
            $(this).html(lesstext);
        }
        $(this).parent().prev().toggle(100);
        $(this).prev().toggle(1000);
        return false;
    });

});