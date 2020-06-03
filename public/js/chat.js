$(function() {
  username = $.cookie("username");
  passwd = $.cookie("password");

  let methods = {
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
        let message = $(settings['message']).val();
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
            let inner = $('<div class="left"></div>').text(event.data);
            let box = $('<div class="box"></div>').html(inner);
          } else {
            $('#text_for_receive_sdp').val(event.data);
            recv_data = JSON.parse(event.data);
            receiveSdp = recv_data['sdp'];
            if(localVideo){
              addRemoteVideoTag(remote_username)
              onSdpText();
              deleteSdp(recv_data['username']);
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
      let inner = $('<div class="left"></div>').text(message);
      let box = $('<div class="box"></div>').html(inner);
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

function addRemoteVideoTag(remote_username) {
  let insert_html = '';
  let remote_video = 'remote_video_' + remote_username;
  insert_html += '                <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">';
  insert_html += '                  <video id="' + remote_video + '" autoplay="1" style="width: 400px; height: 300px; border: 1px solid black;"></video>';
  insert_html += '                </div>';
  return remote_video
}

