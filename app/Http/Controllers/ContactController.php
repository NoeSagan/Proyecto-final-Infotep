<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function show()
    {
        return view('contacto');
    }

    public function send(Request $request)
    {
        $request->validate([
            'name'    => ['required', 'string', 'max:100'],
            'email'   => ['required', 'email', 'max:100'],
            'subject' => ['required', 'string', 'max:200'],
            'message' => ['required', 'string', 'max:2000'],
        ], [
            'name.required'    => 'El nombre es obligatorio.',
            'email.required'   => 'El correo electrónico es obligatorio.',
            'email.email'      => 'El correo electrónico no es válido.',
            'subject.required' => 'El asunto es obligatorio.',
            'message.required' => 'El mensaje es obligatorio.',
        ]);

        // Log the message (email driver handles actual delivery via .env MAIL_MAILER)
        \Illuminate\Support\Facades\Log::info('Contacto recibido', [
            'name'    => $request->name,
            'email'   => $request->email,
            'subject' => $request->subject,
        ]);

        return back()->with('success', '¡Mensaje enviado! Nos pondremos en contacto contigo pronto.');
    }
}
