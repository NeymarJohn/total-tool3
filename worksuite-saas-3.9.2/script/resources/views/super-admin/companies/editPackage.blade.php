<div class="row">
    <div class="col-xs-12">
        <div class="panel panel-inverse">
            <div class="panel-wrapper collapse in" aria-expanded="true">
                <div class="panel-body">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="packageName">Package</label>
                                    <select name="package" id="packageName" class="form-control select2">
                                        @foreach($packages as $package)
                                            <option value="{{ $package->id }}"
                                                    @if($company->package_id == $package->id) selected @endif >{{ $package->name ?? '' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="packageType">Package Type</label>
                                    <select name="packageType" id="packageType" class="form-control select2">
                                        <option value="annual" @if($company->package_type == 'annual') selected @endif >
                                            Annual
                                        </option>
                                        <option value="monthly"
                                                @if($company->package_type == 'monthly') selected @endif >Monthly
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="packageAmount">Amount</label>
                                    <input type="text" name="amount" id="packageAmount" class="form-control" value="{{ !empty($currentPackage) ? $currentPackage->{$company->package_type . '_price'} : ''}}">
                                </div>
                            </div>

                            <!--/span-->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Pay Date</label>
                                    <input type="text" name="pay_date" id="pay_date" class="form-control" value="{{ $lastInvoice && $lastInvoice->pay_date ? $lastInvoice->pay_date->format('m/d/Y') : ''}}">
                                </div>
                            </div>

                            <!--/span-->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Next Pay Date</label>
                                    <input type="text" name="next_pay_date" id="next_pay_date" class="form-control" value="{{ $lastInvoice && $lastInvoice->next_pay_date ? $lastInvoice->next_pay_date->format('m/d/Y') : ''}}">
                                </div>
                            </div>

                            <!--/span-->
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label class="control-label">@lang('modules.package.licenseExpiresOn')</label>
                                    <input type="text" name="licence_expires_on" id="licence_expires_on" class="form-control" value="{{ $company->licence_expire_on ? $company->licence_expire_on->format('m/d/Y') : ''}}" disabled="disabled">
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label class="control-label">Choose Payment Method
                                        <a href="javascript:;"
                                           id="createPaymentMethod"
                                           onclick="offlinePaymentMethod();return false;"
                                           class="btn btn-sm btn-outline btn-success">
                                            <i class="fa fa-plus"></i> @lang('modules.offlinePayment.method')
                                        </a>
                                    </label>
                                    <select id="multiselect" name="payment_method" class="selectpicker form-control type">
                                        @foreach($offlinePaymentMethod as $method)
                                            <option value="{{ $method->id }}">{{ $method->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var packageInfo = @json($packageInfo)

    $("#pay_date").datepicker({
        todayHighlight: true,
        autoclose: true,
        weekStart:'{{ $global->week_start }}',
    }).on('change', function () {
        updateExpiryDate();
    });
    $('.selectpicker').selectpicker();
    $("#next_pay_date").datepicker({
        todayHighlight: true,
        autoclose: true,
        weekStart:'{{ $global->week_start }}',
    });

    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });

    function offlinePaymentMethod() {
        var url = '{{ route('super-admin.offline-payment-method.create')}}';
        $.ajaxModal('#offlineMethod', url);
    }


    $('#packageType').off().on('change', function () {
        $('#packageAmount').val(packageInfo[$('#packageName').val()][$(this).val()]);
        updateExpiryDate();
    });

    $('#packageName').off().on('change', function () {
        $('#packageAmount').val(packageInfo[$(this).val()][$('#packageType').val()]);
    });

    function updateExpiryDate() {
        let startDate = moment($("#pay_date").val(), "MM-DD-YYYY");
        let endDate = startDate.add(1, ($('#packageType').val() === 'monthly') ? 'months' : 'year').format("MM/DD/YYYY");
        $('#licence_expires_on').val(endDate);
    }

    $('#update-company-form').off().submit(function (e) {
        e.preventDefault();
        const PackageName = $('#packageName').val();
        const PackageType = $('#PackageType').val();
        $.easyAjax({
            url: '{{route('super-admin.companies.edit-package.post', [$company->id])}}',
            container: '#update-company-form',
            type: "POST",
            redirect: false,
            data: $(this).serialize(),
            success: function () {
                $('#packageUpdateModal').modal('hide');
                window.location.reload();
            }
        });
    });
</script>
