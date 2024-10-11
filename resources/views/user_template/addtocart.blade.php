@extends('user_template.layouts.template')
@section('main-content')
<h2>Add To Cart</h2>
@if(session()->has('massege')) 
    <div class="alert alert-success">
        {{ session()->get('massege') }}
    </div>
@endif
<div class="row">
    <div class="col-12">
        <div class="box-main">
            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <th>Product Name</th>
                        <th>Product Image</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                    @php
                        $total = 0;
                    @endphp
                    @foreach ($cart_itmes as $item)
                        <tr>
                            @php
                                $product_name = App\Models\product::where('id', $item->product_id)->value('product_name');
                                $img = App\Models\product::where('id', $item->product_id)->value('product_img');
                            @endphp
                            <td>{{ $product_name }}</td>
                            <td><img src="{{ asset($img) }}" style="height: 50px" alt=""></td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ $item->price }}</td>
                            <td>
                                <a class="btn btn-warning" href="{{route('removecartitem', $item->id)}}">Remove</a>
                            </td>
                        </tr>
                        @php
                            $total = $total + $item->price;
                        @endphp
                    @endforeach
                    <tr>
                        @if($total >0)  
                        <td></td>
                        <td></td>
                        <td>Total</td>        
                        <td>{{ $total }}</td>  
                        <td>    
                            <a class="btn btn-primary" href="{{ 'shipping-address' }}">Chackout Now</a>    
                        </td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
@endsection