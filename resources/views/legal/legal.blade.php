@extends('layouts.app')

@section('title', 'Aviso legal · DulceConMaría')

@section('content')
    <section class="pt-20 pb-12">
        <div class="max-w-4xl mx-auto px-4 text-sm text-[#5B4A54] space-y-6">
            <h1 class="text-2xl font-semibold text-[#2B1A22] mb-2">Aviso legal</h1>

            <h2 class="text-base font-semibold text-[#2B1A22]">1. Información general</h2>
            <p>
                [Revisar con abogado] En este apartado debes indicar los datos de la titular de la Web:
                nombre y apellidos o razón social, NIF/CIF, domicilio, email de contacto y, en su caso,
                datos de inscripción en el Registro Mercantil u otros registros.
            </p>

            <h2 class="text-base font-semibold text-[#2B1A22]">2. Actividad</h2>
            <p>
                DulceConMaría ofrece formación online en materia de repostería y elaboración de bombones,
                chocolates y productos relacionados, a través del campus privado accesible en esta Web.
            </p>

            <h2 class="text-base font-semibold text-[#2B1A22]">3. Condiciones de uso</h2>
            <p>
                El acceso y uso del campus implica la aceptación de estas condiciones y de aquellas que
                puedan publicarse en el futuro. Como usuaria te comprometes a:
            </p>
            <ul class="list-disc list-inside space-y-1">
                <li>Utilizar la Web y el campus de forma lícita y de acuerdo con la buena fe.</li>
                <li>No realizar actuaciones que puedan dañar la imagen o los intereses de DulceConMaría o de otras personas usuarias.</li>
                <li>No intentar acceder sin autorización a áreas restringidas o a cuentas de otras personas.</li>
            </ul>

            <h2 class="text-base font-semibold text-[#2B1A22]">4. Propiedad intelectual e industrial</h2>
            <p>
                Los contenidos del campus (vídeos, textos, materiales, imágenes, marcas, logotipos, etc.)
                están protegidos por derechos de propiedad intelectual e industrial. Salvo autorización
                expresa, no está permitido:
            </p>
            <ul class="list-disc list-inside space-y-1">
                <li>Reproducir, distribuir o comunicar públicamente los contenidos del curso.</li>
                <li>Compartir tus credenciales de acceso con terceras personas.</li>
                <li>Subir a otras plataformas el contenido del curso.</li>
            </ul>

            <h2 class="text-base font-semibold text-[#2B1A22]">5. Responsabilidad</h2>
            <p>
                [Revisar con abogado] Aquí se suele indicar que, aunque se pone el máximo cuidado en el
                contenido y en el funcionamiento de la Web, la titular no puede garantizar la ausencia total
                de errores técnicos o tipográficos, ni la disponibilidad continua del servicio.
            </p>

            <h2 class="text-base font-semibold text-[#2B1A22]">6. Enlaces externos</h2>
            <p>
                La Web puede contener enlaces a sitios web de terceros. DulceConMaría no se hace responsable
                de los contenidos ni de las políticas de privacidad de dichos sitios.
            </p>

            <h2 class="text-base font-semibold text-[#2B1A22]">7. Legislación aplicable y jurisdicción</h2>
            <p>
                [Revisar con abogado] Normalmente se indica que la relación entre la titular y las personas
                usuarias se rige por la legislación española y que, salvo que la normativa de consumidores y
                usuarias establezca otra cosa, cualquier disputa se someterá a los juzgados y tribunales del
                domicilio de la titular.
            </p>
        </div>
    </section>
@endsection
