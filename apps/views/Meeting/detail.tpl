              <div class='row'>
                <div class='col-sm-12 col-md-4 col-lg-4 col-xl-4'>
                  Meeting title
                </div>
                <div class='col-sm-12 col-md-8 col-lg-8 col-xl-8'>
                  <!----value:Meeting:title---->
                </div>
              </div>

              <div class='row'>
                <div class='col-4'>
                  Username
                </div>
                <div id="username" class='col-8'>
                </div>
              </div>
              <div class='row'>
                <div class='col-4'>
                  Status
                </div>
                <div id="status" class='col-8'>
                </div>
              </div>

              <div class='row'>
                <div class='col-sm-12 col-md-12 col-lg-12 col-xl-12'>
                  <input type="checkbox" id="use_video" checked="1">use Video</input>
                  <input type="checkbox" id="use_audio" checked="1">use Audio</input>
                  <button type="button" onclick="startVideo();">Start Media</button>
                  <button type="button" onclick="stopVideo();">Stop Media</button>
                  &nbsp;
                  <input type="checkbox" id="send_only">send only</input>
                  <button type="button" onclick="connect();" id="connect">Connect</button>
                  <button type="button" onclick="hangUp();">Hang Up</button>
                  <div>
                    <video id="local_video" autoplay style="width: 320px; height: 240px; border: 1px solid black;"></video>
                    <video id="remote_video" autoplay style="width: 320px; height: 240px; border: 1px solid black;"></video>
                  </div>

                  <p>SDP to send:&nbsp;
                    <button type="button" onclick="copySdp();">copy local SDP</button><br />
                    <textarea id="text_for_send_sdp" rows="5" cols="60" readonly="readonly"></textarea>
                  </p>
                  <p>SDP to receive:&nbsp;
                    <button type="button" onclick="onSdpText();">Receive remote SDP</button><br />
                    <textarea id="text_for_receive_sdp" rows="5" cols="60"></textarea>
                  </p>
                </div>
              </div>

              <hr>
              <div class="row">
                <div class="col-sm-4">
                  <A HREF="<!----value:document_root---->Meeting/index/">INDEX</A>
                </div>
                <div class="col-sm-4">
                  <A HREF="<!----value:document_root---->Meeting/edit/<!----value:Meeting:id---->/">EDIT</A>
                </div>
                <div class="col-sm-4">
                  <A HREF="<!----value:document_root---->Meeting/delete/<!----value:Meeting:id---->/">DELETE</A>
                </div>
              </div>
              <script>
              meeting_id = <!----value:Meeting:id---->;
              </script>
              <script src="/js/chat.js"></script>
              <script src="/js/webrtc.js" type="text/javascript"></script>
