@extends('template.default')

@section ('title') @endsection

@section('content')

    <style>
        /**
         * The CSS shown here will not be introduced in the Quickstart guide, but shows
         * how you can use CSS to style your Element's container.
         */
        .StripeElement {
            box-sizing: border-box !important;
            width: 40% !important;
            height: 40px !important;

            padding: 10px 12px !important;

            border: 1px solid transparent !important;
            border-radius: 4px !important;
            background-color: white !important;

            box-shadow: 0 1px 3px 0 #e6ebf1 !important;
            -webkit-transition: box-shadow 150ms ease !important;
            transition: box-shadow 150ms ease !important;
        }

        .StripeElement--focus {
            box-shadow: 0 1px 3px 0 #cfd7df !important;
        }

        .StripeElement--invalid {
            border-color: #fa755a !important;
        }

        .StripeElement--webkit-autofill {
            background-color: #fefde5 !important;
        }
    </style>


    <script src="https://js.stripe.com/v3/"></script>

    <form action="/api/payment" method="post" id="payment-form">
        <div class="form-row">
            <div id="card-element">
            </div>
            <div id="card-errors" role="alert"></div>
        </div>

        <button>Submit Payment</button>
    </form>


    <script>
        var stripe = Stripe('{{getenv('STRIPE_SECRET_PUBLISH')}}');
        var elements = stripe.elements();
        var style = {
            base: {
                color: '#32325d',
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: 'antialiased',
                fontSize: '16px',
                '::placeholder': {
                    color: '#aab7c4'
                }
            },
            invalid: {
                color: '#fa755a',
                iconColor: '#fa755a'
            }
        };
        var card = elements.create('card', {
            hidePostalCode: true,
            style: style
        });

        card.mount('#card-element');

        card.addEventListener('change', function(event) {
            var displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });

        var form = document.getElementById('payment-form');

        var eventId = document.getElementById('payment-form');

        form.addEventListener('submit', function(event) {
            event.preventDefault();

            stripe.createToken(card).then(function(result) {
                if (result.error) {
                    // Inform the user if there was an error.
                    var errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error.message;
                }
                else {
                    // Send the token to server.
                    $.ajax({
                        method: "POST",
                        url: "/api/payment",
                        data: {
                            stripeToken: result.token.id
                        }
                    })
                    .done(function( lo_json ) {
                        console.log(lo_json);
                    });
                }
            });
        });

    </script>

@endsection