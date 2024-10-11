@extends('user_template.layouts.template')
@section('main-content')
<h2>Final Step To Place Your Order</h2>
<div class="row">
    <div class="col-8">
        <div class="box_main">
           <h3> Product Will Send At-</h3>
           <p>City/Village- {{ $shipping_address->city_name }}</p>
           <p>Postal Code- {{ $shipping_address->postal_code }}</p>
           <p>Phone Number- {{ $shipping_address->phone_number }}</p>
        </div>
    </div>
    
    <div class="col-4">
        <div class="box_main">
           <h3> Your Final Products</h3>
            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                    </tr>
                    @if($total = 0)

                    @endif
                    @foreach ($cart_itmes as $item)
                        <tr>
                            @php
                                $product_name = App\Models\product::where('id', $item->product_id)->value('product_name');
                            @endphp

                            <td>{{ $product_name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ $item->price }}</td>
                        </tr>
                        @php
                              $total = $total + $item->price;
                           @endphp

                    @endforeach
                    <tr>
                        
                        <td></td>  
                        <td>Total</td>        
                        <td>{{ $total }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <form action="{{route('placeorder')}}" method="POST">
        @csrf
        <input type="submit" value="Place order" class="btn btn-primary mr-3">
    </form>
    <form action="" method="post">
        @csrf
        <input type="submit" value="Cancel order" class="btn btn-danger mr-3">
    </form>
</div>
@endsection