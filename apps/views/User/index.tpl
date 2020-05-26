              <h1>User List</h1>
              <div class="row">
                <div class='col-sm-12 col-md-12 col-lg-2 col-xl-3 '>
                  username
                </div>
                <div class='col-sm-12 col-md-12 col-lg-2 col-xl-3 '>
                  ロール
                </div>
                <div class='col-sm-12 col-md-12 col-lg-2 col-xl-3 '>
                  E-Mail
                </div>
                <div class='col-sm-12 col-md-12 col-lg-2 col-xl-1 '>
                  SHOW
                </div>
                <div class='col-sm-12 col-md-12 col-lg-2 col-xl-1 '>
                  EDIT
                </div>
                <div class='col-sm-12 col-md-12 col-lg-2 col-xl-1 '>
                  DEL
                </div>
              </div>
              <!----iteratior:User:start---->
              <div class="row">
                <div class='col-sm-12 col-md-12 col-lg-2 col-xl-3 '>
                  <!----value:User:username---->
                </div>
                <div class='col-sm-12 col-md-12 col-lg-2 col-xl-3 '>
                  <!----value:User:role_id---->
                </div>
                <div class='col-sm-12 col-md-12 col-lg-2 col-xl-3 '>
                  <!----value:User:email---->
                </div>
                <div class='col-sm-12 col-md-12 col-lg-2 col-xl-1 '>
                  <A HREF="<!----value:document_root---->User/detail/<!----value:User:id---->">DETAIL</A>
                </div>
                <div class='col-sm-12 col-md-12 col-lg-2 col-xl-1 '>
                  <!----helper:UserHelper:edit_link:[Session,User]---->
                </div>
                <div class='col-sm-12 col-md-12 col-lg-2 col-xl-1 '>
                  <!----helper:UserHelper:delete_link:[Session,User]---->
                </div>
              </div>
              <!----iteratior:User:end---->
              <hr>
              <div class="row">
                <div class='col-sm-12 col-md-12 col-lg-4 col-xl-4 '>
                  <A HREF="<!----value:document_root---->User/?page=<!----value:ref---->/">Ref</A>
                </div>
                <div class='col-sm-12 col-md-12 col-lg-4 col-xl-4 '>
                  <A HREF="<!----value:document_root---->User/">INDEX</A>
                </div>
                <div class='col-sm-12 col-md-12 col-lg-4 col-xl-4 '>
                  <A HREF="<!----value:document_root---->User/?page=<!----value:next---->/">Next</A>
                </div>
              </div>
