let localVideo = document.getElementById('local_video');
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

function isUseVideo() {
  let useVideo = document.getElementById('use_video').checked;
  return useVideo;
}

function isUseAudio() {
  let useAudio = document.getElementById('use_audio').checked;
  return useAudio;
}

function isSendOnly() {
  let sendOnly = document.getElementById('send_only').checked;
  return sendOnly;
}

// start local video
function startVideo() {
  let useVideo = isUseVideo();
  let useAudio = isUseAudio();
  if ( (! useVideo) && (! useAudio) ) {
    console.warn('NO media to capture');
    return;
  }

  getDeviceStream({video: useVideo, audio: useAudio}) // audio: false
  .then(function (stream) { // success
    logStream('localStream', stream);
    localStream = stream;
    playVideo(localVideo, stream);
  }).catch(function (error) { // error
    console.error('getUserMedia error:', error);
    return;
  });
}

// stop local video
function stopVideo() {
  pauseVideo(localVideo);
  stopLocalStream(localStream);
}

function stopLocalStream(stream) {
  let tracks = stream.getTracks();
  if (! tracks) {
    console.warn('NO tracks');
    return;
  }
  
  for (let track of tracks) {
    track.stop();
  }
}

function logStream(msg, stream) {
  console.log(msg + ': id=' + stream.id);

  var videoTracks = stream.getVideoTracks();
  if (videoTracks) {
    console.log('videoTracks.length=' + videoTracks.length);
    for (var i = 0; i < videoTracks.length; i++) {
      var track = videoTracks[i];
      console.log('track.id=' + track.id);
    }
  }

  var audioTracks = stream.getAudioTracks();
  if (audioTracks) {
    console.log('audioTracks.length=' + audioTracks.length);
    for (var i = 0; i < audioTracks.length; i++) {
      var track = audioTracks[i];
      console.log('track.id=' + track.id);
    }
  }
}

function getDeviceStream(option) {
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
  if ('srcObject' in element) {
    if (! element.srcObject) {
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
  element.volume = 0;
}

function pauseVideo(element) {
  element.pause();
  if ('srcObject' in element) {
    element.srcObject = null;
  }
  else {
    if (element.src && (element.src !== '') ) {
      window.URL.revokeObjectURL(element.src);
    }
    element.src = '';
  }
}

// ----- hand signaling ----
function onSdpText() {
  let text = textToReceiveSdp.value;
  text = _trimTailDoubleLF(text); // for Safar TP --> Chrome
  if (peerConnection) {
    console.log('Received answer text...');
    let answer = new RTCSessionDescription({
      type : 'answer',
      sdp : text,
    });
    setAnswer(answer);
  }
  else {
    console.log('Received offer text...');
    let offer = new RTCSessionDescription({
      type : 'offer',
      sdp : text,
    });
    setOffer(offer);
  }
  textToReceiveSdp.value ='';
}

function sendSdp(sessionDescription) {
  console.log('---sending sdp ---');
  textForSendSdp.value = sessionDescription.sdp;
  textForSendSdp.focus();
  textForSendSdp.select();
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
  let pc_config = {"iceServers":[]};
  let peer = new RTCPeerConnection(pc_config);

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

function makeOffer() {
  peerConnection = prepareNewConnection();

  let options = {};
  if (localStream) {
    if (isSendOnly()) {
      console.log('-- try sendonly ---');
      options = { offerToReceiveAudio: false, offerToReceiveVideo: false };
    }
  }
  else {
    // -- no localStream, so receive --
    console.log('-- try recvonly ---');

    options = { offerToReceiveAudio: true, offerToReceiveVideo: true };

    if ('addTransceiver' in peerConnection) {
      console.log('-- use addTransceiver() for recvonly --');
      peerConnection.addTransceiver('video').setDirection('recvonly');
      peerConnection.addTransceiver('audio').setDirection('recvonly');
    }
  }

  peerConnection.createOffer(options)
  .then(function (sessionDescription) {
    console.log('createOffer() succsess in promise');
    return peerConnection.setLocalDescription(sessionDescription);
  }).then(function() {
    console.log('setLocalDescription() succsess in promise');

    // -- Trickle ICE の場合は、初期SDPを相手に送る -- 
    // -- Vanilla ICE の場合には、まだSDPは送らない --
    //sendSdp(peerConnection.localDescription);
  }).catch(function(err) {
    console.error(err);
  });
}

function setOffer(sessionDescription) {
  if (peerConnection) {
    console.error('peerConnection alreay exist!');
  }
  peerConnection = prepareNewConnection();
  peerConnection.setRemoteDescription(sessionDescription)
  .then(function() {
    console.log('setRemoteDescription(offer) succsess in promise');
    makeAnswer();
  }).catch(function(err) {
    console.error('setRemoteDescription(offer) ERROR: ', err);
  });
}

function makeAnswer() {
  console.log('sending Answer. Creating remote session description...' );
  if (! peerConnection) {
    console.error('peerConnection NOT exist!');
    return;
  }

  let options = {};
  if (! localStream) {
    //options = { offerToReceiveAudio: true, offerToReceiveVideo: true }

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
    //sendSdp(peerConnection.localDescription);
  }).catch(function(err) {
    console.error(err);
  });
}

function setAnswer(sessionDescription) {
  if (! peerConnection) {
    console.error('peerConnection NOT exist!');
    return;
  }

  peerConnection.setRemoteDescription(sessionDescription)
  .then(function() {
    console.log('setRemoteDescription(answer) succsess in promise');
  }).catch(function(err) {
    console.error('setRemoteDescription(answer) ERROR: ', err);
  });
}

// start PeerConnection
function connect() {
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
  if (peerConnection) {
    console.log('Hang up.');
    peerConnection.close();
    peerConnection = null;
    pauseVideo(remoteVideo);
  }
  else {
    console.warn('peer NOT exist.');
  }
}
