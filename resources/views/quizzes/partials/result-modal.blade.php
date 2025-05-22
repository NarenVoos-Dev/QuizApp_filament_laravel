<div class="modal fade" id="results-modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Resultado del Quiz</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="results-content"></div>
            <div class="modal-footer">
                <a href="{{ route('quizzes.index') }}" class="btn btn-outline-primary">Volver a Quizzes</a>
            </div>
        </div>
    </div>
</div>