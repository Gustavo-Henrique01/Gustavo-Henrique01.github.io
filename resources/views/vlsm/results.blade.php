<!DOCTYPE html>
<html>
<head>
    <title>Resultados do Cálculo VLSM</title>
</head>
<body>
    <h1>Resultados do Cálculo VLSM</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Nome da Sub-rede</th>
                <th>Hosts Necessários</th>
                <th>Hosts Disponíveis</th>
                <th>Hosts Não Utilizados</th>
                <th>Endereço de Rede</th>
                <th>Máscara</th>
                <th>Faixa Utilizável</th>
                <th>Endereço de Broadcast</th>
            </tr>
        </thead>
        <tbody>
            @foreach($subredes as $subrede)
                <tr>
                    <td>{{ $subrede['nome'] }}</td>
                    <td>{{ $subrede['hosts_necessarios'] }}</td>
                    <td>{{ $subrede['hosts_disponiveis'] }}</td>
                    <td>{{ $subrede['hosts_nao_utilizados'] }}</td>
                    <td>{{ $subrede['endereco_rede'] }}</td>
                    <td>{{ $subrede['mascara'] }}</td>
                    <td>{{ $subrede['faixa_utilizavel'] }}</td>
                    <td>{{ $subrede['endereco_broadcast'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
