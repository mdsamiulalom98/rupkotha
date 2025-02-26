@php
    $subtotal = Cart::instance('shopping')->subtotal();
    $subtotal = str_replace(',', '', $subtotal);
    $subtotal = str_replace('.00', '', $subtotal);
    $shipping = Session::get('shipping') ? Session::get('shipping') : 0;
    $coupon = Session::get('coupon_amount') ? Session::get('coupon_amount') : 0;
    $discount = Session::get('discount') ? Session::get('discount') : 0;
@endphp
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
        <strong
            id="grand_total">{{ $subtotal + $shipping - ($discount + $coupon) }} TK</strong>
    </div>
</div>
