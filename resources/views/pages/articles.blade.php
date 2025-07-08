<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.css" rel="stylesheet">

@extends('pages.main')

<style>
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #007bff !important;
        border-color: #187744 !important;
        color: #fff;
        padding: 0 10px;
        margin-top: 0.31rem;
    }
    
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
    <div class="content-wrapper">
        <div class="content pt-3">
            <div class="container-fluid">
                <div class="row mb-4">
                    <!-- Main Article Table (now on the left) -->
                    <div class="col-md-9">
                        <div class="card shadow-sm">
                            <div class="card-header bg-white border-bottom">
                                <h5 class="card-title mb-0 font-weight-bold">All Articles Uploaded</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example1" class="table table-bordered table-hover text-sm text-center mb-0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Article Code</th>
                                                <th>Title</th>
                                                <th>Category</th>
                                                <th>Uploaded By</th>
                                                <th>Date Uploaded</th>
                                            </tr>
                                        </thead>
                                        <tbody>
    @foreach ($articles as $article)
        <tr>
            <td>
                <a href="#"
                   class="text-primary text-decoration-none swalDefaultInfo"
                   data-title="{{ $article->title }}"
                   data-content="{{ strip_tags($article->content) }}"
                   data-code="{{ $article->article_code }}"
                   data-author="{{ $article->admin->fname ?? 'Unknown' }} {{ $article->admin->lname ?? '' }}"
                   data-date="{{ $article->created_at->format('M d, Y') }}">
                    {{ $article->article_code }}
                </a>
            </td>
            <td>{{ $article->title }}</td>
            <td>
                @if ($article->article_type == 1)
                    <span class="badge badge-primary">FAQs</span>
                @elseif ($article->article_type == 2)
                    <span class="badge badge-success">Article</span>
                @else
                    <span class="badge badge-secondary">Unknown</span>
                @endif
            </td>
            <td>{{ $article->admin->fname ?? 'N/A' }} {{ $article->admin->lname ?? '' }}</td>
            <td>{{ $article->created_at->format('M d, Y h:i A') }}</td>
        </tr>
    @endforeach
</tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar Panel (now on the right) -->
                    <div class="col-md-3">
                        <div class="card shadow-sm">
                            <div class="card-header text-center font-weight-bold text-dark">
                                Article Panel
                            </div>
                            <div class="card-body text-center">
                                <p class="mb-3">Manage and upload new articles here.</p>
                                <button class="btn btn-success btn-block" data-toggle="modal" data-target="#article">
                                    <i class="fas fa-plus mr-1"></i> Upload Article
                                </button>
                            </div>
                        </div>
                    </div>
                </div> <!-- /.row -->
            </div> <!-- /.container-fluid -->
        </div> <!-- /.content -->
    </div> <!-- /.content-wrapper -->
    @if ($errors->any())
        <div id="error-message" class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <div class="float-right d-none d-sm-block">
            <b>Version</b> 1.0.0
        </div>
        <i>Maintained and Managed by Management Information System Office. All rights reserved.</i>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Add Content Here -->
    </aside>
    <!-- /.control-sidebar -->

    <!-- /.row -->
    </div><!--/. container-fluid -->
    </section>

   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="{{ asset('template/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
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
    
    </body>

    </html>


    @include('modal.addArticle')
@endsection
