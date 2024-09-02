<!DOCTYPE html>  
<html lang="en">  
<head>  
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">  
    <title>User Form with Table</title>  
</head>  
<body>  
<div class="container mt-5">  
    <h2 class="mt-5">Employees Add</h2>  
    <form id="emp_mg">
        @csrf
        <div class="form-group">  
            <label for="name">Name:</label>  
            <input type="text" name="name" class="form-control" id="name" placeholder="Enter Name">  
        </div>  
        <div class="form-group">  
            <label for="email">Email:</label>  
            <input type="email" name="email" class="form-control" id="email" placeholder="Enter email">  
        </div>  
        <div class="form-group">  
            <label for="mob">Mob:</label>  
            <input type="number" name="mob" class="form-control" id="mob" placeholder="Enter mob">  
        </div>  
        <button type="button" id="sub" class="btn btn-primary">Submit</button>  
    </form>  

    <h2 class="mt-5">Employees Information</h2>  
    <table class="table">  
        <thead>  
            <tr>  
                <th>Name</th>  
                <th>Email</th>  
                <th>Mob</th>  
                <th>Action</th>  
            </tr>  
        </thead>  
        <tbody>  
            @foreach($employees as $employee)
            <tr id="employee-{{ $employee->id }}">
                <td>{{ $employee->name }}</td>
                <td>{{ $employee->email }}</td>
                <td>{{ $employee->mob }}</td>
                <td>
                    <button class="edit-btn" data-id="{{ $employee->id }}">Edit</button>
                    <button class="delete-btn" data-id="{{ $employee->id }}">Delete</button>
                </td>
            </tr>
            @endforeach 
        </tbody>  
    </table>  
</div>  

{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>   --}}
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>  

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script>
   $(document).ready(function(){
    // Add or Update employee
    $('#sub').click(function(){
        let employeeId = $('#emp_mg').data('id');
        let formid = $('#emp_mg').serialize();

        let url = employeeId ? `/employee/${employeeId}` : "{{ route('employee.add_employee') }}"; // updated route name

        $.ajax({
            url: url,
            type: 'POST',
            dataType: "json",
            data: formid,
            success: function(data) {
                console.log(data);

                // Show success message
                $('#message').html('<p style="color: green;">' + data.success + '</p>').fadeOut(5000);

                if(employeeId){
                    // Update the row in the table
                    let row = $(`#employee-${employeeId}`);
                    row.find('td:nth-child(1)').text(data.data.name);
                    row.find('td:nth-child(2)').text(data.data.email);
                    row.find('td:nth-child(3)').text(data.data.mob);
                } else {
                    // Add the new row to the table
                    $('tbody').append(
                        `<tr id="employee-${data.data.id}">
                            <td>${data.data.name}</td>
                            <td>${data.data.email}</td>
                            <td>${data.data.mob}</td>
                            <td>
                                <button class="edit-btn" data-id="${data.data.id}">Edit</button>
                                <button class="delete-btn" data-id="${data.data.id}">Delete</button>
                            </td>
                        </tr>`
                    );
                }

                // Clear the form and reset the data-id attribute
                $('#emp_mg').trigger('reset').removeAttr('data-id');
            },
            error: function(xhr) {
                // Show error message
                $('#message').html('<p style="color: red;">Error: ' + xhr.responseJSON.message + '</p>').fadeOut(5000);
            }
        });
    });

    // Edit employee
    $(document).on('click', '.edit-btn', function(){
        let employeeId = $(this).data('id');
        $.ajax({
            url: `/employee/${employeeId}/edit`,
            type: 'GET',
            success: function(data) {
                $('input[name="name"]').val(data.name);
                $('input[name="email"]').val(data.email);
                $('input[name="mob"]').val(data.mob);
                $('#emp_mg').attr('data-id', employeeId); // Attach ID for the update process
            }
        });
    });

    // Delete employee
    $(document).on('click', '.delete-btn', function(){
        let employeeId = $(this).data('id');
        $.ajax({
            url: `/employee/${employeeId}`,
            type: 'DELETE',
            success: function(data) {
                console.log(data);
                $(`#employee-${employeeId}`).remove(); // Remove the deleted employee from the DOM

                // Show success message for deletion
                $('#message').html('<p style="color: green;">' + data.success + '</p>').fadeOut(5000);
            },
            error: function(xhr) {
                // Show error message for deletion
                $('#message').html('<p style="color: red;">Error: ' + xhr.responseJSON.message + '</p>').fadeOut(5000);
            }
        });
    });
});

</script>
</body>  
</html>