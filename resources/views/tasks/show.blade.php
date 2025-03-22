<x-layout :title="$task->title">
    <p><strong>Description:</strong> {{ $task->description ?? 'N/A' }}</p>
    <p><strong>Category:</strong> {{ $task->category->name }}</p>
    <p><strong>Assigned To:</strong> {{ $task->user->name }} ({{ $task->user->role->value }})</p>
    <p><strong>Created By:</strong> {{ $task->createdBy->name }} ({{ $task->createdBy->role->value }})</p>
    <p><strong>Priority:</strong> {{ $task->priority }}</p>
    <p><strong>Due Date:</strong> {{ $task->due_date ?? 'N/A' }}</p>
    <p><strong>Status:</strong> {{ $task->status }}</p>

    <h3>Comments</h3>
    <ul id="comment-list">
        @foreach ($task->comments as $comment)
            <li>
                <strong>{{ $comment->user->name }}:</strong> {{ $comment->body }}
                <small>({{ $comment->created_at->diffForHumans() }})</small>
            </li>
        @endforeach
    </ul>

    <h4>Add a Comment</h4>
    <form id="add-comment-form" method="POST" action="{{ route('comments.store', $task) }}">
        @csrf
        <div class="form-group">
            <textarea name="body" class="form-control" required></textarea>
            <div class="error-message text-danger" data-field="body"></div>
        </div>
        <button type="submit" class="btn btn-primary">Add Comment</button>
    </form>
</x-layout>


    <script>
       $(document).ready(function() {
    $('#add-comment-form').on('submit', function(e) {
        e.preventDefault(); // Prevent page reload

        $('.error-message').html(''); // Clear previous error messages

        let formData = $(this).serialize();

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            success: function(response) {
                // Create new comment element
                let newComment = `
                    <li>
                        <strong>${response.comment.user.name}:</strong> ${response.comment.body}
                        <small>(${response.comment.created_at})</small>
                    </li>
                `;

                // Append new comment
                $('#comment-list').append(newComment);

                // Clear the form
                $('#add-comment-form')[0].reset();

                // Show success message
                iziToast.success({
                    title: 'Success',
                    message: response.message,
                    position: 'topRight'
                });
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function(field, messages) {
                        $(`.error-message[data-field="${field}"]`).html(messages[0]);
                    });
                } else {
                    iziToast.error({
                        title: 'Error',
                        message: 'An unexpected error occurred.',
                        position: 'topRight'
                    });
                }
            }
        });
    });
});

    </script>
