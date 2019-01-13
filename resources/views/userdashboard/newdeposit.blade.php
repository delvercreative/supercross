<hr class="mb-4">
    <h4 class="mb-3">Billing address</h4>
        <div class="mb-3">
  
          <input type="text" class="form-control" id="name_on_card" placeholder="Name" value="" required>
          <div class="invalid-feedback">
            Valid name is required.
          </div>
        </div>
     
    <div class="row">
      <div class="col-md-6 mb-3">
        <input type="text" class="form-control" id="address" placeholder="Address" required>
        <div class="invalid-feedback">
          Please enter your shipping address.
        </div>
      </div>
      <div class="col-md-6 mb-3">
        <input type="text" class="form-control" id="address2" placeholder="Apartment or suite (optional)">
      </div>
    </div>

      <div class="row">
        <div class="col-md-6 mb-3">
            <input type="text" class="form-control" id="city" placeholder="City" required>
          <div class="invalid-feedback">
            City is required
          </div>
        </div>
        <div class="col-md-6 mb-3">
            <input type="text" class="form-control" id="state" placeholder="State" required>
          <div class="invalid-feedback">
            State is required
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <input type="text" class="form-control" id="zip" placeholder="Zip Code" required>
          <div class="invalid-feedback">
            Zip code required.
          </div>
        </div>
        <div class="col-md-6 mb-3">
            <select class="custom-select d-block w-100" id="country" required>
              <option>US</option>
            </select>
            <div class="invalid-feedback">
              Please select a valid country.
            </div>
          </div>
      </div>
      <hr class="mb-4">
      <h4 class="mb-3">Payment</h4>
