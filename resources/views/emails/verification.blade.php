<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><title>Verification</title></head>
<body style="font-family:sans-serif;padding:20px;">
<h2>Bonjour {{ $userName }}</h2>
<p>Votre code de verification Pickup est :</p>
<h1 style="font-size:2.5rem;letter-spacing:8px;color:#2563eb;">{{ $code }}</h1>
<p>Ce code expire dans 10 minutes.</p>
<p>Si vous n avez pas cree de compte, ignorez cet email.</p>
</body>
</html>