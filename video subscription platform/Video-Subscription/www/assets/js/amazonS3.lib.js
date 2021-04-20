(function(window){
  function amazonS3(){
    var _amazonS3Object = {};
    var config = {
        upload_path:['videos'],
        allowed_types: ['jpeg', 'jpg', 'png', 'gif', 'bmp', 'pdf', 'doc', 'docs', 'zip'],
        max_size:5120,
        make_unique_filename:true,
        s3_key_field:"key",
        progress_loader_div:"progress-bar-area",
        uploaded_files_field_id:"uploaded"
    };
    _amazonS3Object.setConfig = function(localConfig){
        for(var i in localConfig){
            if(i){
              config[i] = localConfig[i];
            }      
        }
        return config;
   };
    _amazonS3Object.doFileValidation = function(fileField){
        var fileInput = $(fileField);
        var fileSize = $(fileField)[0].files[0].size;
        if ($.inArray($(fileField).val().split('.').pop().toLowerCase(), config.allowed_types) == -1) {
            alert("Please upload file type:" +" "+config.allowed_types.join(', ')+" "+"only");
            fileInput.val(null);
            return false;
        }
        var finalSize = parseFloat(fileSize/1024).toFixed(2);
        if(finalSize >= config.max_size) // in MB
        {
            alert("Please upload file size less than "+parseFloat(config.max_size/1024).toFixed(2)+" MB");
            fileInput.val(null);
            return false;
        }
        return true;
    };
    _amazonS3Object.s3Upload = function(uploadFieldFormClass){
        var form = $('.'+uploadFieldFormClass);
        var filesUploaded = [];
        var folders = config.upload_path;
        form.fileupload({
            url: form.attr('action'),
            type: form.attr('method'),
            datatype: 'xml',
            add: function (event, data) {
                window.onbeforeunload = function () {
                    return 'You have unsaved changes.';
                };
                var file = data.files[0];
                if(config.make_unique_filename){
                    var filename = Date.now() + '.' + file.name.split('.').pop();
                } else {
                    var filename = file.name;
                }                
                form.find('input[name="'+config.s3_key_field+'"]').val((folders.length ? folders.join('/') + '/' : '') + filename);
                data.submit();
                var bar = $('<div class="progress" data-mod="'+file.size+'"><div class="bar"></div></div>');
                $('.'+config.progress_loader_div).append(bar);
                bar.slideDown('fast');
            },
            progress: function (e, data) {
                var percent = Math.round((data.loaded / data.total) * 100);
                $('.progress[data-mod="'+data.files[0].size+'"] .bar').css('width', percent + '%').html(percent+'%');
            },
            fail: function (e, data) {
                window.onbeforeunload = null;
                $('.progress[data-mod="'+data.files[0].size+'"] .bar').css('width', '100%').addClass('red').html('');
            },
            done: function (event, data) {
                window.onbeforeunload = null;
                var original = data.files[0];
                var s3Result = data.result.documentElement.children;                
                filesUploaded.push({
                    "original_name": original.name,
                    "s3_name": s3Result[2].innerHTML,
                    "size": original.size,
                    "url": s3Result[0].innerHTML
                });
                $('#'+config.uploaded_files_field_id).html(JSON.stringify(filesUploaded, null, 2));
                // $('#uploaded_files').submit();
            }
        });
        return config.mute;
    };
    return _amazonS3Object;
  }
  if(typeof(window.amazonS3) === 'undefined'){
    window.amazonS3 = amazonS3();
  }
})(window);
