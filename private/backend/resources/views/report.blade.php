<!doctype html>
<html lang="sk">
<head>
    <title>Report</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

</head>
<style>
    @font-face {
        font-family: 'Romco';
        src: url({{ storage_path('fonts/Times_New_Roman.ttf') }}) format('truetype');
        font-style: normal;
    }

    @font-face {
        font-family: 'Romco';
        src: url({{ storage_path('fonts/Times_New_Roman_Bold.ttf') }}) format('truetype');
        font-weight: bold;
    }

    body {
        font-family: 'Romco', sans-serif;
        font-weight: 400;
    }


</style>
<body>
    <h1>Záznam o bezpečnostnom incidente</h1>
    <div>
        <p>Meno incidentu: {{ $incident->name }}</p>
        <p>Číslo bezpečostného incidentu: {{ $incident->id }}</p>
        <p>Dátum a čas vzniku incidentu: {{ $incident->created_at }}</p>
        <p>Dátum a čas nahlásenia incudentu: {{ $incident->description }} ?? aj toto cheme pytat</p>
    </div>

    <h4>Bezpečnostný incident nahlásil:</h4>
    <div>
        <p>Meno: {{ $incident->ais_id }}</p>
        <p>Pracoviko: {{ $incident->department }}</p>
        <p>Telefónne číslo: {{ $incident->phone }}</p>
        <p>Email: {{ $incident->email }}</p>
    </div>

    <p>Dotknutá služba: {{ $additional->attacked_service }}</p>
    <p>Závažnosť bezpečnostného incidentu: {{ $additional->attack_severity }}</p>
    <p>Spôspob hlásenia: {{ $incident->source}}</p>
    <p>Odhadovaný dopad: {{ $additional->predicated_attack_severity }}</p>
    <p>Stručný opis incidentu: {{ $additional->description }}</p>
    <h4>Časové údaje bezpečnostného incidentu:</h4>
    @foreach($incidentChronologically as $ic)
        <p>{{ $ic->date }} - {{ $ic->description }}</p>
    @endforeach

    <p>Popis škôd: {{ $additional->description_of_damage }}</p>
    <h4>Priebeh vyšetrovania:</h4>
    @foreach($comments as $comment)
        <p>{{ $comment->created_at }} - {{ $comment->comment }}</p>
    @endforeach

    <p>Kategória bezpečnostného incidentu: {{ $additional->attack_category }}</p>
    <p>Typ bezpečnostného incidentu: {{ $additional->attack_type }}</p>

    <h4>Opatrenia:</h4>
    @foreach($solutions as $sol)
        <p>{{ $sol->name }}</p>
        <p>{{ $sol->description }}</p>
        <p>{{ $sol->name_of_responsible_person }}</p>
        <p>{{ $sol->deadline }}</p>
    @endforeach

    <p>Poznámky: {{ $additional->notes }}</p>

</body>
</html>
