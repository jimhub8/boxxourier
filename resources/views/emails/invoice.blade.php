<!doctype html>
<html lang="en">
<link rel="icon" type="image/png" href="/storage/icons/favicon.png" />
<link href="{{ asset('css/app.css') }}" rel="stylesheet">

<head>
    <meta charset="UTF-8">
    <title>Invoice - #123</title>

    <style type="text/css">
        @page {
            margin: 0px;
        }

        body {
            margin: 0px;
        }

        * {
            font-family: Verdana, Arial, sans-serif;
        }

        a {
            color: #fff;
            text-decoration: none;
        }

        table {
            font-size: x-small;
        }

        tfoot tr td {
            font-weight: bold;
            font-size: x-small;
        }

        .invoice table {
            margin: 15px;
        }

        .invoice h3 {
            margin-left: 15px;
        }

        .information,
        .footer {
            background-color: #043376;
            color: #FFF;
        }

        .footer .logo {
            margin: 5px;
        }

        .footer table {
            padding: 10px;
        }

        .line {
            color: red;
            height: 10px;
            width: 100%;
        }

        .information {
            margin-left: -200px !important;
        }

        .information .pre pre {
            text-align: left;
            color: #fff;
        }

        .information .companyInfo {
            margin-left: -80px !important;
            color: #fff;
        }

        .information .companyInfo pre {
            color: #fff;
        }
    </style>

</head>

<body class="container">
    <div class="information">
        <table class="table table-striped" style="width: 90%">
            <tr>
                <td align="left" class="pre">
                    @foreach ($shipment as $ships)
                    <pre style="word-break: break-all; margin-left: 200px">
                        Bill to:  {{ $ships->client_name }}
                        Address:  {{ $ships->client_address }}
                        <br /><br />
                        Date:  {{ $ships->created_at }}
                        {{-- Payment id:  {{ $ships->payment_id }} --}}
                        Status:  {{ $ships->status }}
                    </pre> @endforeach
                </td>
                <td align="left" style="width: 30%;" class="companyInfo">
                    <pre>
                        Boxleo & Fulfillment Services | Leading Fulfillment Company in East Africa
                        <br>
                        {{ env('APP_URL') }}
                        Nairobi, Kenya
                </pre>
                </td>
            </tr>

        </table>
    </div>

    <br/>

    <div class="invoice">
        <h3>Products</h3>
        <table width="100%" class="table table-striped table-bordered">
            <thead>
                <tr style="border: 1px solid #f00;" <th>Product Name</th>
                    <th>Order No</th>
                    <th>Quantity</th>
                    <th>Weight</th>
                    <th>Price</th>
                </tr>
            </thead>
            @foreach ($shipment as $ships)
            @if (!$ships->products->isEmpty())
            @foreach ($ships->products as $product)
            <tbody>
                <tr>
                    <td>{{ $product->product_name }}</td>
                    <td>{{ $product->price }}</td>
                    <td>{{ $product->weight }}</td>
                    <td>{{ $product->quantity }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td></td>
                    <td align="left"><b>Total</b></td>
                    <td align="right" class="gray"><b>KSH {{ $product->total }}</b></td>
                </tr>
            </tfoot>
            @endforeach
            @else
            <tbody>
                <tr>
                        {{-- {{ $shipment }} --}}
                    <td>{{ $ships->bar_code }}</td>
                    <td>{{ $ships->weight }}</td>
                    <td>{{ $ships->qty }}</td>
                    <td>{{ $ships->cod_amount }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td></td>
                    <td></td>
                    <td align="left"><b>Total</b></td>
                    <td align="right" class="gray"><b>KSH {{ $ships->cod_amount }}</b></td>
                </tr>
            </tfoot>
            @endif
            @endforeach
        </table>
    </div>
    <div class="line"></div>


    <div class="footer" style="position: absolute; bottom: 0;">
        <table width="100%">
            <tr>
                <td align="left" style="width: 50%;">
                    &copy; {{ date('Y') }} {{ env('APP_URL') }} - All rights reserved.
                </td>
                <td align="right" style="width: 50%;">
                    <a href="{{ env('APP_URL') }}">{{ env('APP_NAME') }}</a>
                </td>
            </tr>

        </table>
    </div>
</body>

</html>
