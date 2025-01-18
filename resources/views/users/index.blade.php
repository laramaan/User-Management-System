<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h1 class="mb-4">User Management</h1>

        <!-- Button to create a new user -->
        <div class="mb-3">
            <button class="btn btn-primary" onclick="showCreateUserForm()">Create New User</button>
        </div>

        <!-- User table -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <!-- Update button -->
                            <button class="btn btn-warning btn-sm"
                                onclick="showUpdateUserForm({{ $user }})">Update</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal for create/update user -->
    <div class="modal" id="userModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Success and Error Messages Section -->
                <div id="successMessage" class="alert alert-success d-none" role="alert"></div>
                <div id="errorMessages" class="alert alert-danger d-none" role="alert">
                    <ul id="errorList"></ul>
                </div>

                <form id="userForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Create User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="userId">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" id="name" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3" id="passwordField">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" id="password" name="password" class="form-control">
                        </div>
                        <div class="mb-3" id="passwordConfirmationField">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Show Create User Form
        function showCreateUserForm() {
            document.getElementById('modalTitle').innerText = 'Create User';
            document.getElementById('userId').value = '';
            document.getElementById('name').value = '';
            document.getElementById('email').value = '';
            document.getElementById('password').value = '';
            document.getElementById('password_confirmation').value = '';
            document.getElementById('passwordField').style.display = 'block';
            document.getElementById('passwordConfirmationField').style.display = 'block';
            document.getElementById('password').disabled = false;
            document.getElementById('password_confirmation').disabled = false;
            const modal = new bootstrap.Modal(document.getElementById('userModal'));
            modal.show();
        }

        // Show Update User Form
        function showUpdateUserForm(user) {
            document.getElementById('modalTitle').innerText = 'Update User';
            document.getElementById('userId').value = user.id;
            document.getElementById('name').value = user.name;
            document.getElementById('email').value = user.email;
            document.getElementById('passwordField').style.display = 'none';
            document.getElementById('passwordConfirmationField').style.display = 'none';
            document.getElementById('password').disabled = true;
            document.getElementById('password_confirmation').disabled = true;
            const modal = new bootstrap.Modal(document.getElementById('userModal'));
            modal.show();
        }

        // Submit User Form
        document.getElementById('userForm').addEventListener('submit', async function(event) {
            event.preventDefault();

            const userId = document.getElementById('userId').value;
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;

            let payload = {
                name,
                email,
            };

            // Include password fields only for creating a new user
            if (!userId) {
                const password = document.getElementById('password').value;
                const passwordConfirmation = document.getElementById('password_confirmation').value;
                payload = {
                    ...payload,
                    password,
                    password_confirmation: passwordConfirmation,
                };
            }

            // Determine API endpoint and method
            const apiUrl = userId ?
                `api/v1/user/update/${userId}` // Laravel Update API route
                :
                '/api/v1/user/create'; // Laravel Create API route
            const method = userId ? 'PUT' : 'POST';

            try {
                const response = await fetch(apiUrl, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(payload),
                });

                const data = await response.json();

                if (response.ok) {
                    clearErrors();
                    displaySuccessMessage(data.message || 'Operation successful');
                    location.reload(); // Reload to fetch updated data
                } else {
                    displayErrors(data.errors || {
                        message: data.message || 'An error occurred',
                    });
                }
            } catch (error) {
                displayErrors({
                    message: 'Failed to communicate with the server',
                });
            }
        });

        // Function to Display Success Message
        function displaySuccessMessage(message) {
            const successMessage = document.getElementById('successMessage');
            successMessage.textContent = message;
            successMessage.classList.remove('d-none');
        }

        // Function to Display Errors on Frontend
        function displayErrors(errors) {
            const errorMessages = document.getElementById('errorMessages');
            const errorList = document.getElementById('errorList');

            errorList.innerHTML = ''; // Clear previous errors

            if (typeof errors === 'object') {
                Object.values(errors).forEach((error) => {
                    const li = document.createElement('li');
                    li.textContent = Array.isArray(error) ? error[0] : error;
                    errorList.appendChild(li);
                });
            } else {
                const li = document.createElement('li');
                li.textContent = errors;
                errorList.appendChild(li);
            }

            errorMessages.classList.remove('d-none');
        }

        // Function to Clear Errors
        function clearErrors() {
            const errorMessages = document.getElementById('errorMessages');
            const errorList = document.getElementById('errorList');

            errorList.innerHTML = '';
            errorMessages.classList.add('d-none');
        }
    </script>


</body>

</html>
