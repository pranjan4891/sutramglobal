@extends('web.layout.layout', ['pageTitle' => $title])
@section('contant')
<style>
    ul {
  list-style-type: disc; /* default dot */
  margin-left: 20px; /* adds some space */
}

li {
  font-size: 16px; /* custom text size */
  margin-bottom: 8px; /* space between list items */
}

</style>
<section class="blacksection">
    <div class="container">
        <div class="row">
        </div>
    </div>
</section>
<!-- first section start -->
<section class="py-5">
    <div class="container">
    <div class="text-center pb-3 ">
        <h2>Shipping and Delivery Policy</h2>
    </div>
        <div class="row">
            <div class="col-md-12">
                <div class="firstpraragraph">
          
                <p>At Sutramglobal, we aim to ensure your orders are delivered promptly and safely. Below are our guidelines for shipping and delivery:</p>
               </div>
               <div class="firstpraragraph">
                <h4>1. Shipping Methods
</h4>
                 <ul>
                  <li>We offer various shipping options to suit your needs, including standard and expedited shipping. You can select your preferred method at checkout.
</li>

                </ul>
               </div>
               <div class="firstpraragraph2">
                <h4>2. Shipping Costs</h4>
                <ul>
                  <li>Shipping costs are calculated based on the weight of your order and the delivery location. The total shipping fee will be displayed at checkout before you complete your purchase.</li>
                 
                </ul>
               </div>
               <div class="firstpraragraph2">
                <h4>3. Processing Time</h4>
                <ul>
                  <li>Orders are typically processed within 1-3 business days. You will receive a confirmation email once your order is shipped, including tracking information.
</li>
                 
                </ul>
               </div><div class="firstpraragraph2">
                <h4>4. Delivery Time</h4>
                <ul>
                  <li>Delivery times vary based on the shipping method selected and your location. Generally, you can expect your order to arrive within:</li>
                  <li>Standard Shipping: 5-7 business days.</li>
                          <li>Expedited Shipping: 2-3 business days
</li>
                                  <li>Please note that delivery times may be affected by factors beyond our control, such as weather conditions or courier delays.</li>
                </ul>
               </div><div class="firstpraragraph2">
                <h4>5. Tracking Your Order</h4>
                <ul>
                  <li>After your order has been shipped, you will receive a tracking number via email. You can use this number to monitor the status of your shipment online.</li>
                </ul>
               </div><div class="firstpraragraph2">
                <h4>6. Undeliverable Packages</h4>
                <ul>
                  <li>If a package is returned to us due to an incorrect address or failure to claim, we will notify you. You will be responsible for the reshipping cost to send the order again.</li>
                </ul>
               </div><div class="firstpraragraph2">
                <h4>7. International Shipping</h4>
                <ul>
                  <li>We currently ship within India. For international orders, please contact our customer support team for assistance.
</li>
                </ul>
               </div>
               <div class="firstpraragraph2">
                <h4>8. Contact Information</h4>
                <ul>
                  <li>If you have any questions or need assistance regarding shipping and delivery, please reach out to us:</li>
                   <li>Phone Number: +91 99990 39094</li>
                    <li>Email: info@sutramglobal.com</li>
                     <li>Address: 3rd Floor Orchid Center, Golf Course Road, Sector 53, Gurugram, Haryana - 122002.</li>
                </ul>
               </div>
           
              
            </div>
        </div>
    </div>
</section>
@endsection
@push('sub-script')



@endpush

