@extends('pages.main')

@section('body')
    <div class="content-wrapper">
        <div class="content pt-3">
            <div class="container-fluid">
                <div class="card shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title mb-0 font-weight-bold">Edit Article</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('updateArticle', $article->article_code) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <!-- Article Code (readonly) -->
                            <div class="form-group">
                                <label for="article_code">Article Code:</label>
                                <input type="text" name="article_code" id="article_code"
                                    value="{{ $article->article_code }}" class="form-control" readonly>
                            </div>

                            <!-- Article Type -->
                            <div class="form-group">
                                <label for="article_type">Article Type:</label>
                                <select name="article_type" id="article_type" class="form-control" required>
                                    <option value="1" {{ $article->article_type == 1 ? 'selected' : '' }}>FAQ</option>
                                    <option value="2" {{ $article->article_type == 2 ? 'selected' : '' }}>Article
                                    </option>
                                </select>
                            </div>

                            <!-- Title -->
                            <div class="form-group">
                                <label for="title">Title:</label>
                                <input type="text" name="title" id="title" value="{{ $article->title }}"
                                    class="form-control" required>
                            </div>

                            <!-- Content -->
                            <div class="form-group">
                                <label for="content">Content:</label>
                                <textarea name="content" id="content" class="form-control" rows="5" required>{{ $article->content }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">Update Article</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
