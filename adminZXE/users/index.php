<!DOCTYPE html>
<?php
include '../../bot/config.php';
include '../../bot/functions.php';
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        @font-face {
            font-family: 'CustomFont';
            src: url('./CustomFont.woff2') format('woff2');
        }
        body {
            font-family: 'CustomFont', sans-serif;
        }
        .select-button {
            transition: background-color 0.3s ease;
        }
        .select-button:hover {
            background-color: #2563eb;
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8 text-center">User Management</h1>
        <div class="bg-white shadow-md rounded-lg p-6 mb-8">
            <input type="text" placeholder="Search by UserID | Name | Username" class="w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 text-center" id="searchInput">
        </div>
        <div class="bg-white shadow-md rounded-lg p-6 text-center">
            <h2 class="text-xl font-semibold text-gray-700 mb-4 text-center">User List</h2>
            <div id="userList">
                <!-- User Item template (example) -->
                <!-- This will be populated dynamically -->
            </div>
        </div>
    </div>


<script>


//          close keyboard when clicked on empty area           //
document.addEventListener('DOMContentLoaded', function() {
    document.body.addEventListener('click', function(event) {
        var isInputOrTextarea = event.target.tagName.toLowerCase() === 'input' || event.target.tagName.toLowerCase() === 'textarea';
        
        if (!isInputOrTextarea) {
            var activeElement = document.activeElement;
            if (activeElement && (activeElement.tagName.toLowerCase() === 'input' || activeElement.tagName.toLowerCase() === 'textarea')) {
                activeElement.blur();
            }
        }
    });
});


document.getElementById('searchInput').addEventListener('input', function() {
    const query = this.value.trim();
    if (query.length > 0) {
        fetch(`api.php?q=${query}`)
            .then(response => response.json())
            .then(data => {
                const userList = document.getElementById('userList');
                userList.innerHTML = '';
                let userGroup = document.createElement('div');
                userGroup.className = 'grid grid-cols-1 md:grid-cols-3 gap-4';
                data.forEach((user, index) => {
                    const userItem = document.createElement('div');
                    userItem.className = 'flex flex-col justify-between bg-gray-50 p-4 mb-4 rounded-md user-item';
                    userItem.innerHTML = `
                        <div>
                            <strong>Name: </strong><span class="text-gray-500 user-id">${user.first_name}</span>
                            <br>
                            <strong>ID: </strong><span class="text-gray-500 user-id">${user.id}</span>
                            <br>
                            ${user.username ? `<strong>Username: </strong><span class="text-gray-500 user-id">${user.username}</span>` : '<strong>Username: </strong><span class="text-gray-500 user-id">-</span>'}
                            <br>
                            <strong>Language: </strong><span class="text-gray-500 user-id">${user.language_code}</span>
                            <br>
                            <strong>Balance: </strong><span class="text-gray-500 user-id">${user.balance}</span>
                        </div>
                    `;
                    userItem.addEventListener('click', function() {
                        window.location.href = `./manage/?q=${user.id}`;
                    });

                    userGroup.appendChild(userItem);

                    if ((index + 1) % 3 === 0 || index === data.length - 1) {
                        userList.appendChild(userGroup);
                        userGroup = document.createElement('div');
                        userGroup.className = 'grid grid-cols-1 md:grid-cols-3 gap-4';
                    }
                });
            })
            .catch(error => {
                console.error('Error fetching users:', error);
            });
    } else {
        document.getElementById('userList').innerHTML = '';
    }
});


        document.getElementById('userList').addEventListener('click', function(event) {
            if (event.target.classList.contains('select-button')) {
                const userId = event.target.getAttribute('data-user-id');
                window.location.href = `./manage/?q=${userId}`;
            }
        });
    </script>
</body>
</html>
