<x-mail::message>
# Zdravo, {{$user->ime}}

Hvala sto ste se registrovali na nasu aplikaciju.

Molimo Vas da verifikujete svoju email adresu klikom na dugme ispod:

<x-mail::button :url="$verificationUrl">
Verifikuj email
</x-mail::button>

Ako niste kreirali ovaj nalog, slobodno ignorisite ovu poruku.

Hvala,<br>
{{ config('app.name') }}
</x-mail::message>

