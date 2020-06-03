/*
 *  Copyright (c) 2015 The WebRTC project authors. All Rights Reserved.
 *
 *  Use of this source code is governed by a BSD-style license
 *  that can be found in the LICENSE file in the root of the source
 *  tree.
 */

'use strict';

let localConnection;
let remoteConnection;
let sendChannel;
let receiveChannel;
let localSdp;
// let dataChannelSend = document.querySelector('textarea#dataChannelSend');
// let dataChannelReceive = document.querySelector('textarea#dataChannelReceive');
// let startButton = document.querySelector('button#startButton');
// let sendButton = document.querySelector('button#sendButton');
// let closeButton = document.querySelector('button#closeButton');

let sdps = {};

// startButton.onclick = createConnection;
// sendButton.onclick = sendData;
// closeButton.onclick = closeDataChannels;

// function enableStartButton() {
//   startButton.disabled = false;
// }

// function disableSendButton() {
//   sendButton.disabled = true;
// }
// RTCPeerConnection = window.RTCPeerConnection || window.webkitRTCPeerConnection || window.mozRTCPeerConnection;


/**
 *  接続処理
 */
function createConnection() {
  // dataChannelSend.placeholder = '';
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
  window.localConnection = localConnection = new RTCPeerConnection(pc_config);
  console.log('Created local peer connection object localConnection');

  sendChannel = localConnection.createDataChannel('sendDataChannel');
  console.log('Created send data channel');

  localConnection.onicecandidate = e => {
    onIceCandidate(localConnection, e);
  };
  sendChannel.onopen = onSendChannelStateChange;
  sendChannel.onclose = onSendChannelStateChange;

  window.remoteConnection = remoteConnection = new RTCPeerConnection(servers);
  console.log('Created remote peer connection object remoteConnection');

  remoteConnection.onicecandidate = e => {
    onIceCandidate(remoteConnection, e);
  };
  remoteConnection.ondatachannel = receiveChannelCallback;

  localConnection.createOffer().then(
      gotDescription1,
      onCreateSessionDescriptionError
  );
  // startButton.disabled = true;
  // closeButton.disabled = false;
}

function onCreateSessionDescriptionError(error) {
  console.log('Failed to create session description: ' + error.toString());
}

function sendData(data, data_type) {
  let data = {data_type: data_type, username: username,data: data, meeting_id: meeting_id};
  sendChannel.send(JSON.stringify(data));
  console.log('Sent Data: ' + data);
}

function closeDataChannels() {
  console.log('Closing data channels');
  sendChannel.close();
  console.log('Closed data channel with label: ' + sendChannel.label);
  receiveChannel.close();
  console.log('Closed data channel with label: ' + receiveChannel.label);
  localConnection.close();
  remoteConnection.close();
  localConnection = null;
  remoteConnection = null;
  console.log('Closed peer connections');
  // startButton.disabled = false;
  // sendButton.disabled = true;
  // closeButton.disabled = true;
  // dataChannelSend.value = '';
  // dataChannelReceive.value = '';
  // dataChannelSend.disabled = true;
  // disableSendButton();
  // enableStartButton();
}

function gotDescription1(desc) {
  localConnection.setLocalDescription(desc);
  console.log(`Offer from localConnection\n${desc.sdp}`);
  remoteConnection.setRemoteDescription(desc);
  remoteConnection.createAnswer().then(
      gotDescription2,
      onCreateSessionDescriptionError
  );
}

function gotDescription2(desc) {
  remoteConnection.setLocalDescription(desc);
  console.log(`Answer from remoteConnection\n${desc.sdp}`);
  localConnection.setRemoteDescription(desc);
}

function getOtherPc(pc) {
  return (pc === localConnection) ? remoteConnection : localConnection;
}

function getName(pc) {
  return (pc === localConnection) ? 'localPeerConnection' : 'remotePeerConnection';
}

function onIceCandidate(pc, event) {
  getOtherPc(pc)
      .addIceCandidate(event.candidate)
      .then(
          () => onAddIceCandidateSuccess(pc),
          err => onAddIceCandidateError(pc, err)
      );
  console.log(`${getName(pc)} ICE candidate: ${event.candidate ? event.candidate.candidate : '(null)'}`);
}

function onAddIceCandidateSuccess() {
  console.log('AddIceCandidate success.');
}

function onAddIceCandidateError(error) {
  console.log(`Failed to add Ice Candidate: ${error.toString()}`);
}

/**
 * 受信チャンネルイベント定義 
 */
function receiveChannelCallback(event) {
  console.log('Receive Channel Callback');
  receiveChannel = event.channel; //  受信チャンネルイベント
  receiveChannel.onmessage = onReceiveMessageCallback; // 受信時
  receiveChannel.onopen = onReceiveChannelStateChange;  // ステータス変更時
  receiveChannel.onclose = onReceiveChannelStateChange; //  受信チャンネル閉鎖
}

/**
 * 受信チャンネル　データ受信時処理
 */
function onReceiveMessageCallback(event) {
  console.log('Received Message');
  // dataChannelReceive.value = event.data;
  // 受け取ったデータをパース
  let datum = JSON.parse(event.data);
  // 自分のを受け取っていいたら何もしないで終了。
  if(datum.username == username) {
    return;
  }
  // 他のユーザのデータであったらキューに詰め込む
  if(sdps.data_type == 'sdp'){
    sdps.push(datum);
  }
   // else {
  return;
  // }
}

/**
 * 送信チャンネルオープン時処理
 */
function onSendChannelStateChange() {
  let readyState = sendChannel.readyState;
  console.log('Send channel state is: ' + readyState);
  if (readyState === 'open') {
    // dataChannelSend.disabled = false;
    // dataChannelSend.focus();
    // sendButton.disabled = false;
    // closeButton.disabled = false;
  } else {
    // dataChannelSend.disabled = true;
    // sendButton.disabled = true;
    // closeButton.disabled = true;
  }
}

/**
 * 受信チャンネルステータス更新時処理
 */
function onReceiveChannelStateChange() {
  let readyState = receiveChannel.readyState;
  console.log(`Receive channel state is: ${readyState}`);
}

function processSdp() {
  let len = sdps.length;
  if(len == 0) {
    return ;
  }
  receiveSdp = sdps[0];
  sdps.shift();
  onSdpText();
}

