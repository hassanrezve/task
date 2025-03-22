<x-layout title="Create Task">
    <form id="create-task-form" method="POST" action="{{ route('tasks.store') }}">
        @csrf
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" class="form-control" required>
            <div class="error-message text-danger" data-field="title"></div>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" class="form-control"></textarea>
            <div class="error-message text-danger" data-field="description"></div>
        </div>
        <div class="form-group">
            <label for="category_id">Category</label>
            <select name="category_id" class="form-control" required>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
            <div class="error-message text-danger" data-field="category_id"></div>
        </div>
        <div class="form-group">
            <label for="user_id">Assign To</label>
            <select name="user_id" class="form-control" required>
                @foreach ($assignableUsers as $assignableUser)
                    <option value="{{ $assignableUser->id }}">{{ $assignableUser->name }} ({{ $assignableUser->role->value }})</option>
                @endforeach
            </select>
            <div class="error-message text-danger" data-field="user_id"></div>
        </div>
        <div class="form-group">
            <label for="priority">Priority</label>
            <select name="priority" class="form-control" required>
                <option value="low">Low</option>
                <option value="medium">Medium</option>
                <option value="high">High</option>
            </select>
            <div class="error-message text-danger" data-field="priority"></div>
        </div>
        <div class="form-group">
            <label for="due_date">Due Date</label>
            <input type="date" name="due_date" class="form-control">
            <div class="error-message text-danger" data-field="due_date"></div>
        </div>
        <button type="submit" class="btn btn-primary">Create Task</button>
    </form>
    
</x-layout>

<script>
         $(document).ready(function() {
            $('#create-task-form').on('submit', function(e) {
                e.preventDefault(); // Prevent page reload

                // Clear previous error messages
                $('.error-message').html(''); // Clear all error messages

                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        // Show success message with iziToast
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
                        // Handle validation errors
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(field, messages) {
                                // Find the error div for the field and display the first error message
                                $(`.error-message[data-field="${field}"]`).html(messages[0]);
                            });
                        } else {
                            // Show generic error with iziToast
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


