              <h1>Chat create<br /></h1>
              <form action="<!----value:document_root---->Chat/save" method="post">
                <div class="row">
                  <div class='col-sm-12 col-md-12 col-lg-3 col-xl-2'>
                    Chat user_id
                  </div>
                  <div class='col-sm-12 col-md-12 col-lg-9 col-xl-10'>
                     <input type="text" name="Chat[user_id]" length="10" value="<!----value:Chat:user_id---->">
                  </div>
                </div
                <div class="row">
                  <div class='col-sm-12 col-md-12 col-lg-3 col-xl-2'>
                    Chat body
                  </div>
                  <div class='col-sm-12 col-md-12 col-lg-9 col-xl-10'>
                     <input type="text" name="Chat[body]" length="3000" value="<!----value:Chat:body---->">
                  </div>
                </div
                <div class="row">
                  <div class='col-sm-12 col-md-12 col-lg-12 col-xl-12'>
                    <input type="submit" name="bottom">
                  </div>
                </div>
              </form>
