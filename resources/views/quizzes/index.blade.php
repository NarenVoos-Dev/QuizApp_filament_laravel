@extends('layouts.app')

@section('title', 'Quizzes Disponibles')

@section('content')
@if($quizzes->isEmpty())
  <div class="px-3 py-5 text-center shadow card">
      <i class="mb-3 fas fa-info-circle fa-3x text-warning"></i>
      <h5 class="text-muted">No hay quizzes disponibles por ahora</h5>
      <p class="text-sm text-secondary">Vuelve más tarde o revisa con tu instructor.</p>
  </div>
@else
  <div class="row">
    @foreach($quizzes as $quiz)
      <div class="mb-4 col-md-6 col-lg-4">
        <div class="border-0 shadow card h-100 animate__animated animate__fadeInUp">
          <div class="card-body">
            <h5 class="card-title text-dark">{{ $quiz->title }}</h5>
            <p class="text-muted">{{ Str::limit($quiz->description, 90) }}</p>
            <ul class="list-unstyled small">
              <li><i class="fas fa-question-circle text-primary me-2"></i>{{ $quiz->questions_count }} preguntas</li>
              <li><i class="fas fa-clock text-primary me-2"></i>{{ $quiz->time_per_question * $quiz->questions_count }} min aprox.</li>
              <li><i class="fas fa-calendar-alt text-primary me-2"></i>Disponible hasta: {{ $quiz->end_date->format('d/m/Y H:i') }}</li>
            </ul>
          </div>
          <div class="text-center bg-transparent card-footer border-top-0">
            <button class="btn btn-info w-100 btn-start-quiz" data-quiz-id="{{ $quiz->id }}">
              {{ in_array($quiz->id, $attemptedQuizzes->toArray()) ? 'Ver Resultados' : 'Comenzar Quiz' }}
            </button>
          </div>
        </div>
      </div>
    @endforeach
  </div>
@endif

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.btn-start-quiz').click(function(e) {
        e.preventDefault();
        const quizId = $(this).data('quiz-id');

        // Verificación vía AJAX con URL absoluta
        $.ajax({
            url: `{{ url('/quizzes') }}/${quizId}/check-access`,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    window.location.href = `/quizzes/${quizId}`;
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('Error al verificar el quiz');
            }
        });
    });
});
</script>
@endpush

