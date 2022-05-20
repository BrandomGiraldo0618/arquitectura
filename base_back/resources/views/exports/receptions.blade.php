<table>
    <thead>
        @foreach($receptions as $reception)
            <tr style="background-color: yellow">
                <td>{{$reception->name}}</td>
            </tr>
        @endforeach
    </thead>
</table>
<table>
    <thead>
    <tr>
        <td><b>NIT</b></td>
        <td><b>PROVEEDOR</b></td>
        <td><b>FACTURA</b></td>
        <td><b>VALOR USD</b></td>
        <td><b>TRM</b></td>
        <td><b>TOTAL PESOS</b></td>
    </tr>
    </thead>
    <tbody>
    @foreach($invoices as $invoice)
        <tr>
            <td>{{$invoice->document}}</td>
            <td>{{$invoice->name}}</td>
            <td>{{$invoice->number}}</td>
            <td>{{$invoice->value_usd}}</td>
            <td>{{$invoice->trm}}</td>
            <td>{{$invoice->total_cop}}</td>
        </tr>
    @endforeach
    @foreach($expenses as $expense)
        <tr>
            <td></td>
            <td>{{$expense->category_name}} {{$expense->concept_name}}</td>
            <td>{{$expense->number}}</td>
            <td>{{$expense->value_usd}}</td>
            <td>{{$expense->trm}}</td>
            <td>{{$expense->total_cop}}</td>
        </tr>
    @endforeach
    <tr>
        <td></td>
        <td></td>
        <td><b>VALOR IMP</b></td>
        <td>{{$factor}}</td>
        <td></td>
        <td>{{$total_cop}}</td>
    </tr>
    </tbody>
</table>
<table>
    <thead>
        <tr>
            <td></td>
            <td><b>DESCRIPCION</b></td>
            <td><b>ITEM</b></td>
            <td><b>UNIDAD COMERCIAL</b></td>
            <td><b>VALOR DE COMPRA X MILLAR. USD</b></td>
            <td><b>COSTO EN PESOS X UND.</b></td>
            <td><b>TOTAL EN PESOS</b></td>
            <td></td>
            <td></td>
            <td><b>Factor % Landed Cost</b></td>
        </tr>
    </thead>
    <tbody>
        @foreach($items as $item)
            <tr>
                <td></td>
                <td>{{$item->product_code}}</td>
                <td>{{$item->product_name}}</td>
                <td>{{$item->received_amount}}</td>
                <td>{{ (int)$item->received_amount * (float)$item->unit_value }}</td>
                <td>{{ (float)$item->total_value * (float)$factor }}</td>
                <td>{{ (int)$item->received_amount * ((float)$item->total_value * (float)$factor) }}</td>
                <td></td>
                <td></td>
                <td>{{ round((($item->unit_value * $item->received_amount) * $factor) / (($item->received_amount) * ($item->unit_value) * ($trm)),2) }}%</td>
            </tr>
        @endforeach
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td><b>TOTAL</b></td>
                <td>{{$total_items}}</td>
            </tr>
    </tbody>
</table>
