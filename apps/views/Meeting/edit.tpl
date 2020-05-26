              <h1>Meeting edit<br /></h1>
              <form action="<!----value:document_root---->Meeting/update" method="post">
                Meeting id<input type="text" name="Meeting[id]" length="255" value="<!----value:Meeting:id---->"><br>
                <div class="row">
                  <div class='col-sm-12 col-md-12 col-lg-3 col-xl-2'>
                    Meeting user_id
                  </div>
                  <div class='col-sm-12 col-md-12 col-lg-9 col-xl-10'>
                     <input type="text" name="Meeting[user_id]" length="10" value="<!----value:Meeting:user_id---->">
                  </div>
                </div
                <div class="row">
                  <div class='col-sm-12 col-md-12 col-lg-3 col-xl-2'>
                    Meeting title
                  </div>
                  <div class='col-sm-12 col-md-12 col-lg-9 col-xl-10'>
                     <input type="text" name="Meeting[title]" length="128" value="<!----value:Meeting:title---->">
                  </div>
                </div
                <div class="row">
                  <div class='col-sm-12 col-md-12 col-lg-3 col-xl-2'>
                    Meeting password
                  </div>
                  <div class='col-sm-12 col-md-12 col-lg-9 col-xl-10'>
                     <input type="text" name="Meeting[password]" length="128" value="<!----value:Meeting:password---->">
                  </div>
                </div
                <div class="row">
                  <div class='col-sm-12 col-md-12 col-lg-3 col-xl-2'>
                    Meeting last_connected_at
                  </div>
                  <div class='col-sm-12 col-md-12 col-lg-9 col-xl-10'>
                     <input type="text" name="Meeting[last_connected_at]" length="19" value="<!----value:Meeting:last_connected_at---->">
                  </div>
                </div
                <div class="row">
                  <div class='col-sm-12 col-md-12 col-lg-12 col-xl-12'>
                    <input type="submit" name="bottom">
                  </div>
                </div>
              </form>
