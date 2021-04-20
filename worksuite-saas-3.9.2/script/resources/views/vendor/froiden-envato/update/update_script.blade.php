<script type="text/javascript">
    var updateAreaDiv = $('#update-area');
    var refreshPercent = 0;
    var checkInstall = true;

    $('#update-app').click(function () {
        if ($('#update-frame').length) {
            return false;
        }

        @php($envatoUpdateCompanySetting = \Froiden\Envato\Functions\EnvatoUpdate::companySetting())

        @if(!is_null($envatoUpdateCompanySetting->supported_until) && \Carbon\Carbon::parse($envatoUpdateCompanySetting->supported_until)->isPast())
        var supportText = " Your support has been expired on <b><span id='support-date'>{{\Carbon\Carbon::parse($envatoUpdateCompanySetting->supported_until)->format('d M, Y')}}</span></b>";
            swal({
                title: "Support Expired",
                html:true,
                text: supportText + "<br>Please renew your suport for one-click updates.",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#00c292",
                confirmButtonText: "Renew Now",
                cancelButtonText: "Cancel",
                closeOnConfirm: true,
                closeOnCancel: true
            },  function (isConfirm) {
            if (isConfirm) {
                window.open(
                "{{ config('froiden_envato.envato_product_url') }}",
                '_blank' // <- This is what makes it open in a new window.
                );
            }
        });
        @else
        swal({
            title: "Are you sure?",
            text: `<div class='alert alert-danger'>Do not click update now button if the application is customised. Your changes will be lost.\n
                <br>
                Take backup of files and database before updating. \
                <br>
                <strong><i>Author will not be responsible if something goes wrong</i></strong>
                </div>
                <span class="">To confirm if you have read the above message, type <strong><i>confirm</i></strong> in the field.</span>
                `,
            type: "input",
            html:true,
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, update it!",
            cancelButtonText: "No, cancel please!",
            closeOnConfirm: false,
            closeOnCancel: true,
        }, function (isConfirm) {
            
            if (isConfirm == false){
                return false;
            }

            if(isConfirm.toLowerCase() !== "confirm") {
                swal({
                    title: "Text not matched",
                    text: "You have entered wrong spelling on confirm",
                    type: "error",
                });
                return false;
            }  
            if (isConfirm.toLowerCase() === "confirm") {
                updateAreaDiv.removeClass('hide');
                swal.close();
                $.easyAjax({
                    type: 'GET',
                    url: '{!! route("admin.updateVersion.update") !!}',
                    success: function (response) {
                        if(response.status =='success'){
                            downloadScript();
                            downloadPercent();
                        }else{
                            swal({
                                title: response.message,
                                type: "error",
                            });
                        }

                    }
                });
            }
        });
        @endif

    })

    function downloadScript() {
        $.easyAjax({
            type: 'GET',
            url: '{!! route("admin.updateVersion.download") !!}',
            success: function (response) {
                clearInterval(refreshPercent);
                $('#percent-complete').css('width', '100%');
                $('#percent-complete').html('100%');
                $('#download-progress').append("<i><span class='text-success'>Download complete.</span> Now Installing...Please wait (This may take few minutes.)</i>");

                window.setInterval(function () {
                    /// call your function here
                    if (checkInstall == true) {
                        checkIfFileExtracted();
                    }
                }, 1500);

                installScript();

            }
        });
    }

    function getDownloadPercent() {
        $.easyAjax({
            type: 'GET',
            url: '{!! route("admin.updateVersion.downloadPercent") !!}',
            success: function (response) {
                response = response.toFixed(1);
                $('#percent-complete').css('width', response + '%');
                $('#percent-complete').html(response + '%');
            }
        });
    }

    function checkIfFileExtracted() {
        $.easyAjax({
            type: 'GET',
            url: '{!! route("admin.updateVersion.checkIfFileExtracted") !!}',
            success: function (response) {
                checkInstall = false;
                if(response.status == 'success'){
                    window.location.reload();
                }
            }
        });
    }

    function downloadPercent() {
        updateAreaDiv.append('<hr><div id="download-progress">' +
            'Download Progress<br><div class="progress progress-lg">' +
            '<div class="progress-bar progress-bar-success active progress-bar-striped" role="progressbar" id="percent-complete" role="progressbar""></div>' +
            '</div>' +
            '</div>'
        );
        //getting data
        refreshPercent = window.setInterval(function () {
            getDownloadPercent();
            /// call your function here
        }, 1500);
    }

    function installScript() {
        $.easyAjax({
            type: 'GET',
            url: '{!! route("admin.updateVersion.install") !!}',
            success: function (response) {
                if(response.status == 'success'){
                    window.location.reload();
                }
            }
        });
    }

    function getPurchaseData() {
        var token = "{{ csrf_token() }}";
        $.easyAjax({
            type: 'POST',
            url: "{{ route('purchase-verified') }}",
            data: {'_token': token},
            container: "#support-div",
            messagePosition: 'inline',
            success: function (response) {
                window.location.reload();
            }
        });
        return false;
    }
</script>