<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ $global->favicon_url }}">
    {{--<link rel="manifest" href="{{ asset('favicon/manifest.json') }}">--}}
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ $global->favicon_url }}">
    <meta name="theme-color" content="#ffffff">

    <title>{{ $pageTitle }}</title>
    <!-- Bootstrap Core CSS -->
    <link href="{{ asset('bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel='stylesheet prefetch'
          href='https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css'>
    <link rel='stylesheet prefetch'
          href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.2/css/bootstrap-select.min.css'>

    <!-- This is Sidebar menu CSS -->
    <link href="{{ asset('plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.css') }}" rel="stylesheet">

    <link href="{{ asset('plugins/bower_components/toast-master/css/jquery.toast.css') }}"   rel="stylesheet">
    <link href="{{ asset('plugins/bower_components/sweetalert/sweetalert.css') }}"   rel="stylesheet">

    <!-- This is a Animation CSS -->
    <link href="{{ asset('css/animate.css') }}" rel="stylesheet">

@stack('head-script')

<!-- This is a Custom CSS -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <!-- color CSS you can use different color css from css/colors folder -->
    <!-- We have chosen the skin-blue (default.css) for this starter
       page. However, you can choose any other skin from folder css / colors .
       -->
    <link href="{{ asset('css/colors/default.css') }}" id="theme"  rel="stylesheet">
    <link href="{{ asset('plugins/froiden-helper/helper.css') }}"   rel="stylesheet">
    <link href="{{ asset('css/custom-new.css') }}"   rel="stylesheet">
    <link href="{{ asset('css/rounded.css') }}"   rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="https://js.stripe.com/v3/"></script>
    <style>
        .sidebar .notify  {
            margin: 0 !important;
        }
        .sidebar .notify .heartbit {
            top: -23px !important;
            right: -15px !important;
        }
        .sidebar .notify .point {
            top: -13px !important;
        }
        @media (max-width:991px){
            .tablet-margin{margin-top: 35px;}
        }

        .admin-logo {
            max-height: 40px;
        }

        .ribbon {
            top: 12px !important;
            left: 0px;
        }
        .signature-pad {
            position: absolute;
            left: 0;
            top: 0;
            width:100%;
            height: 100%;
            background-color: white;
        }
    </style>
</head>
<body class="fix-sidebar">
<!-- Preloader -->
<div class="preloader">
    <div class="cssload-speeding-wheel"></div>
</div>

<div id="wrapper">

    <!-- Left navbar-header end -->
    <!-- Page Content -->
    <div id="page-wrapper" style="margin-left: 0px !important;">
        <div class="container-fluid">

            <!-- .row -->
            <div class="row">
                {{--<div class="col-md-offset-2 col-md-8 m-t-40 m-b-40">--}}
                {{--<img src="{{ $invoiceSetting->logo_url }}" alt="home" class="admin-logo"/>--}}
                {{--</div>--}}

                <div class="col-md-offset-2 col-md-8 m-t-30 col-md-offset-2">
                    <div class="row m-b-20">
                        <div class="col-xs-12">
                            <a href="{{ route("front.download-proposal", md5($proposal->id)) }}" class="btn btn-default pull-right m-r-10 actionButton"><i class="fa fa-file-pdf-o"></i> @lang('app.download')</a>
                            @if(!$proposal->signature && $proposal->status == 'waiting')
                                <button type="button" id="reject_action" class="btn btn-danger pull-right m-r-10 actionButton" onclick="sign('reject');return false;"><i class="fa fa-times"></i> @lang('app.reject')</button>

                                <button type="button" id="accept_action" class="btn btn-success pull-right m-r-10 actionButton" onclick="sign('accept');return false;"><i class="fa fa-check"></i> @lang('app.accept')</button>
                            @endif
                            <div class="clearfix"></div>
                        </div>

                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="white-box printableArea ribbon-wrapper" style="background: #ffffff !important;">
                                <div class="ribbon-content p-20" id="invoice_container">
                                    @if($proposal->status == 'accepted')
                                        <div class="ribbon ribbon-bookmark ribbon-success">@lang('modules.proposal.accepted')</div>
                                    @elseif($proposal->status == 'waiting')
                                        <div class="ribbon ribbon-bookmark ribbon-warning">@lang('app.pending')</div>
                                    @else
                                        <div class="ribbon ribbon-bookmark ribbon-danger">@lang('app.rejected')</div>
                                    @endif
                                    <h4 class="text-right"><b>@lang('app.proposal')</b> </h4>
                                    <hr>
                                    <div class="row tablet-margin">
                                        <div class="row">
                                            <div class="row">
                                                <div class="col-xs-6 b-r">
                                                    <strong class="clearfix">@lang('app.lead')</strong> <br>
                                                    <span class="text-muted">{{ $proposal->lead->client_name }} </span>
                                                </div>
                                                <div class="col-xs-6">
                                                    <strong class="clearfix">@lang('modules.proposal.validTill')</strong> <br>
                                                    <p class="text-muted">{{ $proposal->valid_till->format($settings->date_format) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive m-t-40" style="clear: both;">
                                                    <table class="table table-hover">
                                                        <thead>
                                                        <tr>
                                                            <th class="text-center">#</th>
                                                            <th>@lang("modules.invoices.item")</th>
                                                            @if($invoiceSetting->hsn_sac_code_show)
                                                                <th >@lang('modules.invoices.hsnSacCode')</th>
                                                            @endif
                                                            <th class="text-right">@lang("modules.invoices.qty")</th>
                                                            <th class="text-right">@lang("modules.invoices.unitPrice")</th>
                                                            <th class="text-right">@lang("modules.invoices.price")</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php $count = 0; ?>
                                                        @foreach($proposal->items as $item)
                                                            <tr>
                                                                <td class="text-center">{{ ++$count }}</td>
                                                                <td>{{ ucfirst($item->item_name) }}
                                                                    @if(!is_null($item->item_summary))
                                                                        <p class="font-12">{{ $item->item_summary }}</p>
                                                                    @endif
                                                                </td>
                                                                @if($invoiceSetting->hsn_sac_code_show)
                                                                    <td>{{ ($item->hsn_sac_code) ?? '--' }}</td>
                                                                @endif
                                                                <td class="text-right">{{ $item->quantity }}</td>
                                                                <td class="text-right"> {!! htmlentities($proposal->currency->currency_symbol)  !!}{{ $item->unit_price }} </td>
                                                                <td class="text-right"> {!! htmlentities($proposal->currency->currency_symbol)  !!}{{ $item->amount }} </td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="pull-right m-t-30 text-right">
                                                    <p>@lang("modules.invoices.subTotal")
                                                        : {!! htmlentities($proposal->currency->currency_symbol)  !!}{{ $proposal->sub_total }}</p>

                                                    @if ($discount > 0)
                                                        <p>@lang("modules.invoices.discount")
                                                            : {!! htmlentities($proposal->currency->currency_symbol)  !!}{{ $discount }} </p>
                                                    @endif
                                                    @foreach($taxes as $key=>$tax)
                                                        <p>{{ strtoupper($key) }}
                                                            : {!! htmlentities($proposal->currency->currency_symbol)  !!}{{ $tax }} </p>
                                                    @endforeach
                                                    <hr>
                                                    <h3><b>@lang("modules.invoices.total")
                                                            :</b> {!! htmlentities($proposal->currency->currency_symbol)  !!}{{ $proposal->total }}
                                                    </h3>
                                                    <hr>
                                                </div>

                                                @if(!is_null($proposal->note))
                                                    <div class="col-md-12">
                                                        <p><strong>@lang('app.note')</strong>: {{ $proposal->note }}</p>
                                                    </div>
                                                @endif
                                                <div class="clearfix"></div>
                                                @if($proposal->signature)
                                                    <hr>
                                                    <div class="col-md-12" style="text-align: right;">
                                                        <h2 class="name" style="margin-bottom: 20px;">@lang('modules.estimates.signature')</h2>
                                                        <img src="{{ $proposal->signature->signature }}" style="width:250px">

                                                        <p>{{ ucwords($proposal->signature->full_name) }}</p>
                                                    </div>
                                                @endif

                                                @if($proposal->client_comment)
                                                    <hr>
                                                    <div class="col-md-12">
                                                        <h4 class="name" style="margin-bottom: 20px;">@lang('app.comment')</h4>
                                                        <p> {{ $proposal->client_comment }} </p>
                                                    </div>
                                                @endif
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>

        </div>
        <!-- /.container-fluid -->

    </div>

    <div class="modal fade bs-modal-md in" id="estimateAccept" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <span class="caption-subject font-red-sunglo bold uppercase" id="modelHeading"></span>
                </div>
                <div class="modal-body">
                    Loading...
                </div>
                <div class="modal-footer">
                    {{--<button type="button" class="btn default" data-dismiss="modal">Close</button>--}}
                    {{--<button type="button" class="btn blue">Accept</button>--}}
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</div>
<!-- jQuery -->
<script src="{{ asset('plugins/bower_components/jquery/dist/jquery.min.js') }}"></script>
<!-- Bootstrap Core JavaScript -->
<script src="{{ asset('bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.2/js/bootstrap-select.min.js'></script>

<!-- Sidebar menu plugin JavaScript -->
<script src="{{ asset('plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js') }}"></script>
<!--Slimscroll JavaScript For custom scroll-->
<script src="{{ asset('js/jquery.slimscroll.js') }}"></script>
<!--Wave Effects -->
<script src="{{ asset('js/waves.js') }}"></script>
<!-- Custom Theme JavaScript -->
<script src="{{ asset('plugins/bower_components/sweetalert/sweetalert.min.js') }}"></script>
<script src="{{ asset('js/custom.min.js') }}"></script>
<script src="{{ asset('js/jasny-bootstrap.js') }}"></script>
<script src="{{ asset('plugins/froiden-helper/helper.js') }}"></script>
<script src="{{ asset('plugins/bower_components/toast-master/js/jquery.toast.js') }}"></script>

{{--sticky note script--}}
<script src="{{ asset('js/cbpFWTabs.js') }}"></script>
<script src="{{ asset('plugins/bower_components/icheck/icheck.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/icheck/icheck.init.js') }}"></script>
<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>


<script>
    $(document).ready(() => {
       @if(!$proposal->signature && $proposal->status == 'waiting')
       @else
       @endif
        let url = location.href.replace(/\/$/, "");

        if (location.hash) {
            const hash = url.split("#");
            $('#myTab a[href="#'+hash[1]+'"]').tab("show");
            url = location.href.replace(/\/#/, "#");
            history.replaceState(null, null, url);
            setTimeout(() => {
                $(window).scrollTop(0);
            }, 400);
        }

        $('a[data-toggle="tab"]').on("click", function() {
            let newUrl;
            const hash = $(this).attr("href");
            if(hash == "#summery") {
                newUrl = url.split("#")[0];
            } else {
                newUrl = url.split("#")[0] + hash;
            }
            // newUrl += "/";
            history.replaceState(null, null, newUrl);
        });
    });

    //Accept proposal
    function sign(type) {
        $('.actionButton').prop("disabled", true);
        var allowSignature = false;
      @if($proposal->signature_approval == 1) allowSignature = true; @endif
      if(allowSignature == true ||  type == 'reject')
      {
          var url = '{{ route('front.proposal-action', md5($proposal->id)) }}?type='+type;
          $.ajaxModal('#estimateAccept', url);

      }else {
            swal({
                title: "@lang('messages.sweetAlertTitle')",
                text: "@lang('messages.confirmation.acceptApproval')",
                dangerMode: true,
                icon: 'warning',
                confirm: {
                    text: "@lang('messages.approveIt')",
                    value: 'confirm',
                    visible: true,
                    className: "success",
                }
            }).then(function (isConfirm) {
                if (isConfirm) {
                    $.easyAjax({
                        url: '{{route('front.proposal-action-post', md5($proposal->id))}}',
                        container: '#invoice_container',
                        type: "POST",
                        data: {
                            type:'accept',
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(data){
                            if(data.status == 'success'){
                                window.location.reload();
                            }
                        }
                    })
                }
            });
        }

    }

</script>

</body>
</html>
