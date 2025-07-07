@extends('access.layout')

<style>
    .swal2-article-popup {
        font-family: 'Segoe UI', sans-serif;
        font-size: 1rem;
        text-align: left;
        line-height: 1.6;
    }

    .swal-article-title {
        font-size: 1.5rem;
        font-weight: bold;
        color: #2c3e50;
        border-bottom: 2px solid #f4f4f4;
        padding-bottom: 0.5rem;
    }

    .swal-article-content {
        max-height: 400px;
        overflow-y: auto;
        white-space: pre-line;
        font-family: 'Segoe UI', sans-serif;
        font-size: 1rem;
        color: #333;
    }

    .swal-article-content::-webkit-scrollbar {
        width: 6px;
    }

    .swal-article-content::-webkit-scrollbar-thumb {
        background-color: #ccc;
        border-radius: 4px;
    }

    .swal-article-meta {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        font-size: 0.9rem;
        color: #666;
    }
    .swal2-article-popup .swal-article-body {
        text-align: left;
    }

    .swal-article-title {
        font-weight: bold;
        font-size: 1.4rem;
    }

    .swal-article-content {
        font-size: 1rem;
        line-height: 1.6;
        white-space: pre-wrap;
    }

    .swal-article-meta {
        font-size: 0.9rem;
        color: #666;
    }
</style>


@section('body')
    <div class="content-wrapper" style="background-color: white;">
        <div class="content" style="padding-top: 1%;">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-md-9">
                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-md-5">
                                <div class="card mb-4">
                                    <div class="card-header font-weight-bold text-dark" style="background-color: #f0f0f0;">
                                        Sources
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item"><a href="#"
                                                    class="text-decoration-none text-dark">All</a></li>
                                            <li class="list-group-item"><a href="#"
                                                    class="text-decoration-none text-dark">Knowledge Base Articles</a></li>
                                            <li class="list-group-item"><a href="#"
                                                    class="text-decoration-none text-dark">Frequently Asked Questions</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Filters Label -->
                                <h6 class="font-weight-bold mb-2">Filters</h6>

                                <!-- Filters Box -->
                                <div class="card">
                                    <div class="card-header font-weight-bold text-dark" style="background-color: #f0f0f0;">
                                        Basis
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-group list-group-flush">
                                            <!-- Author -->
                                            <li class="list-group-item">
                                                <label for="filterAuthor" class="small font-weight-bold">Author</label>
                                                <select class="form-control">
                                                    <option value="">Select Author</option>
                                                    @foreach ($authors as $author)
                                                        <option value="{{ $author->id }}">{{ $author->fname }}
                                                            {{ $author->lname }}</option>
                                                    @endforeach
                                                </select>
                                            </li>

                                            <!-- Category -->
                                            <li class="list-group-item">
                                                <label for="filterCategory" class="small font-weight-bold">Category</label>
                                                <select id="filterCategory" class="form-control form-control-sm">
                                                    <option selected disabled>Select Category</option>
                                                    <option value="1">Frequently Asked Questions</option>
                                                    <option value="2">Knowledge Base Articles</option>
                                                </select>
                                            </li>

                                            <!-- Date Uploaded -->
                                            <li class="list-group-item">
                                                <label for="filterDate" class="small font-weight-bold">Date Uploaded</label>
                                                <input type="date" id="filterDate" class="form-control form-control-sm">
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="col-md-7 mb-5">
                                <div class="card">
                                    <div class="card-header font-weight-bold text-dark" style="background-color: #f0f0f0;">
                                        These articles might help you with what you're looking for.
                                    </div>

                                    <div class="card-body">
                                        @foreach ($articles as $article)
                                            <div class="mb-3 pb-2 border-bottom">
                                                <!-- Title -->
                                                <a href="#" class="swalDefaultInfo d-block text-decoration-none"
                                                    data-title="{{ $article->title }}"
                                                    data-content="{{ strip_tags($article->content) }}"
                                                    data-code="{{ $article->article_code }}"
                                                    data-author="{{ $article->admin->fname ?? 'Unknown' }}"
                                                    data-date="{{ $article->created_at->format('M d, Y') }}">

                                                    <h5 class="text-primary font-weight-bold mb-2">
                                                        <i class="fas fa-book mr-2"></i> {{ $article->title }}
                                                    </h5>
                                                </a>

                                                <!-- Footer Metadata -->
                                                <div class="text-muted small d-flex flex-wrap justify-content-between">
                                                    <span><strong>Article:</strong>
                                                        {{ $article->article_code ?? 'Unknown' }}</span>
                                                    <span><strong>Published by:</strong>
                                                        {{ $article->admin->fname ?? 'Unknown' }}</span>
                                                    <span><strong>Date:</strong>
                                                        {{ $article->created_at->format('M d, Y') }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>


                                    <!-- Pagination -->
                                    <div class="card-footer d-flex justify-content-center">
                                        {{ $articles->links('pagination::bootstrap-4') }}
                                    </div>
                                </div>
                            </div>



                        </div> <!-- end inner row -->
                    </div>
                </div> <!-- end center row -->
            </div>
        </div>
    </div>



    <!-- Fixed Footer -->
    <footer class="text-muted text-center bg-white py-2"
        style="position: fixed; left: 0; bottom: 0; width: 100%; z-index: 999;">
        <div class="float-right d-none d-sm-block pr-2">
            <b>Version</b> 1.0.0
        </div>
        <div class="float-left d-none d-sm-block pl-2">
            <i>Maintained and Managed by Management Information System Office. All rights reserved.</i>
        </div>
    </footer>

    <script src="template/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="{{ asset('template/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('template/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

    <script>
        $(function() {
            $(document).on('click', '.swalDefaultInfo', function(e) {
                e.preventDefault();

                const title = $(this).data('title');
                const content = $(this).data('content');
                const code = $(this).data('code');
                const author = $(this).data('author');
                const date = $(this).data('date');

                const htmlContent = `
            <div class="swal-article-body">
                <h4 class="swal-article-title mb-3">${title}</h4>
                <div class="swal-article-content mb-4">${escapeHtml(content)}</div>
                <hr>
                <div class="swal-article-meta text-muted small">
                    <div><strong>Article Code:</strong> ${code}</div>
                    <div><strong>Published by:</strong> ${author}</div>
                    <div><strong>Date:</strong> ${date}</div>
                </div>
            </div>
        `;

                Swal.fire({
                    html: htmlContent,
                    showConfirmButton: false,
                    width: '720px',
                    padding: '2rem',
                    customClass: {
                        popup: 'swal2-article-popup'
                    }
                });
            });

            // Escape unsafe HTML, preserve basic formatting
            function escapeHtml(str) {
                return String(str)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/\n/g, '<br>') // preserve line breaks
                    .replace(/  /g, '&nbsp;&nbsp;'); // preserve spacing
            }
        });
    </script>




    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('commentContainer');
            container.scrollTop = container.scrollHeight;
        });
    </script>

    <script>
        $(function() {
            bsCustomFileInput.init();
        });
    </script>
@endsection
