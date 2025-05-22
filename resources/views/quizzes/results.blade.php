@extends('layouts.app')

@section('title', 'Mis Resultados')

@push('styles')
<style>
.hidden-details {
    display: none;
}

.badge {
    padding: 0.35em 0.65em;
    font-size: 0.75em;
    font-weight: 700;
    border-radius: 0.25rem;
}
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="mb-4 card">
            <div class="pb-0 card-header">
                <h1 class="mb-6 text-2xl font-bold text-gray-800">Resultados de Quizzes</h1>
            </div>

            <div class="px-0 pt-0 pb-2 card-body">
                <div class="p-0 table-responsive">
                    <table class="table mb-0 align-items-center justify-content-center">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Quiz</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Puntaje</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Estado</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Progreso</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tiempo</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Fecha</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($attempts as $attempt)
                            <tr>
                                <td>
                                    <div class="px-2 d-flex">
                                        <div class="my-auto">
                                            <h6 class="mb-0 text-sm">{{ $attempt->quiz->title }}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="mb-0 text-sm font-weight-bold">{{ $attempt->score }} / {{ $attempt->total_questions }}</p>
                                </td>
                                <td>
                                    @php
                                    $statusClass = $attempt->score == $attempt->total_questions ? 'bg-gradient-success' :
                                    ($attempt->score >= ($attempt->total_questions/2) ? 'bg-gradient-info' : 'bg-gradient-danger');
                                    $statusText = $attempt->score == $attempt->total_questions ? 'Completado' :
                                    ($attempt->score >= ($attempt->total_questions/2) ? 'En progreso' : 'Reprobado');
                                    @endphp
                                    <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                                </td>
                                <td class="text-center align-middle">
                                    @php
                                    $percentage = round(($attempt->score / $attempt->total_questions) * 100);
                                    @endphp
                                    <div class="d-flex align-items-center justify-content-center">
                                        <span class="text-xs me-2 font-weight-bold">{{ $percentage }}%</span>
                                        <div>
                                            <div class="progress">
                                                <div class="progress-bar {{ $statusClass }}" role="progressbar"
                                                    aria-valuenow="{{ $percentage }}" aria-valuemin="0"
                                                    aria-valuemax="100" style="width: {{ $percentage }}%;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="mb-0 text-sm font-weight-bold">{{ gmdate('i:s', $attempt->time_spent) }} min</p>
                                </td>
                                <td>
                                    <p class="mb-0 text-sm font-weight-bold">{{ $attempt->completed_at->format('d/m/Y') }}</p>
                                </td>
                                <td class="align-middle">
                                    @if($attempt->completed_at) {{-- Mostrar botón si el quiz fue completado --}}
                                    <button class="mb-0 btn btn-sm btn-outline-primary" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#detailsModal{{ $attempt->id }}">
                                        Ver Detalle
                                    </button>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="p-4 text-center">
                                    <p class="text-sm text-gray-500">Aún no has finalizado ningún quiz.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals para cada intento -->
<!-- Modals para cada intento -->
@foreach($attempts as $attempt)
@if($attempt->completed_at) {{-- Crear modal solo para quizzes completados --}}
<div class="modal fade" id="detailsModal{{ $attempt->id }}" tabindex="-1" aria-labelledby="detailsModalLabel{{ $attempt->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailsModalLabel{{ $attempt->id }}">Detalles del Quiz: {{ $attempt->quiz->title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-4">
                    <h6 class="mb-2 font-bold">Resumen:</h6>
                    <div class="row">
                        <div class="col-md-4">
                            <p class="mb-1"><strong>Puntaje:</strong> {{ $attempt->score }}/{{ $attempt->total_questions }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-1"><strong>Tiempo:</strong> {{ gmdate('i:s', $attempt->time_spent) }} min</p>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-1"><strong>Fecha:</strong> {{ $attempt->completed_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
                
                @if(isset($attempt->answers_data['details']))
                <h6 class="mb-3 font-bold">Respuestas:</h6>
                <div class="space-y-3">
                    @foreach($attempt->answers_data['details'] as $index => $detail)
                    <div class="p-3 rounded {{ $detail['is_correct'] ? 'bg-green-50' : 'bg-red-50' }}">
                        <p class="mb-1 font-bold">Pregunta {{ $index + 1 }}: {{ $detail['question'] }}</p>
                        <p class="mb-1">Tu respuesta: {{ $detail['user_answer'] ?? 'No respondida' }}</p>
                        @if(!$detail['is_correct'] && !empty($detail['correct_answer']))
                        <p class="mb-0">Respuesta correcta: {{ $detail['correct_answer'] }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
                @else
                <div class="alert alert-info">
                    No hay detalles disponibles para este quiz.
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@endif
@endforeach

@endsection
@push('scripts')
<script>
    // Inicializar tooltips si es necesario
    $(document).ready(function(){
        $('[data-bs-toggle="tooltip"]').tooltip();
    });
</script>
@endpush