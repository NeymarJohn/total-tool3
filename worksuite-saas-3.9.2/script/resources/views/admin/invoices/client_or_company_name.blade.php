@if($projectID == '')
    <select class="form-control select2" name="client_id" id="client_company_id" data-style="form-control">
        @foreach($clients as $client)
            <option value="{{ $client->id }}">{{ ucwords($client->name) }}
                @if($client->company_name != '') {{ '('.$client->company_name.')' }} @endif</option>
        @endforeach
    </select>

    {{-- <script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script> --}}
    <script>
        $("#client_company_id").select2({
            formatNoMatches: function () {
                return "{{ __('messages.noRecordFound') }}";
            }
        });

//        setClient();

        $('#client_company_id').change(function() {
            checkShippingAddress();
        });
    </script>
@else
    <div class="input-icon">
        <input type="text" readonly class="form-control" name="company_name" id="company_name" value="{{ $companyName }}">
    </div>
    <input type="hidden" class="form-control" name="client_id" id="client_id" value="{{ $clientId }}">
@endif
