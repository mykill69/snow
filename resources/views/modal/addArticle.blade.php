<style>
    .btn-group .btn i {
        font-size: 1rem;
    }

    .btn-group .btn {
        min-width: 38px;
        height: 34px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

<div class="modal fade" id="article">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-success text-center">
                <h3 class="modal-title w-100">Add New Article</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
               <form id="submissionForm" method="POST" action="{{ route('addArticles') }}" enctype="multipart/form-data">
    @csrf

    <div class="row">
        <div class="col-md-10">
            <div class="input-group mb-2">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-pen"></i></span>
                </div>
                <input type="text" class="form-control" id="title" name="title"
                    placeholder="Article Title" required>
            </div>
        </div>

        <div class="col-md-2">
            <div class="input-group mb-2">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-list-ul"></i></span>
                </div>
                <select class="form-control" id="article_type" name="article_type" required>
                    <option value="" disabled selected>Category</option>
                    <option value="1">FAQs</option>
                    <option value="2">Article</option>
                </select>
            </div>
        </div>
    </div>

    <div class="row">
    <div class="col-md-12">
       

        <!-- Beautified button group -->
        <div class="d-flex align-items-center mb-2">
            <div class="btn-group" role="group" aria-label="Text formatting tools">
                <button 
                    type="button" 
                    class="btn btn-sm btn-outline-primary me-2" 
                    onclick="addBulletToSelection()" 
                    title="Toggle bullet">
                    <i class="fas fa-list-ul"></i>
                </button>
                <button 
                    type="button" 
                    class="btn btn-sm btn-outline-secondary me-2" 
                    onclick="addNumberingToSelection()" 
                    title="Toggle numbering">
                    <i class="fas fa-list-ol"></i>
                </button>
                <button 
                    type="button" 
                    class="btn btn-sm btn-outline-success" 
                    onclick="addParagraphIndent()" 
                    title="Toggle paragraph indent">
                    <i class="fas fa-paragraph"></i>
                </button>
            </div>
        </div>

        <!-- Textarea with icon on the left -->
        <div class="input-group mb-2">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="fas fa-book"></i>
                </span>
            </div>
            <textarea 
                id="content" 
                name="content" 
                rows="4" 
                class="form-control" 
                placeholder="Type your article content here..."></textarea>
        </div>
    </div>
</div>






    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>

            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
function addBulletToSelection() {
    const textarea = document.getElementById("content");
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const text = textarea.value;
    const selectedText = text.substring(start, end);

    if (selectedText.trim() !== "") {
        let newText;
        if (selectedText.trim().startsWith("•")) {
            newText = selectedText.replace(/^•\s*/, '');
        } else {
            newText = "• " + selectedText;
        }

        textarea.value = text.substring(0, start) + newText + text.substring(end);
        textarea.focus();
        textarea.selectionStart = start;
        textarea.selectionEnd = start + newText.length;
    }
}

function addNumberingToSelection() {
    const textarea = document.getElementById("content");
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const text = textarea.value;
    const selectedText = text.substring(start, end);

    if (selectedText.trim() !== "") {
        const lines = selectedText.split("\n");

        // Detect if already numbered
        const isNumbered = lines.every(line => /^\d+\.\s/.test(line.trim()));

        let newLines;
        if (isNumbered) {
            // Remove numbering
            newLines = lines.map(line => line.replace(/^\d+\.\s*/, ''));
        } else {
            // Add numbering
            newLines = lines.map((line, i) => `${i + 1}. ${line}`);
        }

        const newText = newLines.join("\n");
        textarea.value = text.substring(0, start) + newText + text.substring(end);
        textarea.focus();
        textarea.selectionStart = start;
        textarea.selectionEnd = start + newText.length;
    }
}

function addParagraphIndent() {
    const textarea = document.getElementById("content");
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const text = textarea.value;
    const selectedText = text.substring(start, end);

    if (selectedText.trim() !== "") {
        let newText;
        if (selectedText.startsWith("\t") || selectedText.startsWith("    ")) {
            // Remove indent
            newText = selectedText.replace(/^(\t| {4})/, '');
        } else {
            // Add indent
            newText = "\t" + selectedText;
        }

        textarea.value = text.substring(0, start) + newText + text.substring(end);
        textarea.focus();
        textarea.selectionStart = start;
        textarea.selectionEnd = start + newText.length;
    }
}
</script>



<script>
    document.getElementById('password').addEventListener('input', function() {
        const password = this.value;
        const errorElement = document.getElementById('passwordError');
        if (password.length < 8) {
            errorElement.style.display = 'block'; // Show error message
        } else {
            errorElement.style.display = 'none'; // Hide error message
        }
    });
</script>
