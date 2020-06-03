let receiveSdp = null;
let settings = {};

let micList = document.getElementById("mic_list");
let localVideo = document.getElementById('local_video');
let cameraList = document.getElementById("camera_list");
let speakerList = document.getElementById("speaker_list");
let remoteVideo = document.getElementById('remote_video');
let localStream = null;
let peerConnection = null;

let textForSendSdp = document.getElementById('text_for_send_sdp');
let textToReceiveSdp = document.getElementById('text_for_receive_sdp');

// --- prefix -----
navigator.getUserMedia  = navigator.getUserMedia    || navigator.webkitGetUserMedia ||
                          navigator.mozGetUserMedia || navigator.msGetUserMedia;
RTCPeerConnection = window.RTCPeerConnection || window.webkitRTCPeerConnection || window.mozRTCPeerConnection;
RTCSessionDescription = window.RTCSessionDescription || window.webkitRTCSessionDescription || window.mozRTCSessionDescription;

// ---------------------- media handling -----------------------
$(function(){
  username = $.cookie("username");
  passwd = $.cookie("password");
  $("#username").text(username);
  console.log('Call checkSdp()');
  checkSdp();
  console.log('Call getDeviceList()');
  getDeviceList();
  // startVideo();
  $('#status').prepend('<div id="status_disp" style="color: read;">WAIT</div>');
  $("#connect").prop("disabled", true);
});

/*****
 * デバイスリストの更新
 */
function addDevice(device) {
  if (device.kind === 'audioinput') {
    let id = device.deviceId;
    let label = device.label || 'microphone'; // label is available for https 
    let option = document.createElement('option');
    option.setAttribute('value', id);
    option.innerHTML = label + '(' + id + ')';;
    micList.appendChild(option);
  }
  else if (device.kind === 'videoinput') {
    let id = device.deviceId;
    let label = device.label || 'camera'; // label is available for https 

    let option = document.createElement('option');
    option.setAttribute('value', id);
    option.innerHTML = label + '(' + id + ')';
    cameraList.appendChild(option);
  }
  else if (device.kind === 'audiooutput') {
    let id = device.deviceId;
    let label = device.label || 'speaker'; // label is available for https 

    let option = document.createElement('option');
    option.setAttribute('value', id);
    option.innerHTML = label + '(' + id + ')';
    speakerList.appendChild(option);
  }

  else {
    console.error('UNKNOWN Device kind:' + device.kind);
  }
}

/*****
 * 利用デバイスの取得
 */
function getDeviceList() {
  console.log('getDeviceList() START');
  console.log('Call clearDeviceList()');
  clearDeviceList();
  navigator.mediaDevices.enumerateDevices()
  .then(function(devices) {
    console.log('getDeviceList() get devices');
    devices.forEach(function(device) {
      console.log(device.kind + ": " + device.label + " id = " + device.deviceId);
      addDevice(device);
    });
    $("#start_video_button").prop("disabled", false);
  })
  .catch(function(err) {
    console.error('enumerateDevide ERROR:', err);
  });
}

/*****
 * デバイスリストのクリア
 */
function clearDeviceList() {
  console.log('clearDeviceList() START');
  while(micList.lastChild) {
    console.log('Call micList.removeChild()');
    micList.removeChild(micList.lastChild);
  }
  while(cameraList.lastChild) {
    console.log('Call cameraList.removeChild()');
    cameraList.removeChild(cameraList.lastChild);
  }
  while(speakerList.lastChild) {
    console.log('Call speakerList.removeChild()');
    speakerList.removeChild(speakerList.lastChild);
  }
}

/*****
 * 使用カメラの取得
 */
function getSelectedVideo() {
  console.log('getSelectedVideo() ');
  let id = cameraList.options[cameraList.selectedIndex].value;
  return id;
}

/*****
 * 使用マイクの取得
 */
function getSelectedAudio() {
  console.log('getSelectedAudio() ');
  let id = micList.options[micList.selectedIndex].value;
  return id;
}

/*****
 * 使用スピーカーの取得
 */
function getSelectedSpeaker() {
  console.log('getSelectedSpeaker() ');
  let id = speakerList.options[speakerList.selectedIndex].value;
  return id;
}

/*****
 * スピーカーの設定
 */
function setSpeaker() {
  console.log('setSpeaker() START');
  let speakerId = getSelectedSpeaker();
  localVideo.volume = 0;
  localVideo.setSinkId(speakerId)
  .then(function() {
    console.log('setSinkID Success');
  })
  .catch(function(err) {
    console.error('setSinkId Err:', err);
  });
}

// function isUseVideo() {
//   console.log('isUseVideo() ');
//   let useVideo = document.getElementById('use_video').checked;
//   return useVideo;
// }

// function isUseAudio() {
//   console.log('isUseAudio() ');
//   let useAudio = document.getElementById('use_audio').checked;
//   return useAudio;
// }

function startSelectedVideoAudio() {
  console.log('startSelectedVideoAudio() START');
  let audioId = getSelectedAudio();
  let deviceId = getSelectedVideo();
  console.log('selected video device id=' + deviceId + ' ,  audio=' + audioId);
  let constraints = {
    audio: {
      deviceId: audioId
    },
    video: { 
      deviceId: deviceId
    },
  };
  console.log('mediaDevice.getMedia() constraints:', constraints);

  navigator.mediaDevices.getUserMedia(
   constraints
  ).then(function(stream) {
    console.log('startSelectedVideoAudio() Success');
    localStream = stream;
    logStream('selectedVideo', stream);
    localVideo.srcObject = stream;
    // playVideo(localVideo, stream);
    $('#status_disp').remove();
    $('#status').prepend('<div id="status_disp" style="color: grean;">READY</div>');
    // $("#connect").prop("disabled", false);

    if(receiveSdp == null || receiveSdp == ''){
      connect();
    } else {
      onSdpText();
    }
    $("#start_video_button").prop("disabled", true);
  }).catch(function(err){
    console.error('getUserMedia Err:', err);
  });
}

// start local video
function startVideo() {
  console.log('startVideo() START');
  // let useVideo = isUseVideo();
  // let useAudio = isUseAudio();
  let useVideo = true;
  let useAudio = true;
  if ( (! useVideo) && (! useAudio) ) {
    console.warn('NO media to capture');
    return;
  }

  getDeviceStream({video: useVideo, audio: useAudio}) // audio: false
  .then(function (stream) { // success
    console.log('getDeviceStream() Success');
    logStream('localStream', stream);
    localStream = stream;
    playVideo(localVideo, stream);
    $('#status_disp').remove();
    $('#status').prepend('<div id="status_disp" style="color: grean;">READY</div>');
    $("#connect").prop("disabled", false);
  }).catch(function (error) { // error
    console.error('getUserMedia error:', error);
    return;
  });
}

// stop local video
function stopVideo() {
  console.log('stopVideo() START');
  pauseVideo(localVideo);
  stopLocalStream(localStream);
  $('#status_disp').remove();
  $('#status').prepend('<div id="status_disp" style="color: read;">WAIT</div>');
  $("#connect").prop("disabled", true);
  $("#stop_button").prop("disabled", true);
}

function stopLocalStream(stream) {
  console.log('stopLocalStream() START');
  let tracks = stream.getTracks();
  if (! tracks) {
    console.warn('NO tracks');
    return;
  }

  for (let track of tracks) {
    track.stop();
    $("#start_video_button").prop("disabled", false);
    $("#stop_button").prop("disabled", true);
  }
}

/**
 ・meeting roomにだれかいるのかチェック
 ・既に入室者がいた場合に、入室者のオファーを取得する。
 */
function checkSdp() {
  console.log('checkSdp() START');
  let data = {
    "meeting_id": meeting_id
  };
  $.ajax({
    url:'https://september-rain.com/checksdp',
    type:"POST",
    data:data,
    dataType:"json",
    timespan:1000
  }).done(function(data,status,jqXHR) {
    console.log('checkSdp() Success');
    if(data['status'] == true) {
      receiveSdp = data['sdp'];
      textToReceiveSdp.value = receiveSdp;
      $("#connect").prop("disabled", true);
    }
  }).fail(function(jqXHR, status, errorThrown ) {
  }).always(function(){
  });
}

function logStream(msg, stream) {
  console.log('logStream() START');
  console.log(msg + ': id=' + stream.id);

  let videoTracks = stream.getVideoTracks();
  if (videoTracks) {
    console.log('logStream() exist videoTracks');
    console.log('videoTracks.length=' + videoTracks.length);
    for (let i = 0; i < videoTracks.length; i++) {
      let track = videoTracks[i];
      console.log('track.id=' + track.id);
    }
  }

  let audioTracks = stream.getAudioTracks();
  if (audioTracks) {
    console.log('logStream() exist audioTracks');
    console.log('audioTracks.length=' + audioTracks.length);
    for (let i = 0; i < audioTracks.length; i++) {
      let track = audioTracks[i];
      console.log('track.id=' + track.id);
    }
  }
}

function getDeviceStream(option) {
  console.log('getDeviceStream() ');
  if ('getUserMedia' in navigator.mediaDevices) {
    console.log('navigator.mediaDevices.getUserMadia');
    return navigator.mediaDevices.getUserMedia(option);
  }
  else {
    console.log('wrap navigator.getUserMadia with Promise');
    return new Promise(function(resolve, reject){
      navigator.getUserMedia(option,
        resolve,
        reject
      );
    });
  }
}

function playVideo(element, stream) {
  console.log('playVideo() ');
  if ('srcObject' in element) {
    console.log('playVideo() exist srcObject');
    if (! element.srcObject) {
      console.log('playVideo() srcObject is null. Create srcObject');
      element.srcObject = stream;
    }
    else {
      console.log('stream alreay playnig, so skip');
    }
  }
  else {
    element.src = window.URL.createObjectURL(stream);
  }
  element.play();
  element.volume = 0.5411764705882353;
}

function pauseVideo(element) {
  console.log('pauseVideo() ');
  element.pause();
  if ('srcObject' in element) {
    console.log('pauseVideo() set null.(1)');
    element.srcObject = null;
  }
  else {
    console.log('pauseVideo() set null.(2)');
    if (element.src && (element.src !== '') ) {
      window.URL.revokeObjectURL(element.src);
    }
    element.src = '';
  }
}

// ----- hand signaling ----
function onSdpText() {
  console.log('onSdpText() ');
  settings['conn'].send(JSON.stringify({command: "subscribe", meeting_id: meeting_id,username: username }));
  // receiveSdp = (receiveSdp != null && receiveSdp != '') ? receiveSdp : textToReceiveSdp.value;
  receiveSdp = _trimTailDoubleLF(receiveSdp)
  console.log("onSdpText() text:" + receiveSdp);

  if (peerConnection) {
    console.log('Received answer text...');
    let answer = new RTCSessionDescription({
      type : 'answer',
      sdp : receiveSdp,
    });
    setAnswer(answer);
  }
  else {
    console.log('Received offer text...');
    let offer = new RTCSessionDescription({
      type : 'offer',
      sdp : receiveSdp,
    });
    setOffer(offer);
  }
  $("#hangUp").prop("disabled", false);
}

function sendSdp(sessionDescription) {
  console.log('---sending sdp ---');
  textForSendSdp.value = sessionDescription.sdp;
  let sdp = sessionDescription.sdp;

  let data = {
    "meeting_id": meeting_id,
    "sdp": sdp
  };
  $.ajax({
    url:'https://september-rain.com/registsdp/',
    type:"POST",
    data:data,
    dataType:"json",
    timespan:1000
  }).done(function(revive_data,status,jqXHR) {
    settings['conn'].send(JSON.stringify({command: "message", meeting_id: meeting_id, username: username }));
  }).fail(function(jqXHR, status, errorThrown ) {
  }).always(function(){
  });
}
function copySdp() {
  textForSendSdp.focus();
  textForSendSdp.select();
  document.execCommand('copy');
}

function _trimTailDoubleLF(str) {
  const trimed = str.trim();
  return trimed + String.fromCharCode(13, 10);
}

// ---------------------- connection handling -----------------------
function prepareNewConnection() {
  console.log('prepareNewConnection() ');
  let peer = null;
  let pc_config = {
    "iceServers": [
      {
        "url":"stun:september-rain.com:3478"
      },
      {
        "url": "turn:september-rain.com:3478?transport=udp",
        "credential": passwd,
        "username": username
      },
      {
        "url": "turn:september-rain.com:3478?transport=tcp",
        "credential": passwd,
        "username": username
      },
    ]
  };
  try {
    peer = new RTCPeerConnection(pc_config);
  } catch (e) {
    console.log("Failed to create PeerConnection, exception: " + e.message);
  }

  // --- on get remote stream ---
  if ('ontrack' in peer) {
    peer.ontrack = function(event) {
      console.log('-- peer.ontrack()');
      let stream = event.streams[0];
      playVideo(remoteVideo, stream);
      if (event.streams.length > 1) {
        console.warn('got multi-stream, but play only 1 stream');
      }
    };
  }
  else {
    peer.onaddstream = function(event) {
      console.log('-- peer.onaddstream()');
      let stream = event.stream;
      playVideo(remoteVideo, stream);
    };
  }

  // --- on get local ICE candidate
  peer.onicecandidate = function (evt) {
    console.log('peer.onicecandidate() ');
    if (evt.candidate) {
      console.log(evt.candidate);
      // Trickle ICE の場合は、ICE candidateを相手に送る
      // Vanilla ICE の場合には、何もしない
    } else {
      console.log('empty ice event');
      // Trickle ICE の場合は、何もしない
      // Vanilla ICE の場合には、ICE candidateを含んだSDPを相手に送る
      sendSdp(peer.localDescription);
    }
  };


  // -- add local stream --
  if (localStream) {
    console.log('Adding local stream...');
    if ('addTrack' in peer) {
      console.log('use addTrack()');
      let tracks = localStream.getTracks();
      for (let track of tracks) {
        let sender = peer.addTrack(track, localStream);
      }
    }
    else {
      console.log('use addStream()');
      peer.addStream(localStream);
    }
  }
  else {
    console.warn('no local stream, but continue.');
  }

  return peer;
}

/**
 *  Offerを作成する
 */
function makeOffer() {
  console.log('makeOffer() ');
  peerConnection = prepareNewConnection();

  let options = {};

  options = { offerToReceiveAudio: true, offerToReceiveVideo: true };

  peerConnection.createOffer(options)
  .then(function (sessionDescription) {
    console.log('createOffer() succsess in promise');
    return peerConnection.setLocalDescription(sessionDescription);
  }).then(function() {
    console.log('setLocalDescription() succsess in promise');
    $("#hangUp").prop("disabled", false);
    // -- Trickle ICE の場合は、初期SDPを相手に送る --
    // -- Vanilla ICE の場合には、まだSDPは送らない --
    //sendSdp(peerConnection.localDescription);
  }).catch(function(err) {
    console.error(err);
  });
}

/**
 *　受けたOfferに従いリモートの情報をpeerConnectionにセットする  
 */
function setOffer(sessionDescription) {
  console.log('setOffer() ');
  if (peerConnection) {
    console.error('peerConnection alreay exist!');
  }
  peerConnection = prepareNewConnection();
  peerConnection.setRemoteDescription(sessionDescription)
  .then(function() {
    console.log('setRemoteDescription:offer succsess in promise');
    makeAnswer();
  }).catch(function(err) {
    console.error('setRemoteDescription:offer ERROR: ', err);
  });
}

/**
 *  Answeを作成する。
 */
function makeAnswer() {
  console.log('sending Answer. Creating remote session description...' );
  if (! peerConnection) {
    console.error('peerConnection NOT exist!');
    return;
  }

  let options = {};
  if (! localStream) {
    if ('addTransceiver' in peerConnection) {
      console.log('-- use addTransceiver() for recvonly --');
      peerConnection.addTransceiver('video').setDirection('recvonly');
      peerConnection.addTransceiver('audio').setDirection('recvonly');
    }
  }

  peerConnection.createAnswer(options)
  .then(function (sessionDescription) {
    console.log('createAnswer() succsess in promise');
    return peerConnection.setLocalDescription(sessionDescription);
  }).then(function() {
    console.log('setLocalDescription() succsess in promise');

    // -- Trickle ICE の場合は、初期SDPを相手に送る --
    // -- Vanilla ICE の場合には、まだSDPは送らない --
    // sendSdp(peerConnection.localDescription);
  }).catch(function(err) {
    console.error(err);
  });
}

/**
 *  Offerに対するAnserをpeerConnectionにセットする。
 */
function setAnswer(sessionDescription) {
  console.log('setAnswer() ');
  if (! peerConnection) {
    console.error('peerConnection NOT exist!');
    return;
  }

  peerConnection.setRemoteDescription(sessionDescription)
  .then(function() {
    console.log('setRemoteDescription:answer succsess in promise');
  }).catch(function(err) {
    console.error('setRemoteDescription:answer ERROR: ', err);
  });
}

// start PeerConnection
function connect() {
  settings['conn'].send(JSON.stringify({command: "subscribe", meeting_id: meeting_id,username: username }));

  if (! peerConnection) {
    console.log('make Offer');
    makeOffer();
  }
  else {
    console.warn('peer already exist.');
  }
}

// close PeerConnection
function hangUp() {
  console.log('hangUp() ');
  if (peerConnection) {
    console.log('Hang up.');
    peerConnection.close();
    peerConnection = null;
    pauseVideo(remoteVideo);

    let data = {
      "meeting_id": meeting_id,
    };

    $.ajax({
      url:'https://september-rain.com/remove/',
      type:"POST",
      data:data,
      dataType:"json",
      timespan:1000
    }).done(function(data,status,jqXHR) {
      $('#status_disp').remove();
      $('#status').prepend('<div id="status_disp" style="color: green;">READY</div>');
      $("#connect").prop("disabled", false);
      $("#start_video_button").prop("disabled", false);
      $("#stop_button").prop("disabled", false);
    }).fail(function(jqXHR, status, errorThrown ) {
    }).always(function(){
    });
  }
  else {
    console.warn('peer NOT exist.');
  }
}

function sleep(waitSec, callbackFunc) {
  console.log('sleep() ');
  // 経過時間（秒）
  let spanedSec = 0;

  // 1秒間隔で無名関数を実行
  let id = setInterval(function () {
      spanedSec++;
      // 経過時間 >= 待機時間の場合、待機終了。
      if (spanedSec >= waitSec) {
          // タイマー停止
          clearInterval(id);
          // 完了時、コールバック関数を実行
          if (callbackFunc) callbackFunc();
      }
  }, 1000);
}

function set_user_info(){
  username = $('#username').val();
  passwd = $('#passwd').val();
}
