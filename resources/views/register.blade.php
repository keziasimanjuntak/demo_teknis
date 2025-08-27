<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h1>Register User</h1>

    <form id="registerForm">
        <input type="text" name="name" placeholder="Name" required><br><br>
        <input type="email" name="email" placeholder="Email" required><br><br>

        <select name="gender" required>
            <option value="">Select Gender</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
        </select><br><br>

        <input type="password" name="password" placeholder="Password" required><br><br>

        <button type="submit">Register</button>
    </form>

    <hr>

    <h2>Registered Users</h2>
    <ul id="userList"></ul>

    <script>
        const form = document.getElementById('registerForm');
        const userList = document.getElementById('userList');

        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(form);

            const data = {
                name: formData.get('name'),
                email: formData.get('email'),
                gender: formData.get('gender'),
                password: formData.get('password'),
            };

            console.log("Data yang dikirim:", data);
            
            const response = await fetch('/api/users', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(data)
            });

            if (response.ok) {
                form.reset();
                loadUsers();
            } else {
                const error = await response.json();
                alert("Registration failed:\n" + JSON.stringify(error.errors));
            }
        });

        async function loadUsers() {
            const response = await fetch('/api/users');
            const users = await response.json();

            userList.innerHTML = '';
            users.forEach(user => {
                const li = document.createElement('li');
                li.innerText = `${user.name} - ${user.email} (${user.gender})`;
                userList.appendChild(li);
            });
        }

        // Load users saat halaman dibuka
        loadUsers();
    </script>
</body>
</html>
