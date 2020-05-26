              <h1>User edit</h1>
              <form action="<!----value:document_root---->User/update" method="post">
                <input type="text" name="Role[id]" length="10" value="<!----value:Role:id---->">
                <div class="row">
                  <div class='col-sm-12 col-md-12 col-lg-4 col-xl-4'>
                    Role name
                  </div>
                  <div class='col-sm-12 col-md-12 col-lg-8 col-xl-8'>
                    <input type="text" name="User[name]" length="64" value="<!----value:Role:name---->">
                  </div>
                </div>
                <div class="row">
                  <div class='col-sm-12 col-md-12 col-lg-12 col-xl-12'>
                    <input type="submit" name="bottom">
                  </div>
                </div>
              </form>
