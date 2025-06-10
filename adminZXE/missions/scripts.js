document.addEventListener('DOMContentLoaded', function () {
    loadMissions();

    document.getElementById('addMissionBtn').addEventListener('click', function () {
        Swal.fire({
            title: 'Add Mission',
            html: `
                <input type="text" id="missionName" class="swal2-input" placeholder="Mission Name" autocomplete="off" required>
                <input type="number" id="missionReward" class="swal2-input" placeholder="Mission Reward" autocomplete="off" min="0" required>
                <textarea id="missionDescription" class="swal2-textarea" placeholder="Mission Description" autocomplete="off" required></textarea>
            `,
            confirmButtonText: 'Add',
            preConfirm: () => {
                const name = document.getElementById('missionName').value.trim();
                const reward = document.getElementById('missionReward').value.trim();
                const description = document.getElementById('missionDescription').value.trim();

                if (!name || reward === "" || !description) {
                    Swal.showValidationMessage('All fields are required and must not be empty');
                    return false;
                }

                if (reward < 0) {
                    Swal.showValidationMessage('Mission Reward must be a number greater than or equal to 0');
                    return false;
                }

                return { name, reward, description };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                addMission(result.value);
            }
        });
    });
});

function showAddTaskModal(missionId) {
    Swal.fire({
        title: 'Add Task',
        html: `
            <input type="text" id="taskName" class="swal2-input" placeholder="Task Name" autocomplete="off" required>
            <input type="text" id="taskChatId" class="swal2-input" placeholder="Chat Username (No @)" autocomplete="off" required>
            <input type="text" id="taskUrl" class="swal2-input" placeholder="URL | ChatID" autocomplete="off" required>
            <div>
                <input type="radio" id="taskTypeUrl" name="taskType" class="swal2-radio" value="url" required>
                <label for="taskTypeUrl" class="swal2-label">Visit Website</label>
            </div>
            <div>
                <input type="radio" id="taskTypeJoinChat" name="taskType" class="swal2-radio" value="joinchat" required>
                <label for="taskTypeJoinChat" class="swal2-label">Join Chat</label>
            </div>
        `,
        confirmButtonText: 'Add',
        preConfirm: () => {
            const name = document.getElementById('taskName').value.trim();
            const chatId = document.getElementById('taskChatId').value.trim();
            const url = document.getElementById('taskUrl').value.trim();
            const type = document.querySelector('input[name="taskType"]:checked').value;

            if (!name || !chatId || !url || !type) {
                Swal.showValidationMessage('All fields are required and must not be empty');
                return false;
            }

            // Convert type back to database format
            const databaseType = (type === 'url') ? 0 : 1;

            return { missionId, name, chatId, url, type: databaseType };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            addTask(result.value);
        }
    });
}

function addTask(task) {
    fetch('api.php?action=addTask', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(task)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadMissions();
            Swal.fire('Success', 'Task added successfully', 'success');
        } else {
            Swal.fire('Error', 'Failed to add task', 'error');
        }
    });
}

function loadMissions() {
    fetch('api.php?action=getMissions')
        .then(response => response.json())
        .then(data => {
            const missionsList = document.getElementById('missionsList');
            missionsList.innerHTML = '';

            data.missions.forEach(mission => {
                const missionElement = document.createElement('div');
                missionElement.classList.add('bg-white', 'p-4', 'rounded', 'shadow');
                missionElement.innerHTML = `
                    <h2 class="text-2xl font-bold">${mission.name}</h2>
                    <p>Reward: ${mission.reward}</p>
                    <p>${mission.description}</p>
                    <button class="bg-red-500 text-white px-2 py-1 rounded mt-2" onclick="removeMission(${mission.id})">Remove Mission</button>
                    <button class="bg-green-500 text-white px-2 py-1 rounded mt-2" onclick="showAddTaskModal(${mission.id})">Add Task</button>
                    <div class="mt-4">
                        <h3 class="text-xl font-bold">Tasks</h3>
                        <ul class="list-disc pl-4">
                            ${mission.tasks.map(task => `
                                <li>
                                    <p>${task.name}</p>
                                    ${task.type == 1 ? `<p>Username: ${task.chatId}</p>` : ''}
                                    ${task.type == 1 ? `<p>ChatID: ${task.url}</p>` : `<p>webSite: ${task.url}</p>`}
                                    <p>Type: ${task.type == 0 ? 'WebSite' : 'Join Chat'}</p>
                                    <button class="bg-red-500 text-white px-2 py-1 rounded mt-2" onclick="removeTask(${task.id})">Remove Task</button>
                                </li>
                            `).join('')}
                        </ul>
                    </div>
                `;
                missionsList.appendChild(missionElement);
            });
        });
}


function addMission(mission) {
    fetch('api.php?action=addMission', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(mission)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadMissions();
            Swal.fire('Success', 'Mission added successfully', 'success');
        } else {
            Swal.fire('Error', 'Failed to add mission', 'error');
        }
    });
}

function removeMission(id) {
    fetch(`api.php?action=removeMission&id=${id}`, { method: 'GET' })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadMissions();
                Swal.fire('Success', 'Mission removed successfully', 'success');
            } else {
                Swal.fire('Error', 'Failed to remove mission', 'error');
            }
        });
}

function removeTask(id) {
    fetch(`api.php?action=removeTask&id=${id}`, { method: 'GET' })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadMissions();
                Swal.fire('Success', 'Task removed successfully', 'success');
            } else {
                Swal.fire('Error', 'Failed to remove task', 'error');
            }
        });
}
