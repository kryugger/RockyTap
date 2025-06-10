<!DOCTYPE html>
<?php
include '../../../bot/config.php';
include '../../../bot/functions.php';

$MySQLi = new mysqli('localhost',$DB['username'],$DB['password'],$DB['dbname']);
$MySQLi->query("SET NAMES 'utf8'");
$MySQLi->set_charset('utf8mb4');
if ($MySQLi->connect_error) die;
function ToDie($MySQLi){
$MySQLi->close();
die;
}

$q = $_REQUEST['q'];

$get_user = mysqli_fetch_assoc(mysqli_query($MySQLi, "SELECT * FROM `users` WHERE `id` = '{$q}' LIMIT 1"));


//          calculate user tappingGuruLeft           //
$tappingGuruLeft = $get_user['tappingGuruLeft'];
if(microtime(true) * 1000 >= $get_user['tappingGuruNextTime']){
    if($tappingGuruLeft >= 3){
        $tappingGuruLeft = 3;
    }else{
        $tappingGuruLeft++;
    }
    $tappingGuruNextTime = microtime(true) * 1000 + (6 * 60 * 60 * 1000);
    $MySQLi->query("UPDATE `users` SET `tappingGuruNextTime` = '{$tappingGuruNextTime}', `tappingGuruLeft` = '{$tappingGuruLeft}' WHERE `id` = '{$q}' LIMIT 1");
}


//          calculate user fullTankLeft           //
$fullTankLeft = $get_user['fullTankLeft'];
if(microtime(true) * 1000 >= $get_user['fullTankNextTime']){
    if($fullTankLeft >= 3){
        $fullTankLeft = 3;
    }else{
        $fullTankLeft++;
    }
    $fullTankNextTime = microtime(true) * 1000 + (6 * 60 * 60 * 1000);
    $MySQLi->query("UPDATE `users` SET `fullTankNextTime` = '{$fullTankNextTime}', `fullTankLeft` = '{$fullTankLeft}' WHERE `id` = '{$q}' LIMIT 1");
}

$get_user = mysqli_fetch_assoc(mysqli_query($MySQLi, "SELECT * FROM `users` WHERE `id` = '{$q}' LIMIT 1"));

$Name = $get_user['first_name'] . ' ' . $get_user['last_name'];
$UserID = $get_user['id'];
$Username = $get_user['username']?:'-';
$isBanned = 'No';
if ($get_user['step'] == 'banned') $isBanned = 'Yes';
$Language = 'wooden';
if($get_user['score'] >= 1000) $Language = 'bronze';
elseif($get_user['score'] >= 5000) $Language = 'silver';
elseif($get_user['score'] >= 10000) $Language = 'gold';
elseif($get_user['score'] >= 30000) $Language = 'platinum';
elseif($get_user['score'] >= 50000) $Language = 'diamond';
elseif($get_user['score'] >= 100000) $Language = 'master';
elseif($get_user['score'] >= 250000) $Language = 'grandmaster';
elseif($get_user['score'] >= 500000) $Language = 'elite';
elseif($get_user['score'] >= 1000000) $Language = 'legendary';
$isPremium = 'No';
if ($get_user['is_premium'] == 1) $isPremium = 'Yes';
$Score = number_format($get_user['score']);
$Balance = number_format($get_user['balance']);
$TappingGuru = $get_user['tappingGuruLeft'];
$FullTank = $get_user['fullTankLeft'];
$Referrals = $get_user['referrals'];
$multiTap = $get_user['multitap'];
$energyLimit = $get_user['energyLimit'];
$rechargingSpeed = $get_user['rechargingSpeed'];
$joinDate = date('Y-m-d H:i:s', $get_user['joining_date']);
$userInGameURL = $web_app . '/#tgWebAppData='.$get_user['tdata'].'&tgWebAppVersion=7.2&tgWebAppPlatform=weba&tgWebAppThemeParams=%7B%22bg_color%22%3A%22%23212121%22%2C%22text_color%22%3A%22%23ffffff%22%2C%22hint_color%22%3A%22%23aaaaaa%22%2C%22link_color%22%3A%22%238774e1%22%2C%22button_color%22%3A%22%238774e1%22%2C%22button_text_color%22%3A%22%23ffffff%22%2C%22secondary_bg_color%22%3A%22%230f0f0f%22%2C%22header_bg_color%22%3A%22%23212121%22%2C%22accent_text_color%22%3A%22%238774e1%22%2C%22section_bg_color%22%3A%22%23212121%22%2C%22section_header_text_color%22%3A%22%23aaaaaa%22%2C%22subtitle_text_color%22%3A%22%23aaaaaa%22%2C%22destructive_text_color%22%3A%22%23e53935%22%7D';
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        @font-face {
            font-family: 'CustomFont';
            src: url('./CustomFont.woff2') format('woff2');
        }
        body {
            font-family: 'CustomFont', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100">

    <div class="container mx-auto p-8">
        <h1 class="text-3xl font-bold mb-8 text-center">User Management</h1>
        
        <!-- User Info Section -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-8">
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">User Information</h2>
            <div id="userInfo" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- User information will be populated here dynamically -->
                <p class="text-gray-700"><strong>Name: </strong><?=$Name;?></p>
                <p class="text-gray-700"><strong>UserID: </strong><?=$UserID;?></p>
                <p class="text-gray-700"><strong>Username: </strong><?=$Username;?></p>
                <p class="text-gray-700"><strong>isBanned: </strong><?=$isBanned;?></p>
                <p class="text-gray-700"><strong>Language: </strong><?=$Language;?></p>
                <p class="text-gray-700"><strong>isPremium: </strong><?=$isPremium;?></p>
                <p class="text-gray-700"><strong>Score: </strong><?=$Score;?></p>
                <p class="text-gray-700"><strong>Balance: </strong><?=$Balance;?></p>
                <p class="text-gray-700"><strong>multiTap Level: </strong><?=$multiTap;?></p>
                <p class="text-gray-700"><strong>energyLimit Level: </strong><?=$energyLimit;?></p>
                <p class="text-gray-700"><strong>rechargingSpeed Level: </strong><?=$rechargingSpeed;?></p>
                <p class="text-gray-700"><strong>TappingGuru Left: </strong><?=$TappingGuru;?></p>
                <p class="text-gray-700"><strong>FullTank Left: </strong><?=$FullTank;?></p>
                <p class="text-gray-700"><strong>Referrals: </strong><?=$Referrals;?></p>
                <p class="text-gray-700"><strong>joinDate: </strong><?=$joinDate;?></p>
                <!-- Add more user info as needed -->
            </div>
        </div>


<!-- Actions Section -->
<div class="bg-white shadow-md rounded-lg p-6">
    <h2 class="text-2xl font-semibold text-gray-700 mb-4">Actions</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <button class="bg-gray-700 text-white px-4 py-2 rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50 transition-colors" onclick="banUser()">Ban User</button>
        <button class="bg-gray-700 text-white px-4 py-2 rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50 transition-colors" onclick="unbanUser()">Unban User</button>
        <button class="bg-gray-700 text-white px-4 py-2 rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50 transition-colors" onclick="changeUserScore()">Change User Score</button>
        <button class="bg-gray-700 text-white px-4 py-2 rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50 transition-colors" onclick="changeUserBalance()">Change User Balance</button>
        <button class="bg-gray-700 text-white px-4 py-2 rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50 transition-colors" onclick="resetUserTappingGuru()">Reset User Tapping Guru</button>
        <button class="bg-gray-700 text-white px-4 py-2 rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50 transition-colors" onclick="resetUserFullTank()">Reset User FullTank</button>
        <button class="bg-gray-700 text-white px-4 py-2 rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50 transition-colors" onclick="changeMultiTapLevel()">Change User MultiTap Level</button>
        <button class="bg-gray-700 text-white px-4 py-2 rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50 transition-colors" onclick="changeEnergyLimitLevel()">Change User EnergyLimit Level</button>
        <button class="bg-gray-700 text-white px-4 py-2 rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50 transition-colors" onclick="changeRechargingSpeedLevel()">Change User RechargingSpeed Level</button>
        <button class="bg-gray-700 text-white px-4 py-2 rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50 transition-colors" onclick="sendMessageToUser()">Send Message to User</button>
        <button class="bg-gray-700 text-white px-4 py-2 rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50 transition-colors" onclick="openUserProfileInGame()">Open User In-Game Profile</button>
        <a href="../index.php" class="text-gray-900 hover:text-white border border-gray-800 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-gray-600 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-800">Back To Search</a>

        <!-- Add more action buttons as needed -->
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



    function banUser() {
        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you really want to ban this user?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, ban user!',
            cancelButtonText: 'No, cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                const userId = new URLSearchParams(window.location.search).get('q');
                fetch('./api.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `q=${userId}&action=banUser`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire(
                            'Banned!',
                            'The user has been banned.',
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire(
                            'Error!',
                            'There was a problem banning the user.',
                            'error'
                        );
                    }
                })
                .catch(error => {
                    Swal.fire(
                        'Error!',
                        'There was a problem banning the user.',
                        'error'
                    );
                });
            }
        });
    }


    function unbanUser() {
        Swal.fire({
        title: 'Are you sure?',
        text: 'Do you really want to unban this user?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, unban user!',
        cancelButtonText: 'No, cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            const userId = new URLSearchParams(window.location.search).get('q');
            fetch('./api.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `q=${userId}&action=unbanUser`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire(
                        'UnBanned!',
                        'The user has been unbanned.',
                        'success'
                    ).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire(
                        'Error!',
                        'There was a problem unbanning the user.',
                        'error'
                    );
                }
            })
            .catch(error => {
                Swal.fire(
                    'Error!',
                    'There was a problem unbanning the user.',
                    'error'
                );
            });
        }
    });
    }


    function changeUserScore() {
        Swal.fire({
            title: 'Enter new score',
            input: 'number',
            inputAttributes: {
                min: 0,
                autocomplete: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'Submit',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                const newScore = result.value;
                const userId = new URLSearchParams(window.location.search).get('q');
                fetch('./api.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `q=${userId}&action=changeUserScore&newScore=${newScore}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire(
                            'Success!',
                            'The user\'s score has been changed.',
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire(
                            'Error!',
                            'There was a problem changing the user\'s score.',
                            'error'
                        );
                    }
                })
                .catch(error => {
                    Swal.fire(
                        'Error!',
                        'There was a problem changing the user\'s score.',
                        'error'
                    );
                });
            }
        });
    }


    function changeUserBalance() {
        Swal.fire({
            title: 'Enter new balance',
            input: 'number',
            inputAttributes: {
                min: 0,
                autocomplete: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'Submit',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                const newScore = result.value;
                const userId = new URLSearchParams(window.location.search).get('q');
                fetch('./api.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `q=${userId}&action=changeUserBalance&newBalance=${newScore}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire(
                            'Success!',
                            'The user\'s balance has been changed.',
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire(
                            'Error!',
                            'There was a problem changing the user\'s balance.',
                            'error'
                        );
                    }
                })
                .catch(error => {
                    Swal.fire(
                        'Error!',
                        'There was a problem changing the user\'s balance.',
                        'error'
                    );
                });
            }
        });
    }


    function resetUserTappingGuru() {
        Swal.fire({
        title: 'Are you sure?',
        text: 'Do you really want to reset User TappingGuru?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, reset!',
        cancelButtonText: 'No, cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            const userId = new URLSearchParams(window.location.search).get('q');
            fetch('./api.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `q=${userId}&action=resetUserTappingGuru`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire(
                        'Done!',
                        'The user TappingGuru has been reseted.',
                        'success'
                    ).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire(
                        'Error!',
                        'There was a problem reseting the user TappingGuru.',
                        'error'
                    );
                }
            })
            .catch(error => {
                Swal.fire(
                    'Error!',
                    'There was a problem reseting the user TappingGuru.',
                    'error'
                );
            });
        }
    });
    }


    function resetUserFullTank() {
        Swal.fire({
        title: 'Are you sure?',
        text: 'Do you really want to reset User FullTank?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, reset!',
        cancelButtonText: 'No, cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            const userId = new URLSearchParams(window.location.search).get('q');
            fetch('./api.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `q=${userId}&action=resetUserFullTank`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire(
                        'Done!',
                        'The user FullTank has been reseted.',
                        'success'
                    ).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire(
                        'Error!',
                        'There was a problem reseting the user FullTank.',
                        'error'
                    );
                }
            })
            .catch(error => {
                Swal.fire(
                    'Error!',
                    'There was a problem reseting the user FullTank.',
                    'error'
                );
            });
        }
    });
    }


    function sendMessageToUser() {
        Swal.fire({
            title: 'Send Message To User',
            text: 'You can use HTML formats !',
            input: 'textarea',
            inputAttributes: {
                autocapitalize: 'off',
                rows: 1,
                autocomplete: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'Send',
            cancelButtonText: 'Cancel',
            showLoaderOnConfirm: true,
            preConfirm: (message) => {
                const userId = new URLSearchParams(window.location.search).get('q');
                return fetch('./api.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `q=${userId}&action=sendMessageToUser&text=${encodeURIComponent(message)}`
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .catch(error => {
                    Swal.showValidationMessage(
                        `Request failed: ${error}`
                    );
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire(
                    'Message Sent!',
                    'Your message has been sent to the user.',
                    'success'
                ).then(() => {
                    location.reload();
                });
            }
        });
    }


    function changeMultiTapLevel() {
        Swal.fire({
            title: 'Enter new multiTap Level (1-20)',
            input: 'number',
            inputAttributes: {
                min: 1,
                max: 20,
                autocomplete: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'Submit',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                const newLevel = result.value;
                const userId = new URLSearchParams(window.location.search).get('q');
                fetch('./api.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `q=${userId}&action=changeMultiTapLevel&newLevel=${newLevel}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire(
                            'Success!',
                            'The user\'s multiTap Level has been changed.',
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire(
                            'Error!',
                            'There was a problem changing the user\'s multiTap Level.',
                            'error'
                        );
                    }
                })
                .catch(error => {
                    Swal.fire(
                        'Error!',
                        'There was a problem changing the user\'s multiTap Level.',
                        'error'
                    );
                });
            }
        });
    }


    function changeEnergyLimitLevel() {
        Swal.fire({
            title: 'Enter new energyLimit Level (1-20)',
            input: 'number',
            inputAttributes: {
                min: 1,
                max: 20,
                autocomplete: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'Submit',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                const newLevel = result.value;
                const userId = new URLSearchParams(window.location.search).get('q');
                fetch('./api.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `q=${userId}&action=changeEnergyLimitLevel&newLevel=${newLevel}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire(
                            'Success!',
                            'The user\'s energyLimit Level has been changed.',
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire(
                            'Error!',
                            'There was a problem changing the user\'s energyLimit Level.',
                            'error'
                        );
                    }
                })
                .catch(error => {
                    Swal.fire(
                        'Error!',
                        'There was a problem changing the user\'s energyLimit Level.',
                        'error'
                    );
                });
            }
        });
    }


    function changeRechargingSpeedLevel() {
        Swal.fire({
            title: 'Enter new rechargingSpeed Level (1-5)',
            input: 'number',
            inputAttributes: {
                min: 1,
                max: 5,
                autocomplete: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'Submit',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                const newLevel = result.value;
                const userId = new URLSearchParams(window.location.search).get('q');
                fetch('./api.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `q=${userId}&action=changeRechargingSpeedLevel&newLevel=${newLevel}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire(
                            'Success!',
                            'The user\'s rechargingSpeed Level has been changed.',
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire(
                            'Error!',
                            'There was a problem changing the user\'s rechargingSpeed Level.',
                            'error'
                        );
                    }
                })
                .catch(error => {
                    Swal.fire(
                        'Error!',
                        'There was a problem changing the user\'s rechargingSpeed Level.',
                        'error'
                    );
                });
            }
        });
    }

    function openUserProfileInGame(){
        var url = '<?php echo $userInGameURL; ?>';
        window.location.href = url;
    }










    </script>
</body>
</html>
