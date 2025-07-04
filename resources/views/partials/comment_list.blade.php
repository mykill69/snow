<style>
    .chat-bubble {
        position: relative;
        max-width: 100%;
        display: inline-block;
    }

    /* Left arrow */
    .chat-bubble.left::before {
        content: "";
        position: absolute;
        top: 10px;
        left: -10px;
        width: 0;
        height: 0;
        border-top: 10px solid transparent;
        border-bottom: 10px solid transparent;
        border-right: 10px solid #D2D6DE;
    }

    /* Right arrow */
    .chat-bubble.right::before {
        content: "";
        position: absolute;
        top: 10px;
        right: -10px;
        width: 0;
        height: 0;
        border-top: 10px solid transparent;
        border-bottom: 10px solid transparent;
        border-left: 10px solid #0d6efd;
    }

    .chat-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin-top: 2px;
    }
</style>

@foreach ($comments as $comment)
    @php
        $commenter = $comment->admin_id
            ? \App\Models\User::find($comment->admin_id)
            : \App\Models\User::find($comment->user_id);

        $isCurrentUser = $comment->admin_id === auth()->id() || $comment->user_id === auth()->id();
        $commenterName = $isCurrentUser ? 'Me' : ($commenter ? $commenter->fname . ' ' . $commenter->lname : 'User');
        $commentTime = $comment->created_at->format('M d, h:i A');

        $bubbleClass = $isCurrentUser ? 'bg-primary text-white' : 'text-black';
        $bubbleStyle = $isCurrentUser ? '' : 'background-color: #D2D6DE;';
        $alignmentClass = $isCurrentUser ? 'justify-content-end' : 'justify-content-start';
        $nameWrapperClass = $isCurrentUser ? 'd-flex justify-content-end' : 'd-flex justify-content-start';
        $bubbleDirection = $isCurrentUser ? 'right' : 'left';
    @endphp

    <div class="mb-3">
        <!-- Display name -->
        <div class="{{ $nameWrapperClass }}">
            <div class="text-muted small mb-1">
                {{ $commenterName }}
            </div>
        </div>

        <!-- Chat bubble + icon row -->
        <div class="d-flex {{ $alignmentClass }}">
            @if ($isCurrentUser)
                <!-- ✅ Right side: bubble always 25px left of icon -->
                <div class="d-flex align-items-start justify-content-end" style="width: 100%; text-align:right;">
                    <!-- Bubble container -->
                    <div style="max-width: 60%; margin-right: 20px;">
                        <div class="p-2 rounded text-left chat-bubble {{ $bubbleClass }} right"
                            style="word-break: break-word; {{ $bubbleStyle }}">
                            {{ $comment->comments }}
                        </div>
                        <div class="text-muted small mt-1 text-end">
                            {{ $commentTime }}
                        </div>
                    </div>

                    <!-- User icon (fixed on the right) -->
                    <div class="chat-icon bg-secondary text-white d-flex justify-content-center align-items-center">
                        <i class="fas fa-user"></i>
                    </div>
                </div>
            @else
                <!-- Left side: DO NOT TOUCH (already correct) -->
                <div class="chat-icon bg-secondary text-white d-flex justify-content-center align-items-center"
                    style="margin-right: 2%;">
                    <i class="fas fa-user"></i>
                </div>
                <div class="ms-1">
                    <div class="p-2 rounded chat-bubble {{ $bubbleClass }} left" style="{{ $bubbleStyle }}">
                        {{ $comment->comments }}
                    </div>
                    <div class="text-muted small mt-1 text-start">
                        {{ $commentTime }}
                    </div>
                </div>
            @endif
        </div>


    </div>
@endforeach
