<x-layout title="Edit Task">
    <form id="edit-task-form" method="POST" action="{{ route('tasks.update', $task) }}">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" class="form-control" value="{{ $task->title }}" required>
            <div class="error-message text-danger" data-field="title"></div>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" class="form-control">{{ $task->description }}</textarea>
            <div class="error-message text-danger" data-field="description"></div>
        </div>
        <div class="form-group">
            <label for="category_id">Category</label>
            <select name="category_id" class="form-control" required>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ $task->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
            <div class="error-message text-danger" data-field="category_id"></div>
        </div>
        <div class="form-group">
            <label for="user_id">Assign To</label>
            <select name="user_id" class="form-control" required>
                @foreach ($assignableUsers as $assignableUser)
                    <option value="{{ $assignableUser->id }}" {{ $task->user_id == $assignableUser->id ? 'selected' : '' }}>{{ $assignableUser->name }} ({{ $assignableUser->role->value }})</option>
                @endforeach
            </select>
            <div class="error-message text-danger" data-field="user_id"></div>
        </div>
        <div class="form-group">
            <label for="priority">Priority</label>
            <select name="priority" class="form-control" required>
                <option value="low" {{ $task->priority == 'low' ? 'selected' : '' }}>Low</option>
                <option value="medium" {{ $task->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                <option value="high" {{ $task->priority == 'high' ? 'selected' : '' }}>High</option>
            </select>
            <div class="error-message text-danger" data-field="priority"></div>
        </div>
        <div class="form-group">
            <label for="due_date">Due Date</label>
            <input type="date" name="due_date" class="form-control" value="{{ $task->due_date }}">
            <div class="error-message text-danger" data-field="due_date"></div>
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" class="form-control">
                <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
            <div class="error-message text-danger" data-field="status"></div>
        </div>
        <button type="submit" class="btn btn-primary">Update Task</button>
    </form>
</x-layout>


    <script>
       $(document).ready(function() {
    $('#edit-task-form').on('submit', function(e) {
        e.preventDefault(); // Prevent page reload

        // Clear previous error messages
        $('.error-message').html('');

        let formData = $(this).serialize(); 

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST', // Use POST, but explicitly send _method=PUT
            data: formData + '&_method=PUT', // Append _method=PUT
            success: function(response) {
                iziToast.success({
                    title: 'Success',
                    message: response.message,
                    position: 'topRight'
                });

                // Redirect to tasks index after a short delay
                setTimeout(function() {
                    window.location.href = "{{ route('tasks.index') }}";
                }, 1500);
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
