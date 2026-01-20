<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $keuzedeel->naam }} - Details</title>
</head>

<body>
    <h1>{{ $keuzedeel->naam }}</h1>

    @if(session('success'))
    <div style="color: green; border: 1px solid green; padding: 10px; margin: 10px 0;">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div style="color: red; border: 1px solid red; padding: 10px; margin: 10px 0;">
        {{ session('error') }}
    </div>
    @endif

    <a href="{{ route('keuzedelen.index') }}">Back to overview</a>

    <hr>

    <div style="border: 1px solid #ccc; padding: 15px; margin: 10px 0;">
        <h2>Description</h2>
        <p>{{ $keuzedeel->beschrijving }}</p>

        <h3>Information</h3>
        <p><strong>Period:</strong> {{ $keuzedeel->periode }}</p>
        <p><strong>Minimum participants:</strong> {{ $keuzedeel->min_deelnemers }}</p>
        <p><strong>Maximum participants:</strong> {{ $keuzedeel->max_deelnemers }}</p>
        <p><strong>Currently registered:</strong> {{ $keuzedeel->aantalIngeschreven() }}</p>

        @php
        $user = Auth::user();
        $isIngeschreven = $user->actieveInschrijvingen()
        ->where('keuzedeel_id', $keuzedeel->id)
        ->exists();
        $isAfgerond = $user->heeftKeuzedeelAfgerond($keuzedeel->id);
        $heeftPeriode = $user->heeftInschrijvingVoorPeriode($keuzedeel->periode);
        @endphp

        <hr>

        @if($isIngeschreven)
        <p style="color: green;"><strong>You are registered for this keuzedeel</strong></p>
        <form method="POST" action="{{ route('keuzedelen.uitschrijven', $keuzedeel) }}">
            @csrf
            <button type="submit">Unregister</button>
        </form>
        @elseif($isAfgerond)
        <p style="color: blue;"><strong>You have already completed this keuzedeel</strong></p>
        @elseif($keuzedeel->isVol())
        <p style="color: red;"><strong>This keuzedeel is FULL</strong></p>
        @elseif($heeftPeriode)
        <p style="color: orange;"><strong>You already have a keuzedeel in period {{ $keuzedeel->periode }}</strong></p>
        @else
        <form method="POST" action="{{ route('keuzedelen.inschrijven', $keuzedeel) }}">
            @csrf
            <button type="submit">Register for this keuzedeel</button>
        </form>
        @endif
    </div>

    <hr>

    <h2>Registered students ({{ $keuzedeel->aantalIngeschreven() }})</h2>
    @if($keuzedeel->actieveInschrijvingen->isEmpty())
    <p>No students registered yet.</p>
    @else
    <ul>
        @foreach($keuzedeel->actieveInschrijvingen as $inschrijving)
        <li>{{ $inschrijving->user->name }} - Registered on {{ $inschrijving->created_at->format('d-m-Y') }}</li>
        @endforeach
    </ul>
    @endif
</body>

</html>