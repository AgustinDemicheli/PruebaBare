/**
 * Sexy Alert Box - for mootools 1.2 - jQUery 1.3
 * @name sexyalertbox.v1.2.js
 * @author Eduardo D. Sada - http://www.coders.me/web-js-html/javascript/sexy-alert-box
 * @version 1.2
 * @date 07-Feb-2009
 * @copyright (c) 2009 Eduardo D. Sada (www.coders.me)
 * @license MIT - http://es.wikipedia.org/wiki/Licencia_MIT
 * @example http://www.coders.me/ejemplos/sexy-alert-box/
 * @based in <PBBAcpBox> (Pokemon_JOJO, <http://www.mibhouse.org/pokemon_jojo>)
 * @thanks to Pokemon_JOJO!
 * @features:
 * * Chain Implemented (Cola de mensajes)
 * * More styles (info, error, alert, prompt, confirm)
 * * ESC would close the window
 * * Focus on a default button
*/
//alert("sexyalert")
/*
Class: SexyAlertBox
	Clone class of original javascript function : 'alert', 'confirm' and 'prompt'

Arguments:
	options - see Options below

Options:
	name - name of the box for use different style
	zIndex - integer, zindex of the box
	onReturn - return value when box is closed. defaults to false
	onReturnFunction - a function to fire when return box value
	BoxStyles - stylesheets of the box
	OverlayStyles - stylesheets of overlay
	showDuration - duration of the box transition when showing (defaults to 200 ms)
	showEffect - transitions, to be used when showing
	closeDuration - Duration of the box transition when closing (defaults to 100 ms)
	closeEffect - transitions, to be used when closing
	onShowStart - a function to fire when box start to showing
	onCloseStart - a function to fire when box start to closing
	onShowComplete - a function to fire when box done showing
	onCloseComplete - a function to fire when box done closing
*/
  var $j = jQuery.noConflict();


$j(document).ready(function(){
  Sexy.initialize();
});

jQuery.bind = function(object, method){
  var args = Array.prototype.slice.call(arguments, 2);  
  return function() {
    var args2 = [this].concat(args, $j.makeArray( arguments ));  
    return method.apply(object, args2);  
  };  
};  

jQuery.fn.delay = function(time,func){
	return this.each(function(){
		setTimeout(func,time);
	});
};


jQuery.fn.extend({
  $jchain : [],
  chain: function(fn) {
    this.$jchain.push(fn);
    return this;
  },
  callChain: function(context) {
    return (this.$jchain.length) ? this.$jchain.pop().apply(context, arguments) : false;
  },
  clearChain: function(){
    this.$jchain.empty();
    return this;
  }
});

(function($j) {

  Sexy = {
    getOptions: function() {
      return {
        name            : 'SexyAlertBox',
        zIndex          : 65555,
        onReturn        : false,
        onReturnFunction: function(e) {},
        BoxStyles       : { 'width': 500 },
        OverlayStyles   : { 'backgroundColor': '#000', 'opacity': 0.7 },
        showDuration    : 200,
        closeDuration   : 100,
        moveDuration    : 500,
        onCloseComplete : $j.bind(this, function() {
          this.options.onReturnFunction(this.options.onReturn);
        })
      };
    },


    initialize: function(options) {
      this.i=0;
      this.options = $j.extend(this.getOptions(), options);
			$j('body').append('<div id="BoxOverlay"></div><div id="'+this.options.name+'-Box"><div id="'+this.options.name+'-InBox"><div id="'+this.options.name+'-BoxContent"><div id="'+this.options.name+'-BoxContenedor"></div></div></div></div>');
			
			this.Content    = $j('#'+this.options.name+'-BoxContenedor');
			this.Contenedor = $j('#'+this.options.name+'-BoxContent');
			this.InBox      = $j('#'+this.options.name+'-InBox');
			this.Box        = $j('#'+this.options.name+'-Box');
			
			$j('#BoxOverlay').css({
        position        : 'absolute',
        top             : 0,
        left            : 0,
				opacity         : this.options.OverlayStyles.opacity,
				backgroundColor : this.options.OverlayStyles.backgroundColor,
				'z-index'       : this.options.zIndex,
        height          : $j(document).height(),
				width           : $j(document).width()
			}).hide();
			
			this.Box.css({
        display         : 'none',
        position        : 'absolute',
        top             : 0,
        left            : 0,
				'z-index'       : this.options.zIndex + 2,
				width           : this.options.BoxStyles.width + 'px'
			});

      this.preloadImages();

      $j(window).bind('resize', $j.bind(this, function(){
        if(this.options.display == 1) {
          $j('#BoxOverlay').css({
            height          : 0,
            width           : 0
          });
          $j('#BoxOverlay').css({
            height          : $j(document).height(),
            width           : $j(document).width()
          });
          this.replaceBox();
        }
      }));

      this.Box.bind('keydown', $j.bind(this, function(obj, event){
        if (event.keyCode == 27){
          this.options.onReturn = false;
          this.display(0);
        }      
      }));

      $j(window).bind('scroll', $j.bind(this, function(){
        this.replaceBox();
      }));
			
    },

    replaceBox: function() {
      if(this.options.display == 1) {
        
        this.Box.stop();
        
        this.Box.animate({
          left  : ( ($j(document).width() - this.options.BoxStyles.width) / 2),
          top   : ( $j(document).scrollTop() + ($j(window).height() - this.Box.outerHeight()) / 2 )
        }, {
          duration  : this.options.moveDuration,
          easing    : 'easeOutBack'
        });

        $j(this).delay(this.options.moveDuration, $j.bind(this, function() {
          $j('#BoxAlertBtnOk').focus();
          $j('#BoxPromptInput').focus();
          $j('#BoxConfirmBtnOk').focus();
        }));
      }
    },

    display: function(option) {
      if(this.options.display == 0 && option != 0 || option == 1) {


        if (!$j.support.maxHeight) { //IE6
          $j('embed, object, select').css({ 'visibility' : 'hidden' });
        }


        this.togFlashObjects('hidden');

        this.options.display = 1;


        $j('#BoxOverlay').stop();
        $j('#BoxOverlay').fadeIn(this.options.showDuration, $j.bind(this, function(){
          this.Box.css({
            display         : 'block',
            left            : ( ($j(document).width() - this.options.BoxStyles.width) / 2)
          });
          this.replaceBox();
        }));
      
      } else {

        this.Box.css({
          display         : 'none',
          top             : 0
        });

        this.options.display = 0;

        $j(this).delay(500, $j.bind(this, this.queue));

        $j(this.Content).empty();
        this.Content.removeClass();

        if(this.i==1) {
          $j('#BoxOverlay').stop();
          $j('#BoxOverlay').fadeOut(this.options.closeDuration, $j.bind(this, function(){
            $j('#BoxOverlay').hide();
            if (!$j.support.maxHeight) { //IE6
              $j('embed, object, select').css({ 'visibility' : 'hidden' });
            }

            this.togFlashObjects('visible');

            this.options.onCloseComplete.call();
          }));
        }
      }
    },

    messageBox: function(type, message, properties, input) {
        
        $j(this).chain(function () {

        properties = $j.extend({
          'textBoxBtnOk'        : 'OK',
          'textBoxBtnCancel'    : 'Cancelar',
          'textBoxInputPrompt'  : null,
          'password'            : false,
          'onComplete'          : function(e) {}
        }, properties || {});

        this.options.onReturnFunction = properties.onComplete;

        this.Content.append('<div id="'+this.options.name+'-Buttons"></div>');
        if(type == 'alert' || type == 'info' || type == 'error')
        {
            $j('#'+this.options.name+'-Buttons').append('<input id="BoxAlertBtnOk" type="submit" />');
            
            $j('#BoxAlertBtnOk').val(properties.textBoxBtnOk).css({'width':70});
            
            $j('#BoxAlertBtnOk').bind('click', $j.bind(this, function(){
              this.options.onReturn = true;
              this.display(0);
            }));
                      
            if(type == 'alert') {
              clase = 'BoxAlert';
            } else if(type == 'error') {
              clase = 'BoxError';
            } else if(type == 'info') {
              clase = 'BoxInfo';
            }
            
            this.Content.addClass(clase).prepend(message);
            this.display(1);

        }
        else if(type == 'confirm')
        {

            $j('#'+this.options.name+'-Buttons').append('<input id="BoxConfirmBtnOk" type="submit" /> <input id="BoxConfirmBtnCancel" type="submit" />');
            $j('#BoxConfirmBtnOk').val(properties.textBoxBtnOk).css({'width':100});
            $j('#BoxConfirmBtnCancel').val(properties.textBoxBtnCancel).css({'width':100});

            $j('#BoxConfirmBtnOk').bind('click', $j.bind(this, function(){
              this.options.onReturn = true;
              this.display(0);
            }));

            $j('#BoxConfirmBtnCancel').bind('click', $j.bind(this, function(){
              this.options.onReturn = false;
              this.display(0);
            }));

            this.Content.addClass('BoxConfirm').prepend(message);
            this.display(1);
        }
        else if(type == 'prompt')
        {

            $j('#'+this.options.name+'-Buttons').append('<input id="BoxPromptBtnOk" type="submit" /> <input id="BoxPromptBtnCancel" type="submit" />');
            $j('#BoxPromptBtnOk').val(properties.textBoxBtnOk).css({'width':70});
            $j('#BoxPromptBtnCancel').val(properties.textBoxBtnCancel).css({'width':70});
                        
            type = properties.password ? 'password' : 'text';

            this.Content.prepend('<input id="BoxPromptInput" type="'+type+'" />');
            $j('#BoxPromptInput').val(properties.input);
            $j('#BoxPromptInput').css({'width':250});

            $j('#BoxPromptBtnOk').bind('click', $j.bind(this, function(){
              this.options.onReturn = $j('#BoxPromptInput').val();
              this.display(0);
            }));

            $j('#BoxPromptBtnCancel').bind('click', $j.bind(this, function(){
              this.options.onReturn = false;
              this.display(0);
            }));

            this.Content.addClass('BoxPrompt').prepend(message + '<br />');
            this.display(1);
        }
        else
        {
            this.options.onReturn = false;
            this.display(0);		
        }

      });

      this.i++;

      if(this.i==1) {
        $j(this).callChain(this);
      }
    },

    queue: function() {
      this.i--;
      $j(this).callChain(this);
    },

    chk: function (obj) {
      return !!(obj || obj === 0);
    },

    togFlashObjects: function(state) {
      var hideobj=new Array("embed", "iframe", "object");
      for (y = 0; y < hideobj.length; y++) {
       var objs = document.getElementsByTagName(hideobj[y]);
       for(i = 0; i < objs.length; i++) {
        objs[i].style.visibility = state;
       }
      }
    },

    preloadImages: function() {
      var img = new Array(2);
      img[0] = new Image();img[1] = new Image();img[2] = new Image();
      img[0].src = this.Box.css('background-image').replace(new RegExp("url\\('?([^']*)'?\\)", 'gi'), "$j1");
      img[1].src = this.InBox.css('background-image').replace(new RegExp("url\\('?([^']*)'?\\)", 'gi'), "$j1");
      img[2].src = this.Contenedor.css('background-image').replace(new RegExp("url\\('?([^']*)'?\\)", 'gi'), "$j1");
    },
    

    /*
    Property: alert
      Shortcut for alert
      
    Argument:
      properties - see Options in messageBox
    */		
    alert: function(message, properties) {
      this.messageBox('alert', message, properties);
    },

    /*
    Property: info
      Shortcut for alert info
      
    Argument:
      properties - see Options in messageBox
    */		
    info: function(message, properties){
      this.messageBox('info', message, properties);
    },

    /*
    Property: error
      Shortcut for alert error
      
    Argument:
      properties - see Options in messageBox
    */		
    error: function(message, properties){
      this.messageBox('error', message, properties);
    },

    /*
    Property: confirm
      Shortcut for confirm
      
    Argument:
      properties - see Options in messageBox
    */
    confirm: function(message, properties){
      this.messageBox('confirm', message, properties);
    },

    /*
    Property: prompt
      Shortcut for prompt
      
    Argument:
      properties - see Options in messageBox
    */	
    prompt: function(message, input, properties){
      this.messageBox('prompt', message, properties, input);
    }

  };

})(jQuery);