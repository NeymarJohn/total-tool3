jQuery(document).ready(function($) {
  try {
    window.AudioContext    = window.AudioContext || window.webkitAudioContext;
    navigator.getUserMedia = navigator.getUserMedia 
                            || navigator.webkitGetUserMedia || navigator.mozGetUserMedia;
    window.URL             = window.URL || window.webkitURL;
    
  }
  catch (e) {
    console.log('There is no support audio in this browser');
  }
  $(document).on('click',"#recordPostAudio",function(event) {
    audio_context          = new AudioContext;
    var _SELF = $(this);
    if (!localstream) {
      Wo_CreateUserMedia();
    }
    Wo_Delay(function(){
      if(localstream && recorder && _SELF.attr('data-record') == 0 && Wo_IsRecordingBufferClean()) {
        Wo_CleanRecordNodes();
        recording_time = $('#postRecordingTime');
        recording_node = "post";
        _SELF.attr('data-record','1').html('<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-stop-circle main"><circle cx="12" cy="12" r="10"></circle><rect x="9" y="9" width="6" height="6"></rect></svg>');
        Wo_startRecording();
      }
      else if(localstream && recorder && _SELF.attr('data-record') == 1 && $("[data-record='1']").length == 1){
        Wo_stopRecording();
        _SELF.html('<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>').attr('data-record','2');    
      }
      else if(localstream && recorder && _SELF.attr('data-record') == 2){
        Wo_CleanRecordNodes();
        Wo_StopLocalStream();
        _SELF.html('<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mic" color="#009da0"><path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"></path><path d="M19 10v2a7 7 0 0 1-14 0v-2"></path><line x1="12" y1="19" x2="12" y2="23"></line><line x1="8" y1="23" x2="16" y2="23"></line></svg>').attr('data-record','0');    
      }
      else{
        return false;
      }
    },500);
  });

  $(document).on('click',".record-comment-audio",function(event) {
    audio_context          = new AudioContext;
    var _SELF = $(this);
    if (!localstream) {
      Wo_CreateUserMedia();  
    }
    Wo_Delay(function(){
      if(recorder && _SELF.attr('data-record') == 0 && Wo_IsRecordingBufferClean()) {
        Wo_CleanRecordNodes();
        recording_time = $("span[data-comment-rtime='" + _SELF.attr('id') + "']");
        recording_node = "comm";
        comm_field     = _SELF.attr('id');
        _SELF.attr('data-record','1').html('<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-stop-circle main"><circle cx="12" cy="12" r="10"></circle><rect x="9" y="9" width="6" height="6"></rect></svg>');  
        Wo_startRecording();
      }

      else if(recorder && _SELF.attr('data-record') == 1 && $("[data-record='1']").length == 1){
       Wo_stopRecording();
       _SELF.html('<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>').attr('data-record','2');     
      }

      else if(recorder && _SELF.attr('data-record') == 2){
       Wo_CleanRecordNodes();
       Wo_StopLocalStream();
       _SELF.html('<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mic" color="#009da0"><path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"></path><path d="M19 10v2a7 7 0 0 1-14 0v-2"></path><line x1="12" y1="19" x2="12" y2="23"></line><line x1="8" y1="23" x2="16" y2="23"></line></svg>').attr('data-record','0');  
      }

      else{
        return false;
      }
    },500);
    
  });

  $(document).on('click',".record-chat-audio",function(event) {
    audio_context          = new AudioContext;
    var _SELF = $(this);
    if (!localstream) {
      Wo_CreateUserMedia(); 
    }
    Wo_Delay(function(){
      if(recorder && _SELF.attr('data-record') == 0 && Wo_IsRecordingBufferClean() && $("[data-record='1']").length == 0) {
        Wo_CleanRecordNodes();
        recording_time = $("span[data-chat-rtime='" + _SELF.attr('data-chat-tab') + "']");
        recording_node = "chat";
        chat_tab       = _SELF.attr('data-chat-tab');
        _SELF.attr('data-record','1').html('<svg viewBox="0 -25 511.99982 511" xmlns="http://www.w3.org/2000/svg"><linearGradient id="mic_strop_grad" gradientUnits="userSpaceOnUse" x1="-.00018" x2="511.999295999" y1="230.4997489994" y2="230.4997489994"><stop offset="0" stop-color="#cf8283"/><stop offset=".0208" stop-color="#cb7273"/><stop offset=".2931" stop-color="#bb6263"/><stop offset=".5538" stop-color="#b6595a"/><stop offset=".7956" stop-color="#bc5d5e"/><stop offset="1" stop-color="#a84849"/></linearGradient><path d="m312 151.5c-33.082031 0-60 26.914062-60 60v42c0 33.082031 26.917969 60 60 60 33.085938 0 60-26.917969 60-60v-42c0-33.085938-26.914062-60-60-60zm20 102c0 11.027344-8.972656 20-20 20s-20-8.972656-20-20v-42c0-11.027344 8.972656-20 20-20s20 8.972656 20 20zm-177 40v-102h-15c-11.046875 0-20-8.953125-20-20s8.953125-20 20-20h70c11.046875 0 20 8.953125 20 20s-8.953125 20-20 20h-15v102c0 11.046875-8.953125 20-20 20s-20-8.953125-20-20zm297-142h-30c-11.046875 0-20 8.953125-20 20v122c0 11.046875 8.953125 20 20 20s20-8.953125 20-20v-22h10c33.082031 0 60-26.917969 60-60 0-33.085938-26.917969-60-60-60zm0 80h-10v-40h10c11.027344 0 20 8.972656 20 20s-8.972656 20-20 20zm-375.300781-150.070312 29.347656-50.898438c10.679687-18.523438 30.597656-30.03125 51.976563-30.03125h195.953124c21.328126 0 41.199219 11.449219 51.898438 29.894531l30.304688 50.871094c5.652343 9.488281 2.546874 21.761719-6.945313 27.417969-3.207031 1.910156-6.734375 2.820312-10.214844 2.820312-6.816406 0-13.460937-3.484375-17.203125-9.769531l-30.515625-51.222656c-3.558593-6.175781-10.199219-10.011719-17.324219-10.011719h-195.953124c-7.125 0-13.765626 3.835938-17.324219 10.011719l-29.347657 50.894531c-5.519531 9.570312-17.746093 12.855469-27.316406 7.335938-9.570312-5.515626-12.855468-17.746094-7.335937-27.3125zm356.917969 301.058593-27.664063 47.980469c-10.679687 18.523438-30.597656 30.03125-51.976563 30.03125h-195.953124c-21.378907 0-41.296876-11.507812-51.976563-30.03125l-27.417969-48.058594c-5.476562-9.59375-2.132812-21.808594 7.460938-27.28125s21.808594-2.132812 27.28125 7.460938l27.371094 47.980468c3.515624 6.09375 10.15625 9.929688 17.28125 9.929688h195.949218c7.128906 0 13.765625-3.835938 17.328125-10.011719l27.664063-47.980469c5.515625-9.566406 17.746094-12.851562 27.316406-7.335937 9.570312 5.519531 12.855469 17.75 7.335938 27.316406zm-334.328126-83.058593c-9.679687 9.074218-22.46875 14.070312-36.007812 14.070312h-23.878906c-13.828125 0-26.582032-6.003906-34.992188-16.472656-6.917968-8.609375-5.546875-21.199219 3.0625-28.117188 8.609375-6.921875 21.199219-5.546875 28.117188 3.0625 1.015625 1.261719 2.632812 1.527344 3.8125 1.527344h23.878906c5.621094 0 11.429688-3.664062 11.707031-9.820312-.265625-6.007813-5.621093-11.179688-11.707031-11.179688h-10.589844c-27.90625 0-49.878906-21.5625-50.667968-49.375-.015626-.316406-.023438-.632812-.023438-.949219v-.605469c0-.320312.007812-.640624.023438-.960937.800781-27.664063 22.765624-49.109375 50.667968-49.109375h9.734375c16.136719 0 30.808594 7.96875 39.242188 21.3125 5.902343 9.335938 3.117187 21.691406-6.21875 27.59375-9.335938 5.898438-21.691407 3.117188-27.59375-6.222656-.820313-1.296875-2.679688-2.683594-5.433594-2.683594h-9.730469c-6.09375 0-10.546875 4.34375-10.6875 10.375.144532 6.175781 4.59375 10.625 10.6875 10.625h10.589844c27.683594 0 50.355469 21.832031 51.660156 49.160156.039063.511719.058594 1.03125.058594 1.554688v.9375c0 .53125-.019531 1.0625-.0625 1.585937-.636719 12.890625-6.136719 24.773438-15.648438 33.691407zm0 0" fill="url(#mic_strop_grad)"/></svg>');  
        Wo_startRecording();
      }

      else if(recorder && _SELF.attr('data-record') == 1 && $("[data-record='1']").length == 1){
       Wo_stopRecording();
       _SELF.html('<svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><linearGradient id="mic_cancel_grad" gradientUnits="userSpaceOnUse" x1="0" x2="512" y1="256" y2="256"><stop offset="0" stop-color="#cf8283"/><stop offset=".0208" stop-color="#cb7273"/><stop offset=".2931" stop-color="#bb6263"/><stop offset=".5538" stop-color="#b6595a"/><stop offset=".7956" stop-color="#bc5d5e"/><stop offset="1" stop-color="#a84849"/></linearGradient><path d="m357.140625 181.140625-73.355469 73.359375 73.355469 73.359375c7.8125 7.808594 7.8125 20.472656 0 28.28125-3.902344 3.90625-9.023437 5.859375-14.140625 5.859375s-10.238281-1.953125-14.140625-5.859375l-73.359375-73.355469-73.359375 73.355469c-3.902344 3.90625-9.023437 5.859375-14.140625 5.859375s-10.238281-1.953125-14.140625-5.859375c-7.8125-7.808594-7.8125-20.472656 0-28.28125l73.355469-73.359375-73.355469-73.359375c-7.8125-7.808594-7.8125-20.472656 0-28.28125 7.808594-7.8125 20.472656-7.8125 28.28125 0l73.359375 73.355469 73.359375-73.355469c7.808594-7.8125 20.472656-7.8125 28.28125 0 7.8125 7.808594 7.8125 20.472656 0 28.28125zm79.878906-106.160156c-48.351562-48.351563-112.640625-74.980469-181.019531-74.980469s-132.667969 26.628906-181.019531 74.980469c-48.351563 48.351562-74.980469 112.640625-74.980469 181.019531s26.628906 132.667969 74.980469 181.019531c48.351562 48.351563 112.640625 74.980469 181.019531 74.980469 46.8125 0 92.617188-12.757812 132.460938-36.894531 9.449218-5.722657 12.46875-18.019531 6.746093-27.464844-5.722656-9.449219-18.023437-12.46875-27.46875-6.746094-33.59375 20.347657-72.234375 31.105469-111.738281 31.105469-119.101562 0-216-96.898438-216-216s96.898438-216 216-216 216 96.898438 216 216c0 42.589844-12.664062 84.042969-36.625 119.886719-6.140625 9.179687-3.671875 21.601562 5.511719 27.742187 9.179687 6.136719 21.601562 3.671875 27.742187-5.511718 28.371094-42.441407 43.371094-91.585938 43.371094-142.117188 0-68.378906-26.628906-132.667969-74.980469-181.019531zm0 0" fill="url(#mic_cancel_grad)"/></svg>').attr('data-record','2');
      }

      else if(recorder && _SELF.attr('data-record') == 2){
       Wo_CleanRecordNodes();
       Wo_StopLocalStream();
       _SELF.html('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 264.02 264.02"><linearGradient x1="19.28%" y1="86.72%" x2="88.05%" y2="12.24%" id="recod_grad"><stop offset="0" stop-color="#cf8283"/><stop offset=".0208" stop-color="#cb7273"/><stop offset=".2931" stop-color="#bb6263"/><stop offset=".5538" stop-color="#b6595a"/><stop offset=".7956" stop-color="#bc5d5e"/><stop offset="1" stop-color="#a84849"/></linearGradient><g> <path fill="url(#recod_grad)" d="M210.506,126.764c-4.143,0-7.5,3.358-7.5,7.5c0,17.302-8.038,34.335-22.052,46.73 c-13.11,11.596-30.349,18.247-47.297,18.247h-3.295c-16.947,0-34.186-6.65-47.296-18.247 c-14.015-12.395-22.052-29.427-22.052-46.73c0-4.142-3.357-7.5-7.5-7.5s-7.5,3.358-7.5,7.5c0,21.598,9.883,42.726,27.114,57.966 c14.314,12.662,32.764,20.413,51.381,21.773v35.017H89.675c-4.143,0-7.5,3.358-7.5,7.5c0,4.142,3.357,7.5,7.5,7.5h84.667 c4.143,0,7.5-3.358,7.5-7.5c0-4.142-3.357-7.5-7.5-7.5H139.51v-35.017c18.617-1.361,37.067-9.112,51.382-21.773 c17.232-15.241,27.114-36.369,27.114-57.966C218.006,130.122,214.648,126.764,210.506,126.764z"/> <path fill="url(#recod_grad)" d="M130.421,184.938h3.18c30.021,0,56.357-24.364,56.357-52.14v-80.66 C189.957,24.364,163.622,0,133.6,0h-3.18c-30.022,0-56.357,24.364-56.357,52.138v80.66 C74.063,160.573,100.398,184.938,130.421,184.938z M89.063,52.138C89.063,32.701,108.776,15,130.421,15h3.18 c21.645,0,41.357,17.701,41.357,37.138v80.66c0,19.438-19.712,37.14-41.357,37.14h-3.18c-21.644,0-41.357-17.702-41.357-37.14 V52.138z"/> </g></svg>').attr('data-record','0');  
      }

      else{
        return false;
      }
    },500);
    
  });

  $(document).on('click',"#messages-record",function(event) {
    audio_context          = new AudioContext;
    var _SELF = $(this);
    if (!localstream) {
      Wo_CreateUserMedia(); 
    }
    Wo_Delay(function(){
      if(recorder && _SELF.attr('data-record') == 0 && Wo_IsRecordingBufferClean() && $("[data-record='1']").length == 0) {
        Wo_CleanRecordNodes();
        recording_time = $("span.messages-rtime");
        recording_node = "msg";
        _SELF.attr('data-record','1').html('<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-stop-circle main"><circle cx="12" cy="12" r="10"></circle><rect x="9" y="9" width="6" height="6"></rect></svg>');  
        Wo_startRecording();
      }

      else if(recorder && _SELF.attr('data-record') == 1 && $("[data-record='1']").length == 1){
       Wo_stopRecording();
       _SELF.html('<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>').attr('data-record','2');     
      }

      else if(recorder && _SELF.attr('data-record') == 2){
       Wo_CleanRecordNodes();
       Wo_StopLocalStream();
       _SELF.html('<svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 24 24"><path fill="#ff3a55" d="M12,2A3,3 0 0,1 15,5V11A3,3 0 0,1 12,14A3,3 0 0,1 9,11V5A3,3 0 0,1 12,2M19,11C19,14.53 16.39,17.44 13,17.93V21H11V17.93C7.61,17.44 5,14.53 5,11H7A5,5 0 0,0 12,16A5,5 0 0,0 17,11H19Z"></path></svg>').attr('data-record','0');  
      }

      else{
        return false;
      }
    },500);
    
  });
});

function Wo_IsRecordingBufferClean(){
  return $("[data-record='1']").length == 0; 
}

function Wo_CreateUserMedia(){
  navigator.getUserMedia({audio: true}, Wo_startUserMedia, function(e) {
    console.log('Could not get input or something went wrong: ' + e);
  }); 
}
function Wo_CleanRecordNodes(){
  $(".record-comment-audio").each(function(index, el) {
    $(el).html('<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mic" color="#009da0"><path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"></path><path d="M19 10v2a7 7 0 0 1-14 0v-2"></path><line x1="12" y1="19" x2="12" y2="23"></line><line x1="8" y1="23" x2="16" y2="23"></line></svg>').attr('data-record', '0');
    $('[data-comment-rtime="'+$(el).attr('id')+'"]').text('00:00').addClass('hidden');
  });

  $(".record-chat-audio").each(function(index, el) {
    $(el).html('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 264.02 264.02"><linearGradient x1="19.28%" y1="86.72%" x2="88.05%" y2="12.24%" id="recod_grad"><stop offset="0" stop-color="#cf8283"/><stop offset=".0208" stop-color="#cb7273"/><stop offset=".2931" stop-color="#bb6263"/><stop offset=".5538" stop-color="#b6595a"/><stop offset=".7956" stop-color="#bc5d5e"/><stop offset="1" stop-color="#a84849"/></linearGradient><g> <path fill="url(#recod_grad)" d="M210.506,126.764c-4.143,0-7.5,3.358-7.5,7.5c0,17.302-8.038,34.335-22.052,46.73 c-13.11,11.596-30.349,18.247-47.297,18.247h-3.295c-16.947,0-34.186-6.65-47.296-18.247 c-14.015-12.395-22.052-29.427-22.052-46.73c0-4.142-3.357-7.5-7.5-7.5s-7.5,3.358-7.5,7.5c0,21.598,9.883,42.726,27.114,57.966 c14.314,12.662,32.764,20.413,51.381,21.773v35.017H89.675c-4.143,0-7.5,3.358-7.5,7.5c0,4.142,3.357,7.5,7.5,7.5h84.667 c4.143,0,7.5-3.358,7.5-7.5c0-4.142-3.357-7.5-7.5-7.5H139.51v-35.017c18.617-1.361,37.067-9.112,51.382-21.773 c17.232-15.241,27.114-36.369,27.114-57.966C218.006,130.122,214.648,126.764,210.506,126.764z"/> <path fill="url(#recod_grad)" d="M130.421,184.938h3.18c30.021,0,56.357-24.364,56.357-52.14v-80.66 C189.957,24.364,163.622,0,133.6,0h-3.18c-30.022,0-56.357,24.364-56.357,52.138v80.66 C74.063,160.573,100.398,184.938,130.421,184.938z M89.063,52.138C89.063,32.701,108.776,15,130.421,15h3.18 c21.645,0,41.357,17.701,41.357,37.138v80.66c0,19.438-19.712,37.14-41.357,37.14h-3.18c-21.644,0-41.357-17.702-41.357-37.14 V52.138z"/> </g></svg>').attr('data-record', '0');
    $('[data-chat-rtime="'+$(el).attr('data-chat-tab')+'"]').text('00:00').addClass('hidden');
  });

  recorder    &&         recorder.clear();
  recorder    && clearTimeout(wo_timeout);
  Wo_clearPRecording();
  Wo_clearMRecording();
}

function Wo_ClearTimeout(){
  clearTimeout(wo_timeout);
}
function Wo_ShowRecordingTime(self) {
  var time      = self.text();
  var seconds   = time.split(":");
  var date      = new Date();
  date.setHours(0);
  date.setMinutes(seconds[0]);
  date.setSeconds(seconds[1]);
  var __date    = new Date(date.valueOf() + 1000);
  var temp      = __date.toTimeString().split(" ");
  var timeST    = temp[0].split(":");
  if (timeST[1] >= 10) {
    Wo_ClearTimeout();
    Wo_stopRecording();
  }
  else{
    self.text(timeST[1]+":"+timeST[2]);
    wo_timeout    = setTimeout(Wo_ShowRecordingTime,1000,recording_time)  
  }

}
  var audio_context,recorder,recording_time,wo_timeout,localstream,recording_node,chat_tab,comm_field;
function Wo_startUserMedia(stream) {
  localstream   = stream;
  var input     = audio_context.createMediaStreamSource(stream);
  if (input) {
    recorder    = new Recorder(input,{bufferLen:16384});
  }
  else{
    console.log('Could not initialize media stream');
  }
}

function Wo_startRecording() {
  recorder     &&    recorder.record();
  recording_time.removeClass('hidden');
  recorder     && recorder.exportWAV(function(blob){});
  recorder     && setTimeout(Wo_ShowRecordingTime,1000,recording_time);
  //console.log('recording started');
}

function Wo_stopRecording() {
  recorder     &&          recorder.stop();
  wo_timeout   && clearTimeout(wo_timeout);
  //recorder     && console.log('recording sotopped');
}

function Wo_StopLocalStream(){
  localstream  && localstream.getTracks().forEach(function(track) { track.stop() });
  localstream    = false;
  recording_node = false;
  delete(recorder);
}

function Wo_clearPRecording(){
  recorder       &&                  recorder.clear();
  recording_time &&      recording_time.text('00:00');
  recorder       &&          clearTimeout(wo_timeout);
  recording_time && recording_time.addClass('hidden');
  $("#recordPostAudio").html('<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mic"><path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"></path><path d="M19 10v2a7 7 0 0 1-14 0v-2"></path><line x1="12" y1="19" x2="12" y2="23"></line><line x1="8" y1="23" x2="16" y2="23"></line></svg>').attr('data-record','0');
}

function Wo_clearMRecording(){
  recorder       &&                  recorder.clear();
  recording_time &&      recording_time.text('00:00');
  recorder       &&          clearTimeout(wo_timeout);
  recording_time && recording_time.addClass('hidden');
  $("#messages-record").html('<svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 24 24"><path fill="#ff3a55" d="M12,2A3,3 0 0,1 15,5V11A3,3 0 0,1 12,14A3,3 0 0,1 9,11V5A3,3 0 0,1 12,2M19,11C19,14.53 16.39,17.44 13,17.93V21H11V17.93C7.61,17.44 5,14.53 5,11H7A5,5 0 0,0 12,16A5,5 0 0,0 17,11H19Z"></path></svg>').attr('data-record','0');
}

function Wo_GetPRecordLink() {
  var publisher_button = $('#publisher-button');
  publisher_button.attr('disabled', true);
  publisher_button.addClass('btn-loading');
  if (recorder && recording_node == "post") {
    recorder.exportWAV(function(blob) {
      if (blob instanceof Blob && blob.size > 50) {
        var fileName   = (new Date).toISOString().replace(/:|\./g, '-');
        var file       = new File([blob], 'wo-' + fileName + '.wav', {type: 'audio/wav'});
        var dataForm   = new FormData();
        dataForm.append('audio-filename', file.name);
        dataForm.append('audio-blob', file);
        Wo_RegisterPost(dataForm);
      }
      else{$('form.post').submit()}
    });
  }
  else{$('form.post').submit()}
}

function Wo_GetMRecordLink() {
  if (recorder && recording_node == "msg") {
    recorder.exportWAV(function(blob) {
      if (blob instanceof Blob && blob.size > 50) {
        var fileName   = (new Date).toISOString().replace(/:|\./g, '-');
        var file       = new File([blob], 'AU-' + fileName + '.wav', {type: 'audio/wav'});
        var dataForm   = new FormData();
        dataForm.append('audio-filename', file.name);
        dataForm.append('audio-blob', file);
        Wo_RegisterMessage(dataForm);
      }
      else{$('form.sendMessages').submit()}

    });
  }
  else{$('form.sendMessages').submit()}
}

function Wo_RegisterTabMessage(id,type = '') {

  if (!id) {
    return false;
  }

  if (type == 'page') {
    chat_tab = id;
  }
  
  if (recorder && recording_node == "chat" && id == chat_tab) {
    recorder.exportWAV(function(blob) {
      if (blob instanceof Blob && blob.size > 50) {
        var fileName   = (new Date).toISOString().replace(/:|\./g, '-');
        var file       = new File([blob], 'AU-' + fileName + '.wav', {type: 'audio/wav'});
        var dataForm   = new FormData();
        dataForm.append('audio-filename', file.name);
        dataForm.append('audio-blob', file);
        Wo_RegisterTabMessageRecord(dataForm,id,type);
      }
      else{
        if (type == 'page') {
          $('form.page-chat-sending-'+id).submit();
        }
        else{
          $('form.chat-sending-form-'+id).submit();
        }
      }

    });
  }
  else{
    if (type == 'page') {
      $('form.page-chat-sending-'+id).submit();
    }
    else{
      $('form.chat-sending-form-'+id).submit();
    }
  }
}

function Wo_RegisterTabMessageRecord(dataForm,id,type = ''){
  if (dataForm && id) {
    var form_class  = 'chat-sending-form-';
    if (type == 'page') {
      form_class  = 'page-chat-sending-';
    }
    $('form.'+form_class+id).find('.ball-pulse').fadeIn(100);
    $.ajax({
        url: Wo_Ajax_Requests_File() + "?f=chat&s=register_message_record",
        type:       'POST',
        cache:       false,
        dataType:   'json',
        data:   dataForm,
        processData: false,
        contentType: false,
        xhr: function() {
            var xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener("progress", function(evt) {
                if (evt.lengthComputable) {
                    var percentComplete = (evt.loaded / evt.total) * 100;
                }
           }, false);
           return xhr;
        }
    }).done(function(data) {
      if(data.status == 200){
        $('form.'+form_class+id).find('input.message-record').val('');   
        $('form.'+form_class+id).find('input.media-name').val('');
        $('form.'+form_class+id).find('.ball-pulse').fadeOut(100);;
        Wo_stopRecording();
        Wo_CleanRecordNodes();
        Wo_StopLocalStream();
        $('form.'+form_class+id).find('input.message-record').val(data.url);
        $('form.'+form_class+id).find('input.media-name').val(data.name);
        $('form.'+form_class+id).submit();
      }
    });
  }
}

function Wo_RegisterPost(dataForm){
  if (dataForm) {
    $.ajax({
        url: Wo_Ajax_Requests_File() + "?f=posts&s=register_post_record",
        type:       'POST',
        cache:       false,
        dataType:   'json',
        data:   dataForm,
        processData: false,
        contentType: false,
    }).done(function(data) {
      if(data.status == 200){
        Wo_stopRecording();
        Wo_clearPRecording();
        Wo_StopLocalStream();
        $("#postRecord").val(data.url)
        $('form.post').submit()
      }
    });
  }
}

function Wo_RegisterMessage(dataForm){
  if (dataForm) {
    $.ajax({
        url: Wo_Ajax_Requests_File() + "?f=messages&s=upload_record",
        type:       'POST',
        cache:       false,
        dataType:   'json',
        data:   dataForm,
        processData: false,
        contentType: false,
    }).done(function(data) {
      if(data.status == 200){
        Wo_stopRecording();
        Wo_clearMRecording();
        Wo_StopLocalStream();
        $("#message-record-file").val(data.url);
        $("#message-record-name").val(data.name);
        $('form.sendMessages').submit();
      }
    });
  }
}

function Wo_RegisterComment(text, post_id, user_id, event, page_id, type) {
  if (!text) {
    text = $('[id=post-' + post_id + ']').find('.comment-textarea').val();
  }
  
  
  if(event.keyCode == 13 && event.shiftKey == 0 && recording_node == "comm") {
    Wo_stopRecording(); 
    if (recorder) { 
      recorder.exportWAV(function(blob){
        var comment_src_image = $('#post-' + post_id).find('#comment_src_image');
        var comment_image = '';
        if (comment_src_image.length > 0) {
          comment_image = comment_src_image.val();
        }       
        var dataForm = new FormData();                    
        dataForm.append('post_id',            post_id);
        dataForm.append('text',                  text);
        dataForm.append('user_id',            user_id);
        dataForm.append('page_id',            page_id);
        dataForm.append('comment_image',comment_image);
        if (blob.size > 50) {
          var fileName   = (new Date).toISOString().replace(/:|\./g, '-');
          var file       = new File([blob], 'wo-' + fileName + '.wav', {type: 'audio/wav'});
          dataForm.append('audio-filename', file.name);
          dataForm.append('audio-blob', file);
        }
        Wo_InsertComment(dataForm,post_id);
      });
    }

    else{
        var comment_src_image = $('#post-' + post_id).find('#comment_src_image');
        var comment_image = '';
        if (comment_src_image.length > 0) {
          comment_image = comment_src_image.val();
        }       
        var dataForm = new FormData();                    
        dataForm.append('post_id',            post_id);
        dataForm.append('text',                  text);
        dataForm.append('user_id',            user_id);
        dataForm.append('page_id',            page_id);
        dataForm.append('comment_image',comment_image);
        $('#charsLeft_'+post_id).text($('#charsLeft_'+post_id).attr('data_num')); 
        Wo_InsertComment(dataForm,post_id);
    }
  }
}

function Wo_RegisterComment2(post_id, user_id, page_id, type) {
  text = $('[id=post-' + post_id + ']').find('.comment-textarea').val();
  if(recording_node == "comm") {
    Wo_stopRecording(); 
    if (recorder) { 
      recorder.exportWAV(function(blob){
        var comment_src_image = $('#post-' + post_id).find('#comment_src_image');
        var comment_image = '';
        if (comment_src_image.length > 0) {
          comment_image = comment_src_image.val();
        }       
        var dataForm = new FormData();                    
        dataForm.append('post_id',            post_id);
        dataForm.append('text',                  text);
        dataForm.append('user_id',            user_id);
        dataForm.append('page_id',            page_id);
        dataForm.append('comment_image',comment_image);
        if (blob.size > 50) {
          var fileName   = (new Date).toISOString().replace(/:|\./g, '-');
          var file       = new File([blob], 'wo-' + fileName + '.wav', {type: 'audio/wav'});
          dataForm.append('audio-filename', file.name);
          dataForm.append('audio-blob', file);
        }
        Wo_InsertComment(dataForm,post_id);
      });
    }

    else{
        var comment_src_image = $('#post-' + post_id).find('#comment_src_image');
        var comment_image = '';
        if (comment_src_image.length > 0) {
          comment_image = comment_src_image.val();
        }       
        var dataForm = new FormData();                    
        dataForm.append('post_id',            post_id);
        dataForm.append('text',                  text);
        dataForm.append('user_id',            user_id);
        dataForm.append('page_id',            page_id);
        dataForm.append('comment_image',comment_image);
        $('#charsLeft_'+post_id).text($('#charsLeft_'+post_id).attr('data_num')); 
        Wo_InsertComment(dataForm,post_id);
    }
  }
}

function Wo_InsertComment(dataForm,post_id){
    if (!dataForm) { return false;}
    post_wrapper = $('[id=post-' + post_id + ']');
    comment_textarea = post_wrapper.find('.post-comments');
    comment_btn = comment_textarea.find('.emo-comment');
    textarea_wrapper = comment_textarea.find('.textarea');
    comment_list = post_wrapper.find('.comments-list');   
    //event.preventDefault();
    textarea_wrapper.val('');
	
    post_wrapper.find('#wo_comment_combo .ball-pulse').fadeIn(100);
    $.ajax({
        url: Wo_Ajax_Requests_File() + '?f=posts&s=register_comment&hash=' + $('.main_session').val(),
        type:       'POST',
        cache:       false,
        dataType:   'json',
        data:   dataForm,
        processData: false,
        contentType: false,
    }).done(function(data) {
      $('.wo_comment_combo_' + post_id).removeClass('comment-toggle');
      if(data.status == 200) {
        Wo_CleanRecordNodes();
        post_wrapper.find('.post-footer .comment-container:last-child').after(data.html);
        post_wrapper.find('.comments-list-lightbox .comment-container:first').before(data.html);
        post_wrapper.find('[id=comments]').html(data.comments_num);
        post_wrapper.find('.lightbox-no-comments').remove();
        Wo_StopLocalStream();
      }
      $('#post-'+ post_id).find('.comment-image-con').empty().addClass('hidden');
      $('#post-'+ post_id).find('#comment_src_image').val('');
      post_wrapper.find('#wo_comment_combo .ball-pulse').fadeOut(100);
      if (data.can_send == 1) {
        Wo_SendMessages();
      }
    });
}