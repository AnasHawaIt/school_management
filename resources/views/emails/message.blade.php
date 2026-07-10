
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>New Message</title>
</head>
<body>

<h2>New Message</h2>

<p><strong>Subject:</strong> {{ $messageData->subject }}</p>

<p><strong>Message:</strong></p>

<p>{{ $messageData->body }}</p>

</body>
</html>
