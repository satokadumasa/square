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
                  <select id="camera_list" size="1" style="width:160pt;">
                  <option>(video)</option>
                  </select>
                  <select id="mic_list" size="1" style="width:160pt;">
                  <option>(audio)</option>
                  </select>
                  <select id="speaker_list" size="1" style="width:160pt;">
                  <option>(speaker)</option>
                  </select>
                </div>
              </div>
              <div class='row'>
                <div class='col-sm-12 col-md-12 col-lg-12 col-xl-12'>
                  <button onclick="startSelectedVideoAudio()" id="start_video_button" disabled>
                    Start Selected Video/Audio
                  </button>
                  <button onclick="stopVideo()" id="stop_button" disabled>
                    StopVideo
                  </button>
                </div>
              </div>
              <div class='row'>
                <div class='col-sm-12 col-md-12 col-lg-12 col-xl-12'>
                  <button type="button" onclick="connect();" id="connect" disabled>
                    Connect
                  </button>
                  <button type="button" onclick="hangUp();" id="hangUp" disabled>
                    Hang Up
                  </button>
                </div>
              </div>
              <div class='row'>
                <div class='col-sm-12 col-md-12 col-lg-6 col-xl-6'>
                  <video id="local_video" muted="muted" autoplay="1" style="width: 400px; height: 300px; border: 1px solid black;"></video>
                </div>
                <div class='col-sm-12 col-md-12 col-lg-6 col-xl-6'>
                  <video id="remote_video" autoplay="1" style="width: 400px; height: 300px; border: 1px solid black;"></video>
                </div>
              </div>
              <input type="hidden" name="text_for_send_sdp" id="text_for_send_sdp">
              <input type="hidden" name="text_for_receive_sdp" id="text_for_receive_sdp">

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
              let meeting_id = 0;
              meeting_id = <!----value:Meeting:id---->;
              </script>
              <script src="/js/chat.js" type="text/javascript"></script>
              <script src="/js/webrtc.js" type="text/javascript"></script>
