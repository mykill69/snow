@extends('access.layout')
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
                                            @php
                                                $plainTextContent = strip_tags($article->content);
                                                $isLong = strlen($plainTextContent) > 230;
                                            @endphp

                                            <div class="mb-2 border-bottom"
                                                style="height: 150px; overflow: hidden; position: relative;">
                                                <!-- Title -->
                                                <div class="mb-1">
                                                    <h6 class="text-primary font-weight-bold mb-1">
                                                        <i class="fas fa-book mr-2"></i> {{ $article->title }}
                                                    </h6>
                                                </div>

                                                <!-- Content -->
                                                <p class="text-muted mb-2"
                                                    style="max-height: 95px; overflow: hidden; text-overflow: ellipsis;">
                                                    {!! nl2br(e(Str::limit($plainTextContent, 230, '...'))) !!}
                                                    @if ($isLong)
                                                        <a href="#" class="text-primary small">See more</a>
                                                    @endif
                                                </p>

                                                <!-- Metadata -->
                                                <div class="position-absolute w-100" style="bottom: 0;">
                                                    <div class="small text-muted d-flex justify-content-between pt-2">
                                                        <span>Article:
                                                            <strong>{{ $article->article_code ?? 'Unknown' }}</strong></span>
                                                        <span>Published by:
                                                            <strong>{{ $article->admin->fname ?? 'Unknown' }}</strong></span>
                                                        <span>Date:
                                                            <strong>{{ $article->created_at->format('M d, Y') }}</strong></span>
                                                    </div>
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
