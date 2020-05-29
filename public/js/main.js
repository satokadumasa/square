$(function(){
  get_meetengs();
});

/**
 * Meetingのリスト表示
 */
function view_meetings(meetings, ref, next) {
  // var meetings = data['meetings'];
  // var ref = data['ref'];
  // var next = data['next'];
  var str = '';

  $('#meeting_list').remove();
  var keys = Object.keys(meetings);
  for (var i = 0 ; i < keys.length ; i++) {
    meeting = meetings[keys[i]];
    str = '';
    str = str + '                <div class="col-sm-12 col-md-12 col-lg-4 col-xl-4">';
    str = str + '                  <a href="/Meeting/detail/' + meeting['Meeting']['id'] + '"">';
    str = str + '                    ' + meeting['Meeting']['title'];
    str = str + '                  </a>';
    str = str + '                </div>';
    $('#meeting').prepend('<div id="meeting_list"></div>');
    $('#meeting_list').append(str);
  }

  // for (var i = 0 ; i < meetings.length; i++) {
  //   meeting = meetings[i];
  //   str = '';
  //   str = str + '                <div class="col-sm-12 col-md-12 col-lg-4 col-xl-4" onclick="meeting_detail(' + meeting['Meeting']['id'] + ')])"">';
  //   str = str + '                  ' + meeting['Meeting']['title'];
  //   str = str + '                </div>';
  //   $('#meeting').prepend('<div id="meeting_list"></div>');
  //   $('#meeting_list').appendChild(str);
  // }
}

/**
 * Meetingのリスト取得
 */
function get_meetengs(){
  var page = $('#page').val();
  page = page ? 1 : page;
  var request = {page: page };

  $.ajax({
    url:'https://september-rain.com/meeting_list',
    type:"POST",
    data:request,
    dataType:"json",
    timespan:1000
  }).done(function(data,status,jqXHR) {
   var meetings = data['meetings'];
   var ref = data['ref'];
   var next = data['next'];
    view_meetings(meetings, ref, next);
  }).fail(function(jqXHR, status, errorThrown ) {
    alert("Can not get meeting list");
  }).always(function(){
  });
}

/**
 * Meeting詳細表示
 */
function meeting_detail(meeting_id){
  var request = {meeting_id: meeting_id}
  $.ajax({
    url:'https://september-rain.com/get_meeting_detail',
    type:"POST",
    data:request,
    dataType:"json",
    timespan:1000
  }).done(function(data,status,jqXHR) {
    view_meetings(data);
  }).fail(function(jqXHR, status, errorThrown ) {
  }).always(function(){
  });  
}