<x-public-layout>
    <x-slot:title>Contacto | AutoAlquiler</x-slot:title>

    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold">Contáctanos</h1>
            <p class="opacity-50 mt-2">¿Tienes preguntas, sugerencias o necesitas ayuda? Escríbenos.</p>
        </div>

        @if (session('success'))
            <x-alert style="success" class="mb-6">
                {{ session('success') }}
            </x-alert>
        @endif

        <x-card>
            <x-card.body>
                <form method="POST" action="{{ route('contacto.send') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-semibold mb-1">Nombre</label>
                        <input type="text" id="name" name="name"
                               value="{{ old('name', auth()->user()?->name) }}"
                               class="w-full border border-[var(--border)] rounded-[var(--radius)] bg-[var(--input)] px-3 h-9 text-sm placeholder:opacity-40 focus:outline-none focus:ring-2 focus:ring-[var(--ring)] @error('name') border-[var(--danger)] @enderror"
                               required
                               placeholder="Tu nombre completo">
                        @error('name') <p class="text-[var(--danger)] text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold mb-1">Correo electrónico</label>
                        <input type="email" id="email" name="email"
                               value="{{ old('email', auth()->user()?->email) }}"
                               class="w-full border border-[var(--border)] rounded-[var(--radius)] bg-[var(--input)] px-3 h-9 text-sm placeholder:opacity-40 focus:outline-none focus:ring-2 focus:ring-[var(--ring)] @error('email') border-[var(--danger)] @enderror"
                               required
                               placeholder="tu@correo.com">
                        @error('email') <p class="text-[var(--danger)] text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="subject" class="block text-sm font-semibold mb-1">Asunto</label>
                        <input type="text" id="subject" name="subject"
                               value="{{ old('subject') }}"
                               class="w-full border border-[var(--border)] rounded-[var(--radius)] bg-[var(--input)] px-3 h-9 text-sm placeholder:opacity-40 focus:outline-none focus:ring-2 focus:ring-[var(--ring)] @error('subject') border-[var(--danger)] @enderror"
                               required
                               placeholder="¿En qué podemos ayudarte?">
                        @error('subject') <p class="text-[var(--danger)] text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-semibold mb-1">Mensaje</label>
                        <textarea id="message" name="message" rows="5"
                                  class="w-full border border-[var(--border)] rounded-[var(--radius)] bg-[var(--input)] px-3 py-2 text-sm placeholder:opacity-40 focus:outline-none focus:ring-2 focus:ring-[var(--ring)] @error('message') border-[var(--danger)] @enderror"
                                  required
                                  placeholder="Escribe tu mensaje aquí...">{{ old('message') }}</textarea>
                        @error('message') <p class="text-[var(--danger)] text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-end pt-2">
                        <x-btn type="submit">Enviar mensaje</x-btn>
                    </div>
                </form>
            </x-card.body>
        </x-card>

        <p class="mt-6 text-center text-sm opacity-50">
            También puedes encontrarnos en nuestros canales habituales de atención.
        </p>
    </div>

</x-public-layout>
