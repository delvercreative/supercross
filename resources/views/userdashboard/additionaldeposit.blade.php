@extends('layouts.app')
@section('extra-styles')

<script src="https://js.stripe.com/v3/"></script>
    
@endsection
@section('content')
<div class="container">
    @if (session()->has('success_message'))
    <div class="spacer"></div>
    <div class="alert alert-success">
        {{ session()->get('success_message') }}
    </div>
@endif

@if(count($errors) > 0)
    <div class="spacer"></div>
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{!! $error !!}</li>
            @endforeach
        </ul>
    </div>
@endif
<form action="{{ route('payment.deposit') }}" method="POST" id="payment-form">
      @csrf
      <h4 class="mb-0">Select Amount</h4>
      <div id="depositAmountOptions">
          <div class="deposit-amount-wrap">
            <div class="deposit-amount" id="da10">
                <input type="radio" id="deposit10" name="amount" class="deposit-input" value="10">
                <label class="deposit-input-label" for="deposit10">$10</label>
            </div>
          </div>
          <div class="deposit-amount-wrap">
            <div class="deposit-amount" id="da25">
              <input type="radio" id="deposit25" name="amount" class="deposit-input" value="25">
              <label class="deposit-input-label" for="deposit25">$25</label>
          </div>
        </div>
          <div class="deposit-amount-wrap">
            <div class="deposit-amount" id="da40">
                <input type="radio" id="deposit40" name="amount" class="deposit-input" value="40">
                <label class="deposit-input-label" for="deposit40">$40</label>
            </div>
          </div>
          <div class="deposit-amount-wrap">
            <div class="deposit-amount" id="da100">
                <input type="radio" id="deposit100" name="amount" class="deposit-input" value="100">
                <label class="deposit-input-label" for="deposit100">$100</label>
            </div>
          </div>
        </div>

      <input type="hidden" name="email" value="{{$email}}">
    
      {{-- <input type="hidden" name="isCustomer" id="isCustomer" value="true"> --}}
    @if(!$user->stripe_id && !$user->card)
      @include('userdashboard.newdeposit')
    @endif
      <div class="form-row">
        <div id="card-element">
          <!-- A Stripe Element will be inserted here. -->
        </div>
        <!-- Used to display form errors. -->
        <div id="card-errors" role="alert"></div>
      </div>
    
      <button type="submit" class="btn btn-green">Submit Payment</button>
    </form>

</div>

@endsection

@section('extra-js')
<script>
   
    let selections = document.querySelectorAll('.deposit-input');
    selections.forEach(selection => {
      selection.addEventListener('input', function(e){
        let val = e.target.value;
        amountNotActive();
        document.querySelector('#da'+val).classList.add('selected');

      });
    });

    function amountNotActive(){
      let notSelected = document.querySelectorAll('.deposit-amount');
      notSelected.forEach(ns => {
        ns.classList.remove('selected');
      })
    }

    


 

    // Create a Stripe client.
    var stripe = Stripe('{{ config('services.stripe.key') }}');
    
    // Create an instance of Elements.
    var elements = stripe.elements();
    
    // Custom styling can be passed to options when creating an Element.
    // (Note that this demo uses a wider set of styles than the guide below.)
    var style = {
      base: {
        color: '#32325d',
        lineHeight: '18px',
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
    
    // Create an instance of the card Element
    var card = elements.create('card', {
      style: style,
      hidePostalCode: true
    });
    
    // Add an instance of the card Element into the `card-element` <div>
    card.mount('#card-element');
    
    // Handle real-time validation errors from the card Element.
    card.addEventListener('change', function(event) {
      var displayError = document.getElementById('card-errors');
      if (event.error) {
        displayError.textContent = event.error.message;
      } else {
        displayError.textContent = '';
      }
    });
    
    // Handle form submission
    var form = document.getElementById('payment-form');
    form.addEventListener('submit', function(event) {
      event.preventDefault();

      var options = {
        name: document.getElementById('name_on_card').value,
        address_line1: document.getElementById('address').value,
        address_line2: document.getElementById('address2').value,
        address_city: document.getElementById('city').value,
        address_state: document.getElementById('state').value,
        address_zip: document.getElementById('zip').value
      }

      let userCard = "{{$user->card}}";
        if(userCard){
          stripe.createToken(card, options).then(function(result) {
            if (result.error) {
              // Inform the user if there was an error
              var errorElement = document.getElementById('card-errors');
              errorElement.textContent = result.error.message;
            } else {
              // Send the token to your server.
              stripeTokenHandler(result.token);
            
            }
          });
        }
        else{
          stripe.createToken(card).then(function(result) {
            if (result.error) {
              // Inform the user if there was an error
              var errorElement = document.getElementById('card-errors');
              errorElement.textContent = result.error.message;
            } else {
              // Send the token to your server.
              stripeTokenHandler(result.token);
            
            }
          });
        }
    
      
    });
    
    // Submit the form with the token ID.
    function stripeTokenHandler(token) {
      // Insert the token ID into the form so it gets submitted to the server
      var form = document.getElementById('payment-form');
      var hiddenInput = document.createElement('input');
      hiddenInput.setAttribute('type', 'hidden');
      hiddenInput.setAttribute('name', 'stripeToken');
      hiddenInput.setAttribute('value', token.id);
      form.appendChild(hiddenInput);
      console.log(token.id);
    
      // Submit the form
      
      form.submit();
    };
    
    
        </script>    
@endsection