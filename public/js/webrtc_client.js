// TURNを強制
async function client(){
  const pc = new RTCPeerConnection({
    iceServers: [
      {
        urls: "turn:157.7.129.91:3478",
        credential: "si118xjas",
        username: "ksato",
      },
    ],
    iceTransportPolicy: "relay",
  });

  pc.addEventListener('icecandidate', ev => {
    if (ev.candidate == null) {
      // 全ての経路を見つけ尽くしたとき
      return false;
    }
    // 経路の候補が見つかったとき
  });
  const offer = await pc.createOffer();

  offer.type; // "offer"
  offer.sdp; // SDP本体

  // video + audio
  const stream = await navigator.mediaDevices.getUserMedia({
    video: true,
    audio: true,
  });

  stream.getTracks(); // MSTぜんぶ
  document.getElementById('square').srcObject = stream;
  // stream.getAudioTracks(); // audioのMSTだけ
  // stream.getVideoTracks(); // videoのMSTだけ

  // const $video = document.createElement('video');
  // $video.autoPlay = true;

  // // 自身のストリーム or
  // // なんらかの手段で取得したP2P相手のストリーム
  // $video.srcObject = stream;
  // // or
  // $video.src = window.URL.createObjectURL(stream);

}
