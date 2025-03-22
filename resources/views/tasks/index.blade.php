<x-layout title="Tasks">
    <table class="table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Category</th>
                <th>Assigned To</th>
                <th>Created By</th>
                <th>Priority</th>
                <th>Due Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tasks as $task)
                <tr>
                    <td>{{ $task->title }}</td>
                    <td>{{ $task->category->name }}</td>
                    <td>{{ $task->user->name }} ({{ $task->user->role->value }})</td>
                    <td>{{ $task->createdBy->name }} ({{ $task->createdBy->role->value }})</td>
                    <td>{{ $task->priority }}</td>
                    <td>{{ $task->due_date }}</td>
                    <td>{{ $task->status }}</td>
                    <td>
    <a href="{{ route('tasks.show', $task) }}" class="btn btn-info btn-sm">View</a>
    @if (Auth::user()->id === $task->created_by && (Auth::user()->isAdmin() || Auth::user()->isManager()))
        <a href="{{ route('tasks.edit', $task) }}" class="btn btn-primary btn-sm">Edit</a>
        <button class="btn btn-danger btn-sm delete-task" data-id="{{ $task->id }}">Delete</button>
    @endif
</td>


                </tr>
            @endforeach
        </tbody>
    </table>
</x-layout>
<script>
    $(document).ready(function() {
        $('.delete-task').click(function() {
            let taskId = $(this).data('id');
            let row = $(this).closest('tr');

            iziToast.question({
                timeout: 20000,
                close: false,
                overlay: true,
                displayMode: 'once',
                title: 'Confirm Delete',
                message: 'Are you sure you want to delete this task?',
                position: 'center',
                buttons: [
                    ['<button><b>Yes</b></button>', function (instance, toast) {
                        $.ajax({
                            url: "{{ url('tasks') }}/" + taskId, // Laravel delete route
                            method: 'POST', // Use POST with _method=DELETE
                            data: {
                                _token: "{{ csrf_token() }}",
                                _method: 'DELETE'
                            },
                            success: function(response) {
                                iziToast.success({
                                    title: 'Deleted',
                                    message: response.message,
                                    position: 'topRight'
                                });
                                row.fadeOut(500, function() {
                                    $(this).remove();
                                });
                            },
                            error: function(xhr) {
                                iziToast.error({
                                    title: 'Error',
                                    message: 'Failed to delete the task.',
                                    position: 'topRight'
                                });
                            }
                        });

                        instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
                    }, true], // Yes button

                    ['<button>No</button>', function (instance, toast) {
                        instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
                    }] // No button
                ]
            });
        });
    });
</script>
