$(function() {
  username = $.cookie("username");
  passwd = $.cookie("password");

  var methods = {
    init : function( options ) {
      settings = $.extend({
        'uri'   : 'wss://september-rain.com/cable',
        'conn'  : null,
        'message' : '#message',
        'display' : '#display'
      }, options);
      $(settings['message']).keypress( methods['checkEvent'] );
      $(this).chat('connect');
    },

    checkEvent : function ( event ) {
      if (event && event.which == 13) {
        var message = $(settings['message']).val();
        if (message && settings['conn']) {
          settings['conn'].send(message + '');
          $(this).chat('drawText',message,'right');
          $(settings['message']).val('');
        }
      }
    },

    connect : function () {
      if (settings['conn'] == null) {
        settings['conn'] = new WebSocket(settings['uri']);
        settings['conn'].onopen = methods['onOpen'];
        settings['conn'].onmessage = methods['onMessage'];
        settings['conn'].onclose = methods['onClose'];
        settings['conn'].onerror = methods['onError'];
      }
    },

    onOpen : function ( event ) {
      $(this).chat('drawText','サーバに接続','left');
    },

    onMessage : function (event) {
      if (event && event.data) {
        if(event.data) {
          if(event.data == 'CONNECTED') {
            var inner = $('<div class="left"></div>').text(event.data);
            var box = $('<div class="box"></div>').html(inner);
          } else {
            $('#text_for_receive_sdp').val(event.data);
            receiveSdp = event.data;
            if(localVideo){
              onSdpText();
            }
          }
        }
      }
    },

    onError : function(event) {
      $(this).chat('drawText','エラー発生!','left');
    },

    onClose : function(event) {
      $(this).chat('drawText','サーバと切断','left');
      settings['conn'] = null;
      setTimeout(methods['connect'], 3000);
    },
    drawText : function (message, align='left') {
      if($('.box').length){
        $('.box').remove();
      }
      var inner = $('<div class="left"></div>').text(message);
      var box = $('<div class="box"></div>').html(inner);
      $('#chat').prepend(box);
    },
  }; // end of methods

  $.fn.chat = function( method ) {
    if ( methods[method] ) {
      return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
    } else if ( typeof method === 'object' || ! method ) {
      return methods.init.apply( this, arguments );
    } else {
      $.error( 'Method ' +  method + ' does not exist' );
    }
  } // end of function

  $(this).chat({
    'uri':'wss://september-rain.com/cable',
    'message' : '#message',
    'display' : '#chat'
  });
});

