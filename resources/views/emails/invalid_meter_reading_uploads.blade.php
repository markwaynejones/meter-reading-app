<!DOCTYPE html>
<html>
<head>
    <title>Invalid Meter Reading Uploads</title>
</head>
<body>
<h1>Invalid Meter Reading Uploads</h1>
<p>The following rows failed validation during processing the meter readings CSV upload:</p>

<table>
    <thead>
    <tr>
        <th>CSV Row Data</th>
        <th>Errors</th>
    </tr>
    </thead>
    <tbody>
    @foreach($invalidRows as $entry)
        <tr>
            <td>{{ implode(', ', $entry['data']) }}</td>
            <td>{{ implode('; ', $entry['errors']) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
