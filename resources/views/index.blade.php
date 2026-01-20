<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keuzedelen - Overview</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .alert { padding: 10px; margin: 10px 0; border: 1px solid #ccc; }
        .alert-success { color: green; border-color: green; }
        .alert-error { color: red; border-color: red; }
        .card { border: 1px solid #ccc; padding: 15px; margin: 10px 0; }
    </style>
</head>

<body>
    <h1>Available Keuzedelen</h1>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-error">
        {{ session('error') }}
    </div>
    @endif

    <a href="{{ route('keuzedelen.mijn-inschrijvingen') }}">My Registrations</a>

    <hr>

    @foreach($keuzedelen as $keuzedeel)
    <div class="card">
        <h2>{{ $keuzedeel->naam }}</h2>
        <p>{{ $keuzedeel->beschrijving }}</p>
        <p><strong>Period:</strong> {{ $keuzedeel->periode }}</p>
        <p><strong>Registered:</strong> {{ $keuzedeel->aantalIngeschreven() }} / {{ $keuzedeel->max_deelnemers }}</p>

        @php
        $isIngeschreven = auth()->user()?->actieveInschrijvingen()
        ->where('keuzedeel_id', $keuzedeel->id)
        ->exists() ?? false;
        $isAfgerond = auth()->user()?->heeftKeuzedeelAfgerond($keuzedeel->id) ?? false;
        $heeftPeriode = auth()->user()?->heeftInschrijvingVoorPeriode($keuzedeel->periode) ?? false;
        @endphp

        @if($isIngeschreven)
        <p style="color: green;"><strong>You are registered</strong></p>
        <form method="POST" action="{{ route('keuzedelen.uitschrijven', $keuzedeel) }}">
            @csrf
            <button type="submit">Unregister</button>
        </form>
        @elseif($isAfgerond)
        <p style="color: blue;"><strong>Already completed</strong></p>
        @elseif($keuzedeel->isVol())
        <p style="color: red;"><strong>FULL</strong></p>
        @elseif($heeftPeriode)
        <p style="color: orange;"><strong>You already have a keuzedeel in period {{ $keuzedeel->periode }}</strong></p>
        @else
        <form method="POST" action="{{ route('keuzedelen.inschrijven', $keuzedeel) }}">
            @csrf
            <button type="submit">Register</button>
        </form>
        @endif

        <a href="{{ route('keuzedelen.show', $keuzedeel) }}">More details</a>
    </div>
    @endforeach
</body>

</html>