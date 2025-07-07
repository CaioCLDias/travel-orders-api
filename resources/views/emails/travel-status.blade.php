<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Ordem de Viagem - {{ ucfirst($status) }}</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f7fafc; padding: 20px; color: #2d3748;">
    <div
        style="background-color: #fff; padding: 30px; border-radius: 8px; max-width: 600px; margin: auto; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        @php
            $nome = $user->name ?? ($user->email ?? 'usuário');
            $corStatus = $status === 'aprovada' ? '#38a169' : '#e53e3e';
        @endphp

        <h2 style="color: {{ $corStatus }};">Olá, {{ $nome }}</h2>

        <p>Sua ordem de viagem foi <strong>{{ $status }}</strong>.</p>

        <ul>
            <li><strong>Destino:</strong> {{ $travelOrder->destination->name }} </li>
            <li><strong>Data de ida:</strong> {{ \Carbon\Carbon::parse($travelOrder->departure_date)->format('d/m/Y') }}
            </li>
            <li><strong>Data de volta:</strong> {{ \Carbon\Carbon::parse($travelOrder->return_date)->format('d/m/Y') }}
            </li>

        </ul>

        <a href="{{ $url }}"
            style="display:inline-block;padding:10px 20px;margin-top:20px;color:#fff;background-color:#3182ce;border-radius:5px;text-decoration:none;">
            Ver pedido
        </a>

        <p style="margin-top: 40px; font-size: 12px; color: #718096;">Obrigado por utilizar o Travel Orders!</p>
    </div>
</body>

</html>
