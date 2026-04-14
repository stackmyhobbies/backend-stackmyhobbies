<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $actionText ?? 'Notificación' }} - {{ config('app.name') }}</title>
    <style>
        body {
            background-color: #f6f9fc;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #2d3748;
        }

        .email-wrapper {
            width: 100%;
            background-color: #f6f9fc;
            padding: 40px 0;
        }

        .email-content {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .email-header {
            text-align: center;
            padding: 30px 20px 10px;
        }

        .email-header img {
            width: 48px;
            height: 48px;
            margin-bottom: 15px;
        }

        .email-body {
            padding: 0 40px 30px;
            text-align: center;
        }

        .email-body h1 {
            font-size: 20px;
            color: #111827;
            margin-bottom: 10px;
        }

        .email-body p {
            font-size: 15px;
            color: #4a5568;
            line-height: 1.6;
            margin: 10px 0;
        }

        .button-container {
            margin: 30px 0;
        }

        .button {
            display: inline-block;
            background-color: #0069ff;
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 15px;
        }

        .button:hover {
            background-color: #0052cc;
        }

        .email-footer {
            padding: 25px 20px;
            text-align: center;
            font-size: 13px;
            color: #a0aec0;
            border-top: 1px solid #e2e8f0;
            background-color: #fafbfc;
        }

        .email-footer p {
            margin: 6px 0;
        }

        .email-footer a {
            color: #0069ff;
            text-decoration: none;
        }

        .fallback-url {
            word-break: break-all;
            display: inline-block;
            color: #3182ce;
            margin-top: 8px;
        }
    </style>
</head>

<body>
    <div class="email-wrapper">
        <div class="email-content">
            <div class="email-header">
                <img src="{{ asset('logo.png') }}" alt="{{ config('app.name') }}">
            </div>

            <div class="email-body">
                <h1>{{ $actionText ?? 'Confirma tu cuenta' }}</h1>

                @foreach ($introLines as $line)
                    <p>{{ $line }}</p>
                @endforeach

                @isset($actionText)
                    <div class="button-container">
                        <a href="{{ $actionUrl }}" class="button">{{ $actionText }}</a>
                    </div>
                @endisset

                @foreach ($outroLines as $line)
                    <p>{{ $line }}</p>
                @endforeach
            </div>

            <div class="email-footer">
                <p>Si el botón no funciona, copia y pega este enlace en tu navegador:</p>
                <p class="fallback-url">{{ $displayableActionUrl }}</p>

                <p style="margin-top: 10px;">
                    © {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.
                </p>
            </div>
        </div>
    </div>
</body>

</html>
