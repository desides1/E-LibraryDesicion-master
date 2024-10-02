 <div class="modal fade" id="detailRecom" tabindex="-1" aria-labelledby="detailRecomLabel" aria-hidden="true">
     <div class="modal-dialog modal-dialog-scrollable modal-xl">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="detailRecomLabel">Detail Rekomendasi Metode
                     {{ strtoupper(substr($selectedRecom, 0, 1)) . substr($selectedRecom, 1) }}
                 </h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body">
                 <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
                     <li class="nav-item" role="presentation">
                         <a class="nav-link active" id="attribute-identification-tab" data-bs-toggle="tab"
                             href="#attribute-identification" role="tab" aria-controls="attribute-identification"
                             aria-selected="true">Identifikasi Atribut</a>
                     </li>
                     <li class="nav-item" role="presentation">
                         <a class="nav-link" id="decision-matrix-tab" data-bs-toggle="tab" href="#decision-matrix"
                             role="tab" aria-controls="decision-matrix" aria-selected="false" tabindex="-1">Matriks
                             Keputusan</a>
                     </li>
                     <li class="nav-item" role="presentation">
                         <a class="nav-link" id="matrix-normalization-tab" data-bs-toggle="tab"
                             href="#matrix-normalization" role="tab" aria-controls="matrix-normalization"
                             aria-selected="false" tabindex="-1">Normalisasi
                             Matriks</a>
                     </li>
                     <li class="nav-item" role="presentation">
                         <a class="nav-link" id="test-optimization-tab" data-bs-toggle="tab" href="#test-optimization"
                             role="tab" aria-controls="test-optimization" aria-selected="false"
                             tabindex="-1">Optimasi
                             Atribut</a>
                     </li>
                     <li class="nav-item" role="presentation">
                         <a class="nav-link" id="value-ranking-tab" data-bs-toggle="tab" href="#value-ranking"
                             role="tab" aria-controls="value-ranking" aria-selected="false"
                             tabindex="-1">Perangkingan Nilai</a>
                     </li>
                 </ul>

                 <div class="tab-content" id="myTabContent">
                     @if ($selectedRecom !== 'calculate')
                         <div class="tab-pane fade show active" id="attribute-identification" role="tabpanel"
                             aria-labelledby="attribute-identification-tab">
                             @include('pages.budget-book.recommendation.atribut')
                         </div>
                         <div class="tab-pane fade" id="decision-matrix" role="tabpanel"
                             aria-labelledby="decision-matrix-tab">
                             @include('pages.budget-book.recommendation.decision')
                         </div>
                         <div class="tab-pane fade" id="matrix-normalization" role="tabpanel"
                             aria-labelledby="matrix-normalization-tab">
                             @include('pages.budget-book.recommendation.normalization')
                         </div>
                         <div class="tab-pane fade" id="test-optimization" role="tabpanel"
                             aria-labelledby="test-optimization-tab">
                             @include('pages.budget-book.recommendation.optimization')
                         </div>
                     @endif
                     <div class="tab-pane fade" id="value-ranking" role="tabpanel" aria-labelledby="value-ranking-tab">
                         @include('pages.budget-book.recommendation.rangking')
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </div>
