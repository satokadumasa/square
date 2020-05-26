              <h1>Meeting create<br /></h1>
              <form action="<!----value:document_root---->Meeting/save" method="post">
                <input type="hidden" name="Meeting[user_id]" value="<!----value:Meeting:user_id---->">
                <div class="row">
                  <div class="col-sm-12 col-md-12 col-lg-3 col-xl-3">
                    Title
                  </div>
                  <div class="col-sm-12 col-md-12 col-lg-9 col-xl-9">
                     <input type="text" name="Meeting[title]" length="128" value="<!----value:Meeting:title---->">
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12 col-md-12 col-lg-3 col-xl-3">
                    Password
                  </div>
                  <div class="col-sm-12 col-md-12 col-lg-9 col-xl-9">
                     <input type="text" name="Meeting[password]" length="128" value="<!----value:Meeting:password---->"><br>
                     <input type="text" name="Meeting[password_confirm]" length="128" value="<!----value:Meeting:password---->">
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <input type="submit" name="bottom">
                  </div>
                </div>
              </form>
