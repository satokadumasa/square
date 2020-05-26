async function server(){
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
    // なんらかの手段で offer を受け取ったものとして
    await pc.setRemoteDescription(offer);

    const answer = await pc.createAnswer();

    answer.type; // "answer"
    answer.sdp; // SDP本体

    await pc.setLocalDescription(answer);
}
