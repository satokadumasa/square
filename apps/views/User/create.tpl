              <h1>User create<br /></h1>
              <form action="<!----value:document_root---->User/save" method="post">
                <div class="row">
                  <div class='col-sm-12 col-md-12 col-lg-3 col-xl-2'>
                    username
                  </div>
                  <div class='col-sm-12 col-md-12 col-lg-9 col-xl-10'>
                    <input type="text" name="User[username]" length="64" value="<!----value:User:username---->">
                  </div>
                </div>
                <div class="row">
                  <div class='col-sm-12 col-md-12 col-lg-3 col-xl-2'>
                    password
                  </div>
                  <div class='col-sm-12 col-md-12 col-lg-9 col-xl-10'>
                    <input type="text" name="User[password]" length="64" value="<!----value:User:password---->">
                    <br>
                    <input type="text" name="User[password_confirm]" length="64" value="">
                  </div>
                </div>
                <div class="row">
                  <div class='col-sm-12 col-md-12 col-lg-3 col-xl-2'>
                    email
                  </div>
                  <div class='col-sm-12 col-md-12 col-lg-9 col-xl-10'>
                    <input type="text" name="User[email]" length="128" value="<!----value:User:email---->">
                  </div>
                </div>
                <div class="row">
                  <div class='col-sm-12 col-md-12 col-lg-12 col-xl-12'>
                    <input type="submit" name="bottom">
                  </div>
                </div>
              </form>
