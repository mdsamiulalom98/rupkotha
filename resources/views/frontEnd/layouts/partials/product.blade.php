<div class="product_item_inner">
    @if ($value->old_price)
        <div class="discount">
            <p>@php $discount=(((($value->old_price)-($value->new_price))*100) / ($value->old_price)) @endphp {{ number_format($discount, 0) }}% </p>
        </div>
    @endif
    <div class="pro_img">
        <a href="{{ route('product', $value->slug) }}">
            <img src="{{ asset($value->image ? $value->image->image : '') }}" alt="{{ $value->name }}" />
        </a>
    </div>
    <div class="pro_des">
        <div class="pro_name">
            <a href="{{ route('product', $value->slug) }}">{{ Str::limit($value->name, 80) }}</a>
        </div>
        <div class="pro_price">
            @if ($value->variable_count > 0 && $value->type == 0)
                <span class="taka-before">
                    {{ $value->variable->new_price }}
                </span>
                @if ($value->variable->old_price)
                    <del class="taka-before"> {{ $value->variable->old_price }}</del>
                @endif
            @else
                <span class="taka-before">
                    {{ $value->new_price }}
                </span>
                @if ($value->old_price)
                    <del class="taka-before">{{ $value->old_price }}</del>
                @endif
            @endif
        </div>
        <div class="pro_btn">
            <div class="cart_btn">
                <a @if ($value->variable_count > 0 && $value->type == 0) href="{{ route('product', $value->slug) }}" @else data-id="{{ $value->id }}" @endif
                    class="addcartbutton">
                    @include('frontEnd.layouts.svg.cart_updated')
                    Add To Cart</a>
            </div>
            <div class="cart_btn">
                <form action="{{ route('cart.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $value->id }}" />
                    <input type="hidden" name="product_color" value="{{ $value->variable->color ?? '' }}">
                    <input type="hidden" name="product_size" value="{{ $value->variable->size ?? '' }}">
                    <button type="submit">
                        @include('frontEnd.layouts.svg.order_now')
                        Order Now</button>
                </form>
            </div>
        </div>
    </div>
</div>
