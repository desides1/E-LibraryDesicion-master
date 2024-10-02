<div class="modal fade" id="listuserModal" tabindex="-1" aria-labelledby="listuserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="listuserModalLabel">Daftar Usulan Pemustaka</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <input type="text" class="form-control" id="name" placeholder="Cari Nama Pemustaka"
                            name="name" oninput="(() => { restrictInputSearch(this); })()">
                    </div>
                </div>
                <div id="publisher" data-id="{{ encrypt($item->id) }}"></div>
                <div class="mt-4 list-user">
                    @include('pages.front.request_book.show_pagination')
                </div>
            </div>
        </div>
    </div>
</div>

@include('pages.front.request_book.dist.search-handler')