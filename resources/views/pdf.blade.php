<html>
<head>
    <title>{{ $title }} - {{$quote[0]->client_name}}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('{{ $logo }}'); /* Imagen de fondo */
            background-position: right top; /* Posición en la parte superior derecha */
            background-repeat: no-repeat; /* No repetir la imagen */
            padding-top: 40px; /* Espaciado para evitar que el texto toque la imagen */
            position: relative;
        }
        h1 {
            color: #333;
        }
        h2 {
            color: #333;
        }
        tr{
            padding: 15px; border-bottom: 1px solid #ddd;
        }
    </style>
</head>
<body>
<h1>{{ $title }}</h1>
@foreach($quote as $registro)
    <p>Fecha de expiración: {{$registro->quote_expiration_date}}</p>
    <h2>Cliente: </h2>
    <p>NIT/CC: {{$registro->client_identification}}</p>
    <p>Nombre: {{$registro->client_name}}</p>
    <p>Número de teléfono: {{$registro->client_ph}}</p>
    <p>Dirección: {{$registro->client_address}}</p>
    <p>Email: {{$registro->client_email}}</p>
    <h2>Detalle: </h2>
    <p>Horas de trabajo: {{$registro->quote_estimated_time}}</p>
    <p>Total: {{number_format($registro->quote_total)}}</p>
@endforeach
<table style="width: 100%;  border-collapse: collapse; text-align: center; padding-top: 5% ">
    <thead style="background-color: #f7f7f7; font-weight: bold; color: #333; ">
    <tr style="padding: 15px; border-bottom: 1px solid #ddd;">
        <th>Item</th>
        <th>Descripción</th>
        <th>Imagen</th>
        <th>Cantidad</th>
        <th>Precio Unitario</th>
        <th>Valor Parcial</th>
        <th>Subtotal</th>
    </tr>
    </thead>
    <tbody>
    {{$subtotal = 0}}
    {{$contador = 1}}
    @foreach($detail as $detalle)
        {{$subtotal += $detalle->quantity * $detalle-> unit_price}}
        <tr style="padding: 15px; border-bottom: 1px solid #ddd;">
            <td>{{$contador}}</td>
            <td>{{$detalle -> prod_name}}</td>
            <td><img style="box-sizing: border-box" src="{{$detalle -> prod_image}}" alt=""></td>
            <td>{{$detalle -> quantity}}</td>
            <td>$ {{ number_format($detalle -> unit_price)}}</td>
            <td>$ {{number_format($detalle->quantity * $detalle-> unit_price)}}</td>
            <td>$ {{ number_format($subtotal) }}</td>
        </tr>
        {{$contador += 1}}
    @endforeach
    <tr>
        {{$subtotal += ($quote[0]->quote_helper_payday/8)*$quote[0]->quote_estimated_time}}
        <td>{{$contador}}</td>
        <td>Auxiliares</td>
        <td>N/A</td>
        <td>{{$quote[0]->quote_helpers}}</td>
        <td>$ {{number_format($quote[0]->quote_helper_payday) }}</td>
        <td>$ {{number_format(($quote[0]->quote_helper_payday/8)*$quote[0]->quote_estimated_time)}}</td>
        <td>$ {{number_format($subtotal)}}</td>
        {{$contador += 1}}
    </tr>
    <tr>
        {{$subtotal += ($quote[0]->quote_supervisor_payday/8)*$quote[0]->quote_estimated_time}}
        <td>{{$contador}}</td>
        <td>Supervisor de obra</td>
        <td>N/A</td>
        <td>1</td>
        <td>$ {{number_format($quote[0]->quote_supervisor_payday) }}</td>
        <td>$ {{number_format(($quote[0]->quote_supervisor_payday/8)*$quote[0]->quote_estimated_time)}}</td>
        <td>$ {{number_format($subtotal)}}</td>
        {{$contador += 1}}
        <td></td>
    </tr>

    <tr>
        {{$subtotal += $quote[0]->quote_other_costs}}
        <td>{{$contador}}</td>
        <td>Otros Costos</td>
        <td>N/A</td>
        <td>1</td>
        <td>$ {{number_format($quote[0]->quote_other_costs) }}</td>
        <td>$ {{number_format($quote[0]->quote_other_costs)}}</td>
        <td>$ {{number_format($subtotal)}}</td>
        <td></td>
    </tr>

    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>Total</td>
        <td>$ {{number_format($subtotal)}}</td>
    </tr>
    </tbody>
</table>

<table style="width: 100%;  border-collapse: collapse; text-align: center; padding-top: 15%">
    <thead style="background-color: #f7f7f7; font-weight: bold; color: #555; ">
    <tr>
        <td>Daniel Estid Molano  </td>
        <td>Email: Danielestidmolano@gmail.com </td>
        <td>Móvil: 3175040509</td>
    </tr>
    </thead>
</table>
</body>
</html>
