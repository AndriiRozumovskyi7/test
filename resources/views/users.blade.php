<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .user-card {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center">User List</h1>

    <div class="text-center mb-4">
        <button id="get-token-btn" class="btn btn-info">
            Get Token
        </button>
        <p id="token-display" class="mt-3"></p>
    </div>

    <div class="text-center mb-4">
        <button id="add-user-btn" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addUserModal">
            Add User
        </button>
    </div>

    <div class="row" id="user-list">
    </div>

    <div class="text-center mt-4">
        <button id="show-more" class="btn btn-primary">Show More</button>
    </div>
</div>

<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="add-user-form" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone" required>
                    </div>
                    <div class="mb-3">
                        <label for="position" class="form-label">Position</label>
                        <select class="form-select" id="position" name="position_id" required>
                            <option value="">Select Position</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="photo" class="form-label">Photo</label>
                        <input type="file" class="form-control" id="photo" name="photo" accept="image/*" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add User</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    let currentPage = 1;
    let totalPages = 5;
    let csrfToken = '';

    $('#get-token-btn').click(function () {
        $.ajax({
            url: '/api/token',
            type: 'GET',
            success: function (data) {
                if (data.success) {
                    csrfToken = data.token;
                    $('#token-display').text('Token: ' + csrfToken);
                } else {
                    alert("Failed to retrieve token");
                }
            },
            error: function () {
                alert("An error occurred while fetching the token.");
            }
        });
    });

    function loadPositions() {
        $.ajax({
            url: '/api/positions',
            type: 'GET',
            success: function (data) {
                const positions = data.positions;
                $('#position').empty();
                $('#position').append('<option value="">Select Position</option>');

                positions.forEach(position => {
                    $('#position').append(`<option value="${position.id}">${position.name}</option>`);
                });
            }
        });
    }

    function loadUsers(page) {
        $.ajax({
            url: '/api/users?count=6&page=' + page,
            type: 'GET',
            success: function (data) {
                const users = data.users;
                totalPages = data.total_pages;

                users.forEach(user => {
                    const userCard = `
                        <div class="col-md-4 user-card">
                            <div class="card">
                                <img src="/storage/${user.photo}" class="card-img-top" alt="User Photo">
                                <div class="card-body">
                                    <h5 class="card-title">${user.name}</h5>
                                    <p class="card-text">${user.email}</p>
                                    <p class="card-text">${user.phone}</p>
                                    <p class="card-text">${user.position}</p>
                                </div>
                            </div>
                        </div>
                    `;
                    $('#user-list').append(userCard);
                });

                if (page >= totalPages) {
                    $('#show-more').hide();
                }
            }
        });
    }

    $(document).ready(function () {
        loadUsers(currentPage);
        loadPositions();

        $('#show-more').click(function () {
            if (currentPage < totalPages) {
                currentPage++;
                loadUsers(currentPage);
            }
        });

        $('#add-user-form').submit(function (event) {
            event.preventDefault();

            if (!csrfToken) {
                alert("Token is not available.");
                return;
            }

            let formData = new FormData(this);

            $.ajax({
                url: '/api/users?token=' + csrfToken,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.success) {
                        $('#addUserModal').modal('hide');
                        $('#user-list').empty();
                        loadUsers(1);
                        alert('User added successfully!');
                    }
                },
                error: function () {
                    alert('An error occurred while adding the user.');
                }
            });
        });
    });
</script>

</body>
</html>
