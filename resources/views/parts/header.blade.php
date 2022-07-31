<nav class="navbar navbar-dark bg-dark sticky-top">
    <div class="container-fluid">
        <a href="./" class="text-decoration-none text-light">
            <h1 class="m-0">再翻訳河原</h1>
        </a>
        <div>
            <button class="btn btn-secondary mx-2" id="" type="button" data-bs-toggle="modal"
                data-bs-target="#originTextModal">元の文章を見る</button>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasMenu"
                aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation"
                style="border-color: transparent">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </div>
</nav>

<div class="offcanvas offcanvas-start text-bg-dark" tabindex="-1" id="offcanvasMenu"
    aria-labelledby="offcanvasMenuLabel" data-bs-scroll="true">
    <div class="offcanvas-header pb-1">
        <h5 class="offcanvas-title fs-3 fw-bold" id="offcanvasMenuLabel">再翻訳河原 とは</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="閉じる"></button>
    </div>
    <div class="offcanvas-body pt-1 d-flex flex-column justify-content-between">
        <div class=>
            <hr>
            <p class="py-1">元の文章から、繰り返し再翻訳をし、その過程を眺めるだけのWebサイトです。</p>
            <p class="py-1">日本語 → 他言語 → 日本語 → 他言語 → … の順に翻訳を繰り返します。</p>
            <p class="py-1">毎日 0時 0分 に文章を更新します。</p>
            <p class="py-1">更新は 1分毎 に自動的に取得されます。</p>
        </div>
        <div>
            <small class="d-flex justify-content-end text-white-50">
                &copy; 2022 Flounder3dge All rights reserved.
            </small>
        </div>
    </div>
</div>

<div class="modal fade" id="originTextModal" tabindex="-1" aria-labelledby="originTextModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="originTextModalLabel">元の文章</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>{!! $originText !!}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
            </div>
        </div>
    </div>
</div>
