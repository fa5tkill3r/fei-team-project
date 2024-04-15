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
    }

    .section {
        margin-top: 0 !important;
        margin-bottom: 0 !important;
        margin-left: 10px;

    }

    .solution-block {
        margin: 0 !important;
        padding: 0 !important;
        border-bottom: 1px solid black;

    }

    .title {
        margin: 0; !important;
    }
    .title_top {
        text-align: center;
        margin: 0; !important;
    }


</style>
<body>
    <h1 class="title_top">Záznam o bezpečnostnom incidente</h1>
    <div>
        <p><b>Meno incidentu:</b> {{ $incident->name }}</p>
        <p><b>Číslo bezpečostného incidentu:</b> {{ $incident->id }}</p>
        <p><b>Dátum a čas vzniku incidentu:</b> {{ $incident->created_at }}</p>
        <p><b>Dátum a čas nahlásenia incudentu:</b> {{ $incident->description }} ?? aj toto cheme pytat</p>
    </div>

    <h4 class="title">Bezpečnostný incident nahlásil:</h4>
    <div class="section">
        <p>Meno: {{ $incident->ais_id }}</p>
        <p>Pracoviko: {{ $incident->department }}</p>
        <p>Telefónne číslo: {{ $incident->phone }}</p>
        <p>Email: {{ $incident->email }}</p>
    </div>

    <p><b>Dotknutá služba:</b> {{ $additional->attacked_service }}</p>
    <p><b>Závažnosť bezpečnostného incidentu:</b> {{ $additional->attack_severity }}</p>
    <p><b>Spôspob hlásenia:</b> {{ $incident->source}}</p>
    <p><b>Odhadovaný dopad:</b> {{ $additional->predicated_attack_severity }}</p>
    <p><b>Stručný opis incidentu:</b> {{ $additional->description }}</p>
    <h4 class="title">Časové údaje bezpečnostného incidentu:</h4>

    <div class="section">
        @foreach($incidentChronologically as $ic)
            <p>{{ $ic->date }} - {{ $ic->description }}</p>
        @endforeach
    </div>


    <p><b>Popis škôd:</b> {{ $additional->description_of_damage }}</p>
    <h4 class="title">Priebeh vyšetrovania:</h4>
    <div class="section">
        @foreach($comments as $comment)
            <p>{{ $comment->created_at }} - {{ $comment->comment }}</p>
        @endforeach
    </div>
    <p><b>Kategória bezpečnostného incidentu:</b> {{ $additional->attack_category }}</p>
    <p><b>Typ bezpečnostného incidentu:</b> {{ $additional->attack_type }}</p>




    <p><b>Poznámky:</b> {{ $additional->notes }}</p>

    <h4 class="title">Opatrenia:</h4>
    @foreach($solutions as $sol)
        <div class="solution-block">
            <p><b>{{ $sol->name }}</b></p>
            <p>{{ $sol->deadline }} - <b>{{ $sol->name_of_responsible_person }}</b></p>
            <p>{{ $sol->description }}</p>
        </div>
    @endforeach



</body>
</html>
