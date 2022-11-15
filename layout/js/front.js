$(function () {

    'use strict';

    $('.loginPage h4 span').click(function(){
        $('.loginPage form').hide();
        $(this).addClass('spanSelected').siblings('span').removeClass('spanSelected')
        $('.' + $(this).data('class')).fadeIn(100);
    });

    $('.live-name').keyup(function (){
        $('.live-preview .caption h3').text($(this).val());
    });

    $('.live-desc').keyup(function (){
        $('.live-preview .caption p').text($(this).val());
    });

    $('.live-price').keyup(function (){
        $('.live-preview span ').text($(this).val());
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


    $('.confirm').click(function(){
        return confirm('Are You Sure?!');
    });




});//document ready => when document be ready