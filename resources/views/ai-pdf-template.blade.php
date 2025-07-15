<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Document' }}</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; line-height: 1.6; }
    </style>
</head>
<body>
    <h1>{{ $title }}</h1>
    <div>{!! $content !!}</div>
</body>
</html>
