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
                                                        <a href="#" class="text-primary text-decoration-none">
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

                                                    <td>{{ $article->admin->fname ?? 'N/A' }}
                                                        {{ $article->admin->lname ?? '' }}</td>
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

    <!-- AdminLTE for demo purposes -->
    <script src="template/dist/js/demo.js"></script>
    <!-- jQuery -->
    <script src="template/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="template/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- ChartJS -->
    <script src="template/plugins/chart.js/Chart.min.js"></script>
    <script src="template/plugins/chart.js/Chart.js"></script>
    <!-- AdminLTE App -->
    <script src="template/dist/js/adminlte.min.js"></script>



<!-- jQuery and Summernote JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.js"></script>


    </body>

    </html>


    @include('modal.addArticle')
@endsection
