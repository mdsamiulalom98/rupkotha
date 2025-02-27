@extends('frontEnd.layouts.master') @section('title', 'Customer Checkout') @push('css')
<link rel="stylesheet" href="{{ asset('public/frontEnd/css/select2.min.css') }}" />
@endpush @section('content')
<section class="chheckout-section">
    @php
        $subtotal = Cart::instance('shopping')->subtotal();
        $subtotal = str_replace(',', '', $subtotal);
        $subtotal = str_replace('.00', '', $subtotal);
        $shipping = Session::get('shipping') ? Session::get('shipping') : 0;
        $coupon = Session::get('coupon_amount') ? Session::get('coupon_amount') : 0;
        $discount = Session::get('discount') ? Session::get('discount') : 0;
        $cart = Cart::instance('shopping')->content();

    @endphp
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-5 cus-order-2">
                <div class="checkout-form-container">

                    <div class="checkout-shipping">
                        <form action="{{ route('customer.ordersave') }}" id="orderSave" method="POST"
                            data-parsley-validate="">
                            @csrf
                            <div class="checkout-card">
                                <div class="checkout-header">
                                    <h6 class="check-position">ক্যাশ অন ডেলিভারিতে অর্ডার করতে আপনার তথ্য দিন</h6>
                                </div>
                                <div class="checkout-body">
                                    <div class="row">
                                        <div class="col-sm-12 ">
                                            <div class="form-group checkout-input-box mb-3">
                                                <label for="name"> নামঃ *</label>
                                                <input type="text" id="name"
                                                    class="form-control @error('name') is-invalid @enderror"
                                                    name="name" value="{{ old('name') }}"
                                                    placeholder="আপনার নাম লিখুন" required />
                                                <i class="fa-solid fa-user"></i>
                                                @error('name')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <!-- col-end -->
                                        <div class="col-sm-12">
                                            <div class="form-group checkout-input-box mb-3">
                                                <label for="phone"> মোবাইলঃ *</label>
                                                <input type="text" minlength="11" maxlength="11" pattern="0[0-9]+"
                                                    title="please enter number only and 0 must first character"
                                                    title="Please enter an 11-digit number." id="phone"
                                                    class="form-control @error('phone') is-invalid @enderror"
                                                    name="phone" value="{{ old('phone') }}"
                                                    placeholder="১১ ডিজিটের মোবাইল নাম্বার লিখুন" required />
                                                <i class="fa-solid fa-phone"></i>
                                                @error('phone')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <!-- col-end -->
                                        <div class="col-sm-12">
                                            <div class="form-group checkout-input-box mb-3">
                                                <label for="address"> ঠিকানাঃ *</label>
                                                <input type="address" id="address"
                                                    class="form-control @error('address') is-invalid @enderror"
                                                    name="address"
                                                    placeholder="আপনার এলাকা থানা ও জেলার নাম লিখুন এখানে"
                                                    value="{{ old('address') }}" required />
                                                <i class="fa-solid fa-map"></i>
                                                @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group mb-3">
                                                <label for="district">
                                                    <i class="fa-solid fa-truck"></i> Districts *</label>
                                                <select type="district" id="district"
                                                    class="form-control form-select district @error('district') is-invalid @enderror"
                                                    name="district" required>
                                                    @if (isset($pathaocities['data']['data']))
                                                        @foreach ($pathaocities['data']['data'] as $key => $city)
                                                            <option value="{{ $city['city_id'] }}">
                                                                {{ $city['city_name'] }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @error('district')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <!-- col-end -->
                                        <div class="form-group mt-3">
                                            <label for="" class="form-label">Upazilla</label>
                                            <select name="thana" id="thana"
                                                class="thana form-select chosen-select form-control  {{ $errors->has('thana') ? ' is-invalid' : '' }}"
                                                value="{{ old('thana') }}" style="width:100%">
                                            </select>
                                            @if ($errors->has('thana'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('thana') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <!-- form group end -->
                                        <!-- col-end -->
                                        <div class="col-sm-12">
                                            <div class="radio_payment">
                                                <label id="payment_method">Payment Method</label>
                                            </div>
                                            <div class="payment-methods">

                                                <div class="form-check p_cash payment_method" data-id="cod">
                                                    <input class="form-check-input" type="radio" name="payment_method"
                                                        id="inlineRadio1" value="Cash On Delivery" checked required />
                                                    <label class="form-check-label" for="inlineRadio1">
                                                        Cash On Delivery
                                                    </label>
                                                </div>
                                                @if ($bkash_gateway)
                                                    <div class="form-check p_bkash payment_method" data-id="bkash">
                                                        <input class="form-check-input" type="radio"
                                                            name="payment_method" id="inlineRadio2" value="bkash"
                                                            required />
                                                        <label class="form-check-label" for="inlineRadio2">
                                                            Bkash
                                                        </label>
                                                    </div>
                                                @endif
                                                @if ($shurjopay_gateway)
                                                    <div class="form-check p_shurjo payment_method" data-id="nagad">
                                                        <input class="form-check-input" type="radio"
                                                            name="payment_method" id="inlineRadio3" value="shurjopay"
                                                            required />
                                                        <label class="form-check-label" for="inlineRadio3">
                                                            Nagad
                                                        </label>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- card end -->
                        </form>
                        <form
                            action="@if (Session::get('coupon_used')) {{ route('customer.coupon_remove') }} @else {{ route('customer.coupon') }} @endif"
                            class="checkout-coupon-form" method="POST">
                            @csrf
                            <div class="coupon">
                                <input type="text" name="coupon_code"
                                    placeholder=" @if (Session::get('coupon_used')) {{ Session::get('coupon_used') }} @else Apply Coupon @endif"
                                    class="border-0 shadow-none form-control" />
                                <input type="submit"
                                    value="@if (Session::get('coupon_used')) remove @else apply @endif "
                                    class="border-0 shadow-none btn btn-theme" />
                            </div>
                        </form>
                    </div>

                    <div class="cart_details ">
                        <div class="checkout-card">
                            <div class="checkout-header">
                                <h5>Order Information</h5>
                            </div>
                            <div class="card-body cartlist">
                                <div class="table-responsive checkout-cart-wrapper">
                                    <table class="cart_table table table-striped text-center mb-0">
                                        <thead>
                                            <tr>
                                                <th style="width: 40%;">Product</th>
                                                <th style="width: 15%;">Quantity</th>
                                                <th style="width: 15%;">Price</th>
                                                <th style="width: 15%;">Total Price</th>
                                                <th style="width: 15%;">Delete</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach (Cart::instance('shopping')->content() as $value)
                                                <tr>
                                                    <td class="text-left checkout-cart-product">
                                                        <div class="checkout-cart-image">
                                                            <img src="{{ asset($value->options->image) }}" />
                                                        </div>
                                                        <div class="checkout-cart-name">
                                                            <p>
                                                                {{ Str::limit($value->name, 20) }}
                                                            </p>
                                                            @if ($value->options->product_size)
                                                                <p>Size: {{ $value->options->product_size }}</p>
                                                            @endif
                                                            @if ($value->options->product_color)
                                                                <p>Color: {{ $value->options->product_color }}</p>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td class="cart_qty">
                                                        <div class="qty-cart vcart-qty">
                                                            <div class="quantity">
                                                                <button class="minus cart_decrement"
                                                                    data-id="{{ $value->rowId }}">-</button>
                                                                <input type="text" value="{{ $value->qty }}"
                                                                    readonly />
                                                                <button class="plus cart_increment"
                                                                    data-id="{{ $value->rowId }}">+</button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><strong class="checkout-product-price">{{ $value->price }}
                                                            TK</strong></td>
                                                    <td><strong class="checkout-product-price">{{ $value->price }}
                                                            TK</strong></td>
                                                    <td>
                                                        <a class="cart_remove" data-id="{{ $value->rowId }}"><i
                                                                class="fas fa-trash text-danger"></i></a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>

                                    </table>
                                </div>
                                <div>
                                    <div class="price-summary-item">
                                        <strong colspan="3" class="text-end ">Sub Total:</strong>
                                        <strong id="net_total">{{ $subtotal }}TK</strong>
                                    </div>
                                    <div class="price-summary-item">
                                        <strong colspan="3" class="text-end ">Delivery Charge:</strong>
                                        <strong id="cart_shipping_cost">{{ $shipping }} TK</strong>
                                    </div>
                                    <div class="price-summary-item">
                                        <strong colspan="3" class="text-end ">Discount:</strong>
                                        <strong id="cart_shipping_cost">{{ $discount + $coupon }} TK</strong>
                                    </div>
                                    <div class="price-summary-item">
                                        <strong colspan="3" class="text-end ">Payable Total:</strong>
                                        <strong id="grand_total">{{ $subtotal + $shipping - ($discount + $coupon) }}
                                            TK</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <button class="order_place send_otp custom-shake" type="submit">Confirm
                                        Order</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- col end -->
        </div>
    </div>
</section>

<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">You must put your OTP <span
                        id="sessionOTP"></span></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="insert-review">
                    <div class="form-group mb-3">
                        <label for="otp">OTP</label>
                        <input type="otp" id="otp" style="border: 1px solid;border-color: #2377c3;"
                            class="form-control " name="otp" id="otp" value="" required>
                    </div>
                    <!-- col-end -->

                    <div class="form-group mb-3">
                        <button class="submit-btn btn btn-success" id="otpSubmit"> Submit </button>
                    </div>
                    <!-- col-end -->
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@push('script')
<script src="{{ asset('public/frontEnd/') }}/js/parsley.min.js"></script>
<script src="{{ asset('public/frontEnd/') }}/js/form-validation.init.js"></script>
<script src="{{ asset('public/frontEnd/') }}/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $(".select2").select2();
        var questionModal = new bootstrap.Modal(document.getElementById('staticBackdrop'));
        questionModal.show();
    });
</script>

<script>
    $("#area").on("change", function() {
        var id = $(this).val();
        $.ajax({
            type: "GET",
            data: {
                id: id
            },
            url: "{{ route('shipping.charge') }}",
            dataType: "html",
            success: function(response) {
                $(".cartlist").html(response);
            },
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $('.district').change(function() {
            var id = $(this).val();
            shipping_charge(id);
            draft_order();
            if (id) {
                $.ajax({
                    type: "GET",
                    url: "{{ url('customer/pathao-city') }}?city_id=" + id,
                    success: function(res) {
                        if (res && res.data && res.data.data) {
                            $(".thana").empty();
                            $(".thana").append('<option value="">Select..</option>');
                            $.each(res.data.data, function(index, zone) {
                                $(".thana").append('<option value="' + zone
                                    .zone_id + '">' + zone.zone_name +
                                    '</option>');
                                $('.thana').trigger("chosen:updated");
                            });
                        } else {
                            $(".district").empty();
                            $(".thana").empty();
                        }
                    }
                });
            } else {
                $(".district").empty();
                $(".thana").empty();
            }
        });
    });

    function shipping_charge(id) {
        $.ajax({
            type: "GET",
            data: {
                id: id
            },
            url: "{{ route('shipping.charge') }}",
            dataType: "html",
            success: function(response) {
                $(".cartlist").html(response);
            },
        });
    }

    function draft_order() {
        var district = $('.district').val();
        var name = $("#name").val();
        var phone = $("#phone").val();
        var address = $("#address").val();
        if (district && name && phone && address) {
            $.ajax({
                type: "GET",
                data: {
                    district,
                    name,
                    phone,
                    address
                },
                url: "{{ route('order.store.draft') }}",
                success: function(data) {
                    if (data) {
                        return data;
                    }
                },
            });
        }
    }
</script>
<script type="text/javascript">
    // $(document).ready(function() {
    //     $('.thana').change(function() {
    //         var id = $(this).val();
    //         if (id) {
    //             $.ajax({
    //                 type: "GET",
    //                 url: "{{ url('admin/pathao-zone') }}?zone_id=" + id,
    //                 success: function(res) {
    //                     if (res && res.data && res.data.data) {
    //                         $(".district").empty();
    //                         $(".district").append('<option value="">Select..</option>');
    //                         $.each(res.data.data, function(index, area) {
    //                             $(".district").append('<option value="' + area
    //                                 .area_id + '">' + area.area_name +
    //                                 '</option>');
    //                             $('.district').trigger("chosen:updated");
    //                         });
    //                     } else {
    //                         $(".district").empty();
    //                     }
    //                 }
    //             });
    //         } else {
    //             $(".district").empty();
    //         }
    //     });
    // });
</script>

<script>
    $("#phone").on("input", function() {
        var code = $(this).val();
        code = code.replace(/\D/g, '');
        $(this).val(code);

        var isValid = false;
        // Check if the input is a number and has exactly 11 digits
        if (/^\d{11}$/.test(code)) {
            // Check if it starts with one of the allowed prefixes
            if (code.startsWith("013") || code.startsWith("014") ||
                code.startsWith("015") || code.startsWith("016") ||
                code.startsWith("017") || code.startsWith("018") ||
                code.startsWith("019")) {
                isValid = true;
            }
        }
        console.log('test: ' + isValid);
        if (isValid) {
            $("#phone").addClass('border-success');
            $("#phone").removeClass('border-danger');
            $(".send_otp").prop('disabled', false);
        } else {
            $("#phone").addClass('border-danger');
            $("#phone").removeClass('border-success');
            $(".send_otp").prop('disabled', true);
        }
    });

    // send_otp
    $(".send_otp").on("click", function(event) {
        event.preventDefault();
        var phone = $('#phone').val();
        var name = $('#name').val();
        var address = $('#address').val();
        var selectedMethod = $('input[name="payment_method"]:checked').val();
        var selectedArea = $('#area').val();
        var isValid = true;

        $(".validation-error").remove();
        if (phone === '' || phone.length !== 11 || !/^(01[3-9]\d{8})$/.test(phone)) {
            isValid = false;
            $('#phone').after(
                '<span class="validation-error" style="color:red;">Please enter a valid 11-digit phone number starting with 013 to 019.</span>'
            );
        }

        // Validate another field (example)
        if (name === '') {
            isValid = false;
            $('#name').after(
                '<span class="validation-error" style="color:red;">This field is required.</span>');

        }

        // Validate another field (example)
        if (address === '') {
            isValid = false;
            $('#address').after(
                '<span class="validation-error" style="color:red;">This field is required.</span>');

        }

        // Validate radio input (product color)
        if (!$('input[name="payment_method"]:checked').length) {
            isValid = false;
            $('input[name="payment_method"]').closest('.payment-methods').after(
                '<span class="validation-error" style="color:red;">Please select a Payment Method.</span>');
        }

        // Validate another field (example)
        if (selectedArea === '') {
            isValid = false;
            $('#area').after(
                '<span class="validation-error" style="color:red;">This field is required.</span>');
        }

        if (phone === '' || name === '' || address === '') {
            $("html, body").animate({
                scrollTop: 0
            }, 800);
        }


        // If any validation fails, re-enable the button and return (stop execution)
        if (!isValid) {
            $(".send_otp").prop('disabled', false);
            return;
        }

        $.ajax({
            type: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                phone: phone
            },
            url: "{{ route('customer.send_otp') }}",
            success: function(response) {
                if (response) {
                    toastr.success('Success', 'OTP Sent successfully');
                    $(".send_otp").addClass('button-clicked');
                    $('#sessionOTP').text(response.otp);
                    var questionModal = new bootstrap.Modal(document.getElementById(
                        'staticBackdrop'));
                    questionModal.show();
                }
            },
        });

    });
</script>
<script>
    $("#otp").on("input", function() {
        var code = $(this).val();
        if (code.length > 3) {
            $("#otp").addClass('border-success');
            $("#otp").removeClass('border-danger');
            $.ajax({
                type: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    code: code
                },
                url: "{{ route('customer.validate_otp') }}",
                success: function(response) {
                    if (response.status == 'success') {
                        $(".order_place").prop('disabled', false);
                        $("#phone").prop('disabled', false);
                        toastr.success('Success', 'OTP Matched successfully');
                    } else {
                        $(".order_place").prop('disabled', true);
                    }
                },
            });
        } else {
            $("#otp").addClass('border-danger');
            $("#otp").removeClass('border-success');
        }
    });
</script>
<script>
    $("#otpSubmit").on("click", function() {
        var code = $('#otp').val();
        if (code.length > 3) {
            $("#otp").addClass('border-success');
            $("#otp").removeClass('border-danger');
            $.ajax({
                type: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    code: code
                },
                url: "{{ route('customer.validate_otp') }}",
                success: function(response) {
                    if (response.status == 'success') {
                        $(".order_place").prop('disabled', false);
                        $("#phone").prop('disabled', false);
                        toastr.success('Success', 'OTP Matched successfully');
                        $("#orderSave").submit();
                    } else {
                        $(".order_place").prop('disabled', true);
                        toastr.error('Sorry', 'OTP did not Matched');
                    }
                },
            });
        } else {
            $("#otp").addClass('border-danger');
            $("#otp").removeClass('border-success');
        }
    });
</script>

<script type="text/javascript">
    dataLayer.push({
        ecommerce: null
    }); // Clear the previous ecommerce object.
    dataLayer.push({
        event: "view_cart",
        ecommerce: {
            items: [
                @foreach (Cart::instance('shopping')->content() as $cartInfo)
                    {
                        item_name: "{{ $cartInfo->name }}",
                        item_id: "{{ $cartInfo->id }}",
                        price: "{{ $cartInfo->price }}",
                        item_brand: "{{ $cartInfo->options->brand }}",
                        item_category: "{{ $cartInfo->options->category }}",
                        item_size: "{{ $cartInfo->options->size }}",
                        item_color: "{{ $cartInfo->options->color }}",
                        currency: "BDT",
                        quantity: {{ $cartInfo->qty ?? 0 }}
                    },
                @endforeach
            ]
        }
    });
</script>
<script type="text/javascript">
    // Clear the previous ecommerce object.
    dataLayer.push({
        ecommerce: null
    });

    // Push the begin_checkout event to dataLayer.
    dataLayer.push({
        event: "begin_checkout",
        ecommerce: {
            items: [
                @foreach (Cart::instance('shopping')->content() as $cartInfo)
                    {
                        item_name: "{{ $cartInfo->name }}",
                        item_id: "{{ $cartInfo->id }}",
                        price: "{{ $cartInfo->price }}",
                        item_brand: "{{ $cartInfo->options->brands }}",
                        item_category: "{{ $cartInfo->options->category }}",
                        item_size: "{{ $cartInfo->options->size }}",
                        item_color: "{{ $cartInfo->options->color }}",
                        currency: "BDT",
                        quantity: {{ $cartInfo->qty ?? 0 }}
                    },
                @endforeach
            ]
        }
    });
</script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endpush
