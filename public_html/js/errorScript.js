/*
Error script 
@author Robin Staes ( robin.staes@student.odisee.be )
 */

//============== GLOBAL VARS ==============
var windowHeight = $(window).height();
var windowWidth = $(window).width();
var headerHeight = $('header').height();
var footerHeight = 44; //in px

//============== START ====================
$(document).ready(function() {

    //footer resize
    $('.wrapper').height(((windowHeight - headerHeight * 1.5) - footerHeight));
});