<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            padding: 0px;
        }

        table {
            width: 30%;
        }

        td {
            font-size: 10px;
        }

        .border-bottom {
            border-bottom: 1px solid black;
        }

        .my-5 {
            margin: 10px 0px 10px;
        }

        .d-flex {
            display: flex;
        }

        .justify-content-between {
            justify-content: space-between;
        }

        .text-center {
            text-align: center
        }

        .text-right {
            text-align: right
        }

        .text-left {
            text-align: left
        }

    </style>
</head>
<body>
    @php
        $order = optional($invoice->order);
        $order_items = $order->order_items;

        $total_product = $order_items->count();
        $total_price = $order_items->sum('price');
        $total_quantity = $order_items->sum('quantity');
        $tax = $invoice->tax;
        $total_amount = $total_price + $tax;
    @endphp
    <table>
        <tr>
            <td class="text-center">
                <h3>{{ translate('Invoice', 'ငွေတောင်းခံလွှာ') }}</h3>
            </td>
        </tr>
    </table>
    <table class="my-5 border-bottom">
        <tr class="d-flex justify-content-between">
            <td class="text-left">{{ __('message.invoice_datetime') }}</td>
            <td class="text-right">{{ $invoice->invoice_datetime }}</td>
        </tr>
    </table>
    <table class="my-5 border-bottom">
        <tr class="d-flex justify-content-between">
            <td class="text-left">{{ __('message.invoice_number') }}</td>
            <td class="text-right">{{ $invoice->invoice_number }}</td>
        </tr>
        <tr class="d-flex justify-content-between">
            <td class="text-left">{{ __('message.order_number') }}</td>
            <td class="text-right">{{ $order->order_number }}</td>
        </tr>
    </table>
    <table class="my-5 border-bottom">
        @foreach ($order_items as $key => $order_item)
        <tr class="d-flex justify-content-between">
            <td class="text-left">{{ $key + 1 }} . {{ $order_item->product->name }}</td>
            <td class="text-center">{{ number_format($order_item->quantity) }}</td>
            <td class="text-right">{{ number_format($order_item->price) }} {{ __('message.mmk') }}</td>
        </tr>
        @endforeach
    </table>
    <table class="my-5 border-bottom">
        <tr class="d-flex justify-content-between">
            <td class="text-left">{{ __('message.total_product') }}</td>
            <td class="text-right">{{ number_format($total_product) }}</td>
        </tr>
        <tr class="d-flex justify-content-between">
            <td class="text-left">{{ __('message.total_quantity') }}</td>
            <td class="text-right">{{ number_format($total_quantity) }}</td>
        </tr>
    </table>
    <table class="my-5 border-bottom">
        <tr class="d-flex justify-content-between">
            <td class="text-left">{{ __('message.total_price') }}</td>
            <td class="text-right">{{ number_format($total_price) }} {{ __('message.mmk') }}</td>
        </tr>
        <tr class="d-flex justify-content-between">
            <td class="text-left">{{ __('message.tax') }}</td>
            <td class="text-right">{{ number_format($tax) }} {{ __('message.mmk') }}</td>
        </tr>
    </table>
    <table class="my-5 border-bottom">
        <tr class="d-flex justify-content-between">
            <td class="text-left">{{ __('message.total_amount') }}</td>
            <td class="text-right">{{ number_format($total_amount) }} {{ __('message.mmk') }}</td>
        </tr>
    </table>
    <table class="my-5">
        <tr>
            <td class="text-center" style="font-size: 8px; margin-top: 30px;">{{ translate('Thank you...', 'ကျေးဇူးတင်ပါသည်...') }}</td>
        </tr>
    </table>
</body>
</html>
