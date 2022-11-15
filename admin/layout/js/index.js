$(function () {

    'use strict';

    $('.toggle-info').click(function(){
        $(this).toggleClass('selected').parent().next('.panel-body').fadeToggle(100);

        if($(this).hasClass('selected')){
            $(this).html('<i class="fa fa-minus fa-lg"></i>');
        }else{
            $(this).html('<i class="fa fa-plus fa-lg"></i>');
        }
    });

    $("select").selectBoxIt({
        autoWidth:false
    });

    $('[placeholder]').focus(function () {

        $(this).attr('data-text',$(this).attr('placeholder'));

        $(this).attr('placeholder','');

    }).blur(function () {

        $(this).attr('placeholder',$(this).attr('data-text'));
    });

    $('input').each(function(){

        if($(this).attr('required')==='required'){
            $(this).after('<span class="asterisk">*</span>');
        }
    });

    $('.username').blur(function(){

        if($(this).val().length < 4)
        {
            $(this).css("border","1px solid #F00");
            $(this).parent().find('.custom-alert').fadeIn(200);
           
        }else{
            $(this).css("border","1px solid #080");
            $(this).parent().find('.custom-alert').fadeOut(2000);
        }
    });

    $('.email').blur(function(){

        if($(this).val()==='')
        {
            $(this).css("border","1px solid #F00");
            $(this).parent().find('.custom-alert').fadeIn(200);
           
        }else{
            $(this).css("border","1px solid #080");
            $(this).parent().find('.custom-alert').fadeOut(2000);
        }
    });

    $('.fullname').blur(function(){

        if($(this).val().length < 11)
        {
            $(this).css("border","1px solid #F00");
            $(this).parent().find('.custom-alert').fadeIn(200);
           
        }else{
            $(this).css("border","1px solid #080");
            $(this).parent().find('.custom-alert').fadeOut(2000);
        }
    });


    $('.show-pass').hover(function(){
        $('.showpassword').attr('type','text');
    },function(){
        $('.showpassword').attr('type','password');
    });

    $('.confirm').click(function(){
        return confirm('Are You Sure?!');
    });




    $('.cat h3').click(function(){
        $(this).next('.full-view').fadeToggle(500);

    });

    $('.ordering span').click(function(){
        $(this).addClass('active').siblings('span').removeClass('active');

        if($(this).data('view')==='full'){
            $('.cat .full-view').fadeIn(200);
        }else{
            $('.cat .full-view').fadeOut(200);
        }
    });





});//document ready => when document be ready