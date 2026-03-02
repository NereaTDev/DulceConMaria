@extends('layouts.app')

@section('title', 'Política de privacidad · DulceConMaría')

@section('content')
    <section class="pt-20 pb-12">
        <div class="max-w-4xl mx-auto px-4 text-sm text-[#5B4A54] space-y-6">
            <h1 class="text-2xl font-semibold text-[#2B1A22] mb-2">Política de privacidad</h1>

            <p>
                Esta política de privacidad describe cómo se recopilan, utilizan y protegen los datos
                personales de las usuarias del campus de DulceConMaría (en adelante, "la Web").
            </p>

            <h2 class="text-base font-semibold text-[#2B1A22]">1. Responsable del tratamiento</h2>
            <p>
                [Revisar con abogado] Aquí debes indicar tus datos como responsable:
                nombre y apellidos o razón social, NIF/CIF, domicilio, email de contacto y, en su caso,
                datos de inscripción en el Registro Mercantil.
            </p>

            <h2 class="text-base font-semibold text-[#2B1A22]">2. Datos que tratamos</h2>
            <p>En la Web tratamos, entre otros, los siguientes datos personales:</p>
            <ul class="list-disc list-inside space-y-1">
                <li>Datos de identificación: nombre y apellidos, email.</li>
                <li>Datos de acceso al campus: credenciales de acceso (email y contraseña cifrada).</li>
                <li>Datos de uso del campus: cursos en los que te has inscrito, lecciones vistas y progreso.</li>
                <li>Datos de facturación (si proceden): país y datos necesarios para la emisión de facturas, a través del proveedor de pagos.</li>
            </ul>

            <h2 class="text-base font-semibold text-[#2B1A22]">3. Finalidades del tratamiento</h2>
            <p>Utilizamos tus datos para las siguientes finalidades:</p>
            <ul class="list-disc list-inside space-y-1">
                <li>Gestionar tu registro como usuaria del campus.</li>
                <li>Gestionar tus inscripciones a cursos y el acceso a sus contenidos.</li>
                <li>Mostrar tu progreso dentro de los cursos y lecciones.</li>
                <li>Atender consultas y soporte relacionadas con el curso o el campus.</li>
                <li>Enviar comunicaciones relacionadas con el servicio (por ejemplo, restablecimiento de contraseña).</li>
            </ul>

            <h2 class="text-base font-semibold text-[#2B1A22]">4. Base jurídica del tratamiento</h2>
            <p>
                [Revisar con abogado] Normalmente, la base legal será la ejecución de un contrato
                (tus compras o inscripciones), tu consentimiento (por ejemplo, para comunicaciones
                comerciales) y el interés legítimo para mejorar el servicio.
            </p>

            <h2 class="text-base font-semibold text-[#2B1A22]">5. Encargados de tratamiento y transferencias</h2>
            <p>Para prestar el servicio utilizamos proveedores externos que pueden tratar datos en nuestro nombre:</p>
            <ul class="list-disc list-inside space-y-1">
                <li>Proveedor de hosting y backend del campus (Render).</li>
                <li>Proveedor de base de datos (Supabase/Postgres).</li>
                <li>Proveedor de envío de emails (Brevo u otros equivalentes).</li>
                <li>Plataformas de vídeo (YouTube u otras que utilices para alojar las lecciones en vídeo).</li>
            </ul>
            <p>
                [Revisar con abogado] En este apartado debes detallar, para cada proveedor, si se
                producen transferencias internacionales de datos y bajo qué garantías (cláusulas tipo,
                proveedores en la UE, etc.).
            </p>

            <h2 class="text-base font-semibold text-[#2B1A22]">6. Plazos de conservación</h2>
            <p>
                Conservaremos tus datos mientras mantengas tu cuenta activa en el campus o durante los
                plazos exigidos por la normativa aplicable (por ejemplo, obligaciones fiscales).
            </p>

            <h2 class="text-base font-semibold text-[#2B1A22]">7. Derechos de las personas usuarias</h2>
            <p>Puedes ejercer los siguientes derechos en relación con tus datos personales:</p>
            <ul class="list-disc list-inside space-y-1">
                <li>Acceso a tus datos personales.</li>
                <li>Rectificación de los datos inexactos o incompletos.</li>
                <li>Supresión de tus datos cuando ya no sean necesarios.</li>
                <li>Limitación u oposición al tratamiento en determinadas circunstancias.</li>
                <li>Portabilidad de los datos cuando proceda.</li>
            </ul>
            <p>
                Para ejercer estos derechos, puedes ponerte en contacto en la dirección indicada en el
                apartado de responsable del tratamiento.
            </p>

            <h2 class="text-base font-semibold text-[#2B1A22]">8. Reclamaciones</h2>
            <p>
                Si consideras que tus derechos no han sido atendidos adecuadamente, puedes presentar una
                reclamación ante la autoridad de control competente. En España, esta es la Agencia Española
                de Protección de Datos (AEPD).
            </p>

            <h2 class="text-base font-semibold text-[#2B1A22]">9. Modificaciones de esta política</h2>
            <p>
                Nos reservamos el derecho a actualizar esta política de privacidad para adaptarla a cambios
                legales o a la propia prestación del servicio. En caso de cambios relevantes, se te
                informará con antelación razonable.
            </p>
        </div>
    </section>
@endsection
