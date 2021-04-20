<style>
    .input-group[class*=col-] {
        padding-right: 7px !important;
        padding-left: 8px !important;
    }

</style>
<div class="flex flex-wrap mb-6">
    <label for="card-element" class="block text-gray-700 text-sm font-bold mb-2">
        Card Info
    </label>
    <div id="card-element" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></div>
    {{--<div id="card-errors" class="text-red-400 text-bold mt-2 text-sm font-medium"></div>--}}
    <div id="card-error" class="text-red-400 text-bold mt-2 text-sm font-medium"></div>
</div>

<!-- Stripe Elements Placeholder -->
<div class="flex flex-wrap mt-6" style="margin-top: 15px; text-align: center">
    <button type="submit" id="card-button" data-secret="{{ $intent->client_secret }}"  class="btn btn-success inline-block align-middle text-center select-none border font-bold whitespace-no-wrap py-2 px-4 rounded text-base leading-normal no-underline text-gray-100 bg-blue-500 hover:bg-blue-700">
        <i class="fa fa-cc-stripe"></i> {{ __('Pay') }}
    </button>
</div>
<script>
    @if($credentials->stripe_status == 'active')
    // A reference to Stripe.js initialized with your real test publishable API key.
    var stripe = Stripe('{{ $credentials->stripe_client_id }}');
    var clientDetails  = {!! json_encode($customerDetail) !!};

   console.log(['clientDetails', clientDetails, clientDetails.email]);
    // Disable the button until we have Stripe set up on the page
    //        document.querySelector("button#card-button").disabled = true;
    const cardButton = document.getElementById('card-button');
    cardButton.disabled = true;
    var elements = stripe.elements();

    var style = {
        base: {
            color: "#32325d",
            fontFamily: 'Arial, sans-serif',
            fontSmoothing: "antialiased",
            fontSize: "16px",
            "::placeholder": {
                color: "#32325d"
            }
        },
        invalid: {
            fontFamily: 'Arial, sans-serif',
            color: "#fa755a",
            iconColor: "#fa755a"
        }
    };

    var card = elements.create("card", { style: style });
    // Stripe injects an iframe into the DOM
    card.mount("#card-element");

    card.on("change", function (event) {
        // Disable the Pay button if there are no card details in the Element
        cardButton.disabled = event.empty;
        document.querySelector("#card-error").textContent = event.error ? event.error.message : "";
    });

    var form = document.getElementById("stripeAddress");
    form.addEventListener("submit", function(event) {
        event.preventDefault();
        // Complete payment when the submit button is clicked
        payWithCard(stripe, card, '{{ $intent->client_secret }}');
    });

    // Calls stripe.confirmCardPayment
    // If the card requires authentication Stripe shows a pop-up modal to
    // prompt the user to enter authentication details without leaving your page.
    var payWithCard = function(stripe, card, clientSecret) {
        loading(true);
        stripe
            .confirmCardPayment(clientSecret, {
                payment_method: {
                    card: card,
                    billing_details: {
                        name: clientDetails.name,
                        email: clientDetails.email,
                        address: {
                            line1: clientDetails.line1,
                            city: clientDetails.city,
                            state: clientDetails.state,
                            country: clientDetails.country
                        }
                    }
                }
            })
            .then(function(result) {
                if (result.error) {
                    console.log('result.error', result.error);
                    // Show error to your customer
                    showError(result.error.message);
                } else {
                    console.log(result);

                    // The payment succeeded!
                    orderComplete(result.paymentIntent.id);

                }
            });
    };

    /* ------- UI helpers ------- */

    // Shows a success message when the payment is complete
    var orderComplete = function(paymentIntentId) {
        loading(false);
        cardButton.disabled = true;
        $.easyAjax({
            url: '{{route('client.stripe', [$invoice->id])}}',
            container: '#invoice_container',
            type: "POST",
            redirect: true,
            data: {token: paymentIntentId, "_token" : "{{ csrf_token() }}" },
        })
    };

    // Show the customer the error from Stripe if their card fails to charge
    var showError = function(errorMsgText) {
        loading(false);
        var errorMsg = document.querySelector("#card-error");
        errorMsg.textContent = errorMsgText;
        setTimeout(function() {
            errorMsg.textContent = "";
        }, 4000);
    };

    // Show a spinner on payment submission
    var loading = function(isLoading) {
        if (isLoading) {
            // Disable the button and show a spinner
            cardButton.disabled = true;
        } else {
            cardButton.disabled = false;
        }
    };
    @endif

</script>

