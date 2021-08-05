$(function () {
	'use strict';
	// Switch Between Login&Signup

	$('.login-page h1 span').click(function(){

		$(this).addClass('selected').siblings().removeClass('selected');

		$('.login-page form').hide(1000);

		$('.' + $(this).data('class')).fadeIn(100);

	});

    $(window).scroll(function(){                          
            if ($(this).scrollTop() > 600) {
                $('.sidenav1').fadeIn(500);
            } else {
                $('.sidenav1').fadeOut(500);
            }
        });

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

	$('input[type=text], [type=number], [type=password], [type=email]').each(function() {

		if ($(this).attr('required') == 'required') {
			$(this).after('<span class="asterisk">*</span>');
		}

	});

	// Show More Option 

	var showChar = 80;  // How many characters are shown by default
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
        $(this).parent().prev().toggle(500);
        $(this).prev().fadeToggle(500);
        return false;
    });

    $('.toggle-info').click(function() {
        $(this).toggleClass('selected').parent().next('.panel-body').toggle(600); 
        if($(this).hasClass('selected')) {
            $(this).html('Hide Parent Categories<i class="fa fa-arrow-up fa-lg"></i>');
        } else {
            $(this).html('See Parent Categories<i class="fa fa-arrow-down fa-lg"></i>');
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

	$('.live').keyup(function() {
		$($(this).data('class')).text($(this).val());
	});

	$("#fileToUpload").on("change", function(e){
		var files = $(this)[0].files;
		if(files.length >= 2){
			$("#label-span").text(files.length + " Files Ready To Upload");
		} else {
			var filename = e.target.value.split('\\').pop();
			$("#label-span").text(filename);
		}
	});


    //Initialize tooltips
    $('.nav-tabs > li a[title]').tooltip();
    
    //Wizard
    $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {

        var $target = $(e.target);
    
        if ($target.parent().hasClass('disabled')) {
            return false;
        }
    });

    $(".next-step").click(function (e) {

        var $active = $('.wizard .nav-tabs li.active');
        $active.next().removeClass('disabled');
        nextTab($active);

    });
    $(".prev-step").click(function (e) {

        var $active = $('.wizard .nav-tabs li.active');
        prevTab($active);

    });

    $(".fname").blur(function () {
    	if ($(this).val().length < 4) {
    		$(this).css('border', '1px solid #f00');
    		$(this).parent().find('.custom-alert').fadeIn(300);
    	} else {
    		$(this).css('border', '1px solid #080');
    		$(this).parent().find('.custom-alert').fadeOut(300);
    	}
    }); 

    $(".lname").blur(function () {
    	if ($(this).val().length < 4) {
    		$(this).css('border', '1px solid #f00');
    		$(this).parent().find('.custom-alert').fadeIn(300);
    	} else {
    		$(this).css('border', '1px solid #080');
    		$(this).parent().find('.custom-alert').fadeOut(300);
    	}
    }); 

    $(".email").blur(function () {
    	if ($(this).val() === '') {
    		$(this).css('border', '1px solid #f00');
    		$(this).parent().find('.custom-alert').fadeIn(300);
    	} else {
    		$(this).css('border', '1px solid #080');
    		$(this).parent().find('.custom-alert').fadeOut(300);
    	}
    }); 
    $(".message").blur(function () {
    	if ($(this).val().length < 10) {
    		$(this).css('border', '1px solid #f00');
    		$(this).parent().find('.custom-alert').fadeIn(300);
    	} else {
    		$(this).css('border', '1px solid #080');
    		$(this).parent().find('.custom-alert').fadeOut(300);
    	}
    }); 

    /* Start New Ad items */
    $(".name-newad").blur(function () {
    	if ($(this).val().length < 3) {
    		$(this).css('border', '1px solid #f00');
    		$(this).parent().find('.custom-alert').fadeIn(300);
    	} else {
    		$(this).css('border', '1px solid #080');
    		$(this).parent().find('.custom-alert').fadeOut(300);
    	}
    }); 
    $(".desc-newad").blur(function () {
    	if ($(this).val().length < 10) {
    		$(this).css('border', '1px solid #f00');
    		$(this).parent().find('.custom-alert').fadeIn(300);
    	} else {
    		$(this).css('border', '1px solid #080');
    		$(this).parent().find('.custom-alert').fadeOut(300);
    	}
    }); 
	$(".price-newad").blur(function () {
    	if ($(this).val() <= 0) {
    		$(this).css('border', '1px solid #f00');
    		$(this).parent().find('.custom-alert').fadeIn(300);
    	} else {
    		$(this).css('border', '1px solid #080');
    		$(this).parent().find('.custom-alert').fadeOut(300);
    	}
    });
    $(".country-newad").blur(function () {
    	if ($(this).val().length <= 2) {
    		$(this).css('border', '1px solid #f00');
    		$(this).parent().find('.custom-alert').fadeIn(300);
    	} else {
    		$(this).css('border', '1px solid #080');
    		$(this).parent().find('.custom-alert').fadeOut(300);
    	}
    });



     loadGallery(true, 'a.thumbnailss');

    //This function disables buttons when needed
    function disableButtons(counter_max, counter_current){
        $('#show-previous-image, #show-next-image').show();
        if(counter_max == counter_current){
            $('#show-next-image').hide();
        } else if (counter_current == 1){
            $('#show-previous-image').hide();
        }
    }

    /**
     *
     * @param setIDs        Sets IDs when DOM is loaded. If using a PHP counter, set to false.
     * @param setClickAttr  Sets the attribute for the click handler.
     */

    function loadGallery(setIDs, setClickAttr){
        var current_image,
            selector,
            counter = 0;

        $('#show-next-image, #show-previous-image').click(function(){
            if($(this).attr('id') == 'show-previous-image'){
                current_image--;
            } else {
                current_image++;
            }

            selector = $('[data-image-id="' + current_image + '"]');
            updateGallery(selector);
        });

        function updateGallery(selector) {
            var $sel = selector;
            current_image = $sel.data('image-id');
            $('#image-gallery-caption').text($sel.data('caption'));
            $('#image-gallery-title').text($sel.data('title'));
            $('#image-gallery-image').attr('src', $sel.data('image'));
            disableButtons(counter, $sel.data('image-id'));
        }

        if(setIDs == true){
            $('[data-image-id]').each(function(){
                counter++;
                $(this).attr('data-image-id',counter);
            });
        }
        $(setClickAttr).on('click',function(){
            updateGallery($(this));
        });
    }


 /* End New Ad items */

// ************************Java Script*****************************

        
    
   


function nextTab(elem) {
    $(elem).next().find('a[data-toggle="tab"]').click();
}
function prevTab(elem) {
    $(elem).prev().find('a[data-toggle="tab"]').click();
}


});
function imgFunc() {
			var bigImage = document.getElementById("bigImage");
			var thumbnailsHolder = document.getElementById("thumbnailsHolder");

			thumbnailsHolder.addEventListener("click",function(event){
				if (event.target.tagName == "IMG") {
					bigImage.src = event.target.src;
				}
			},false);
		}
		window.addEventListener("load",imgFunc,false); 
