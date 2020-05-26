<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>
  <!----value:site_name---->
  <!----value:Title---->
</title>
<link rel="shortcut icon" href="/favicon.ico" type="image/vnd.microsoft.icon">
<link rel="stylesheet" href="/css/default.css">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
<script type="text/javascript">
var username = null;
var userid = null;
var passwd = null;
var meeting_id = 0;
var receiveSdp = null;
var settings = {};
</script>
</head>
<body>
<input type="hidden" name="message" id="message">
<div id="document_root">
    <div class="container-fluid">
<!----renderpartial:common/top_menu:top_menu---->
        <div class="row contents">
            <div class="col-sm-12 col-md-12 col-lg-2 col-xl-2 side_menu">
<!----renderpartial:common/side_menu:side_menu---->
            </div>
            <div class="col-sm-8 col-md-8 col-lg-8 col-xl-8 details">
<!----renderpartial:CONTROLLER/ACTION:datas---->
            </div>
            <div class="col-sm-4 col-md-4 col-lg-2 col-xl-2 left_contents" id="chat">
            </div>
        </div>
<!----renderpartial:common/footer:footer---->
    </div>
</div>
</body>
