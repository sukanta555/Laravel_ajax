<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Employee Management</title>
</head>
<body>
    <h1>Employee Management</h1>

    <!-- Form for Add/Edit employee -->
    <form id="emp_mg">
        @csrf
        <input type="text" name="name" placeholder="Name">
        <input type="text" name="email" placeholder="Email">
        <input type="text" name="mob" placeholder="Mobile">
        <button id="sub" type="button">Submit</button>
    </form>

    <h2>Employee List</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Mobile</th>
                <th>Actions</th>
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

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
            // Add or Update employee
            $('#sub').click(function(){
                let employeeId = $('#emp_mg').data('id');
                let formid = $('#emp_mg').serialize();
                
                let url = employeeId ? `/employee/${employeeId}` : "{{ route('employee.add_employee') }}"; // post router name added

                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: "json",
                    data: formid,
                    success: function(data) {
                        console.log(data);

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
                    }
                });
            });
        });
    </script>
</body>
</html>
