@php
    $months = array(1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec');
@endphp
<!-- Company Overview section START -->
<div id="event-detail">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><i class="fa fa-cash"></i> Choose Payment Method</h4>
    </div>
    <div class="modal-body">
        <div class="card-panel">
            <div class="media wow fadeInUp" data-wow-duration="1s">
                <div class="companyIcon">
                </div>
                <div class="media-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6">
                                <h1>Authorize Payment</h1>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-md-6" style="background: lightgreen; border-radius: 5px; padding: 10px;">
                                <div class="panel panel-primary">
                                    <div class="creditCardForm">
                                        <div class="payment">
                                            <form id="payment-card-info" method="post" action="{{ route('client.authorize.pay-submit') }}">
                                                @csrf
                                                <div class="row">
                                                    <div class="form-group owner col-md-8">
                                                        <label for="owner">Owner</label>
                                                        <input type="text" class="form-control" id="owner" name="owner" value="" required>
                                                        <span id="owner-error" class="error text-red">Please enter owner name</span>
                                                    </div>
                                                    <div class="form-group CVV col-md-4">
                                                        <label for="cvv">CVV</label>
                                                        <input type="number" class="form-control" id="cvv" name="cvv" value="" required>
                                                        <span id="cvv-error" class="error text-red">Please enter cvv</span>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="form-group col-md-8" id="card-number-field">
                                                        <label for="cardNumber">Card Number</label>
                                                        <input type="text" class="form-control" id="cardNumber" name="cardNumber" value="" required>
                                                        <span id="card-error" class="error text-red">Please enter valid card number</span>
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="amount">Amount</label>
                                                        <input type="number" class="form-control" id="amount" name="amount" min="1" value="{{ $invoice->total }}" required>
                                                        <span id="amount-error" class="error text-red">Please enter amount</span>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="form-group col-md-6" id="expiration-date">
                                                        <label>Expiration Date</label><br/>
                                                        <select class="form-control" id="expiration-month" name="expiration-month" style="float: left; width: 100px; margin-right: 10px;">
                                                            @foreach($months as $k=>$v)
                                                                <option value="{{ $k }}" {{ old('expiration-month') == $k ? 'selected' : '' }}>{{ $v }}</option>
                                                            @endforeach
                                                        </select>
                                                        <select class="form-control" id="expiration-year" name="expiration-year"  style="float: left; width: 100px;">

                                                            @for($i = date('Y'); $i <= (date('Y') + 15); $i++)
                                                                <option value="{{ $i }}">{{ $i }}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-6" id="credit_cards" style="margin-top: 22px;">
                                                        <img src="{{ asset('img/visa.jpg') }}" id="visa">
                                                        <img src="{{ asset('img/mastercard.jpg') }}" id="mastercard">
                                                        <img src="{{ asset('img/amex.jpg') }}" id="amex">
                                                    </div>
                                                </div>

                                                <br/>
                                                <div class="form-group" id="pay-now">
                                                    <button type="submit" class="btn btn-success themeButton" id="confirm-purchase">Confirm Payment</button>
                                                </div>
                                            </form>
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
</div>