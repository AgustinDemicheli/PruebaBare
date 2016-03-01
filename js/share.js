function twitterDialog( titulo, newsLink ){
    var windowWidth = 450;
    var leftPostion = Math.round($(window).width() / 2) - Math.round(windowWidth / 2);
    window.open("http://twitter.com/home?status=" + encodeURI(titulo) + " + " + newsLink, "","status=0, width="+windowWidth+"px, height=250px, left="+leftPostion+"px, top=200px" );
}



/**
 * abre una ventana de dialogo con facebook
 * @link <string>
 */
function facebookDialog( link, title )
{
    var windowWidth = 600;
    var leftPostion = Math.round($(window).width() / 2) - Math.round(windowWidth / 2);
    var facebookUrl = "http://www.facebook.com/sharer.php?u=" + encodeURI(link);
    if (typeof(title) != 'undefined' && title != '')
    {
    	facebookUrl += '&t=' + encodeURI(title);
    }
    window.open(facebookUrl, "","status=0, width="+windowWidth+"px, height=250px, left="+leftPostion+"px, top=200px" );
}


/**
 * abre una ventana de dialogo con googlemas
 * @link <string>
 */
function googlePlusDialog( link )
{
    var windowWidth = 600;
    var leftPostion = Math.round($(window).width() / 2) - Math.round(windowWidth / 2);
    var googlePlusUrl = "https://plusone.google.com/_/+1/confirm?hl=es&url=" + encodeURI(link);
    window.open(googlePlusUrl, "","status=0, width="+windowWidth+"px, height=450px, left="+leftPostion+"px, top=200px" );
}
