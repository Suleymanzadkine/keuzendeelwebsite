<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Registrations</title>
</head>

<body>
    <h1>My Registrations</h1>

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

    @if($inschrijvingen->isEmpty())
    <p>You don't have any registrations yet.</p>
    @else
    @foreach($inschrijvingen as $inschrijving)
    <div style="border: 1px solid #ccc; padding: 15px; margin: 10px 0;">
        <h2>{{ $inschrijving->keuzedeel->naam }}</h2>
        <p>{{ $inschrijving->keuzedeel->beschrijving }}</p>
        <p><strong>Period:</strong> {{ $inschrijving->keuzedeel->periode }}</p>
        <p><strong>Status:</strong>
            @if($inschrijving->status === 'ingeschreven')
            <span style="color: green;">Registered</span>
            @elseif($inschrijving->status === 'afgerond')
            <span style="color: blue;">Completed</span>
            @else
            <span style="color: gray;">Cancelled</span>
            @endif
        </p>
        <p><strong>Registered on:</strong> {{ $inschrijving->created_at->format('d-m-Y H:i') }}</p>

        @if($inschrijving->status === 'ingeschreven')
        <form method="POST" action="{{ route('keuzedelen.uitschrijven', $inschrijving->keuzedeel) }}">
            @csrf
            <button type="submit">Unregister</button>
        </form>
        @endif
    </div>
    @endforeach
    @endif
</body>

</html>