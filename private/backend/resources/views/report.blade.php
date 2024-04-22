<!doctype html>
<html lang="sk">
<head>
    <title>Report</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

</head>
<style>
    @font-face {
        font-family: 'Times New Roman';
        src: url({{ storage_path('fonts/Times_New_Roman.ttf') }}) format('truetype');
        font-style: normal;
    }

    @font-face {
        font-family: 'Times New Roman';
        src: url({{ storage_path('fonts/Times_New_Roman_Bold.ttf') }}) format('truetype');
        font-weight: bold;
    }

    body {
        font-family: 'Times New Roman', serif;
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
    }

    table {
        width: 100%;
    }

    table > tr {
        vertical-align: middle;
    }

    .signatures {
        position: absolute;
        bottom: 0;
    }
</style>
<body>
<h1 class="title_top">Záznam o bezpečnostnom incidente</h1>
<table>
    <tr>
        <td><b>Meno incidentu:</b> {{ $incident->name }}</td>
        <td><b>Číslo bezpečnostného incidentu:</b> {{ $incident->id }}</td>
    </tr>
    <tr>
        <td><b>Vznik incidentu:</b> {{ $incident->incident_created_at }}</td>
        <td><b>Nahlásenie incidentu:</b> {{ $incident->created_at }}</td>
    </tr>
    <tr>
        <h4 class="title">Bezpečnostný incident nahlásil:</h4>
    </tr>
    <tr>
        <td><b>Meno:</b> {{ $incident->name }}</td>
        <td><b>Priezvisko:</b> {{ $incident->surname }}</td>
    </tr>
    <tr>
        <td><b>Email:</b> {{ $incident->email }}</td>
        <td><b>Telefónne číslo:</b> {{ $incident->phone }}</td>
    </tr>

    <tr>
        <td><b>Pracovisko:</b> {{ $incident->department }}</td>
        <td><b>AIS ID:</b> {{ $incident->ais_id }}</td>
    </tr>

    <tr>
        <td><b>Dotknutá služba:</b> {{ $additional->attacked_service }}</td>
        <td><b>Závažnosť bezpečnostného incidentu:</b> {{ $additional->attack_severity }}</td>
    </tr>

    <tr>
        <td><b>Spôspob hlásenia:</b> {{ $incident->source}}</td>
        <td><b>Odhadovaný dopad:</b> {{ $additional->predicated_attack_severity }}</td>
    </tr>

    <tr>
        <td><b>Kategória bezpečnostného incidentu:</b> {{ $additional->attack_category }}</td>
    </tr>

    <tr>
        <td><b>Typ bezpečnostného incidentu:</b> {{ $additional->attack_type }}</td>
    </tr>
</table>
<b>Stručný opis incidentu:</b> {{ $additional->description }}


<h4 class="title">Časové údaje bezpečnostného incidentu:</h4>

<div class="section">
    @foreach($incidentChronologically as $ic)
        {{ $ic->date }} - {{ $ic->description }}
            <br>
    @endforeach
</div>


<b>Popis škôd:</b> {{ $additional->description_of_damage }} <br>

<h4 class="title">Priebeh vyšetrovania:</h4>
<div class="section">
    @foreach($comments as $comment)
        {{ $comment->created_at }} - {{ $comment->comment }}
        @if(!$loop->last)
            <br>
        @endif
    @endforeach
</div>



<b>Poznámky:</b> {{ $additional->notes }} <br>

<h4 class="title">Opatrenia:</h4>
@foreach($solutions as $sol)
    <div class="solution-block">
        <b>{{ $sol->name }}</b> <br>
        {{ $sol->deadline }} - <b>{{ $sol->name_of_responsible_person }}</b><br>
        {{ $sol->description }}
    </div>
@endforeach


<div class="signatures">
    <b>Hlásenie o bezpečnostnom incidente prijal: </b> {{ $taker->titles_before }}
    {{ $taker->first_name }}  {{$taker->last_name}}
    {{ $taker->titles_after }}<br> <br>

    <b>Navrhované bezpečnostné opatrenia schválil: </b> {{ $approver->titles_before }}
    {{ $approver->first_name }}  {{$approver->last_name}}
    {{ $approver->titles_after }}

</div>

</body>

</html>
