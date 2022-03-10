<!DOCTYPE html>
<html>

<head>
    <title><?= $offering['block'] ?> Attendance</title>
    <meta charset="utf-8" />
    <meta name=viewport content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="res/css/font-awesome-all.min.css">
    <link rel="stylesheet" href="res/css/offering.css">
    <link rel="stylesheet" href="res/css/adm.css">
    <link rel="stylesheet" href="res/css/attendance.css">
    <script src="res/js/attendance.js"></script>
</head>

<body>
    <header>
        <div id="controls" data-id="<?= $_SESSION['user']['id'] ?>">
            <a href="/videos/user" title="Users"><i class="fas fa-users"></i></a>
            <a href="logout" title="Logout"><i title="Logout" class="fas fa-power-off"></i></a>
        </div>
        <div id="course">
            <?= strtoupper($course) ?>
            <span data-id="<?= $offering['id'] ?>" id="offering"> <?= $offering['block'] ?> </span>
        </div>
        <h1>
            <span class="title">
                Attendance
            </span>
        </h1>
    </header>
    <main>
        <nav class="areas">
            <div title="Videos"><a href="../<?= $offering['block'] ?>/"><i class="fas fa-film"></a></i></div>
            <div title="Labs"><i class="fas fa-flask"></i></div>
            <div title="Quizzes"><i class="fas fa-school"></i></div>
            <div title="Attendance" class="active"><i class="fas fa-user-check"></i></div>
            <div title="Enrolled"><a href="enrolled"><i class="fas fa-user-friends"></i></a></div>
        </nav>

        <table id="days">
            <tr>
                <th>Mon</th>
                <th>Tue</th>
                <th>Wed</th>
                <th>Thu</th>
                <th>Fri</th>
                <th>Sat</th>
                <th>Sun</th>
            </tr>
            <?php for ($w = 1; $w <= 4; $w++) : ?>
                <tr>
                    <?php for ($d = 1; $d <= 7; $d++) : ?>
                        <?php $date = $start + ($w - 1) * 60 * 60 * 24 * 7 + ($d - 1) * 60 * 60 * 24; ?>
                        <td class="<?= $date < $now ? "done" : "" ?> <?= date("z", $date) == date("z", $now) ? "curr" : "" ?>" 
                            id="<?= "W{$w}D{$d}" ?>" data-day="<?= "W{$w}D{$d}" ?>" data-day_id="<?= $days["W{$w}D{$d}"]["id"] ?>" 
                            data-date="<?= date("Y-m-d", $date) ?>">
                            <?php if ($w == 4 && $d == 6) : ?>
                                <i title="Professionalism Report" class="fab fa-black-tie"></i>
                            <?php elseif ($d == 7): ?>
                                <i title="Physical Classroom Attendance Report" class="fas fa-chalkboard-teacher"></i>
                            <?php else : ?>
                                <?php foreach (["AM", "PM"] as $stype): ?>
                                    <div class="session <?= $stype ?>" data-session_id="<?= $days["W{$w}D{$d}"][$stype]["id"] ?>"
                                        data-stype="<?= $stype ?>">
                                        <?= $stype ?>
                                        <i title="Add Meeting" class="far fa-plus-square"></i>

                                    <?php foreach ($days["W{$w}D{$d}"][$stype]["meetings"] as $meeting) : ?>
                                        <div class="meeting">
                                            <a href="meeting/<?= $meeting["id"] ?>">
                                                <?= $meeting["title"] ?>
                                            </a>
                                        </div>
                                    <?php endforeach; ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>

                            <time>
                                <?= date("M j Y", $date); ?>
                            </time>
                        </td>
                    <?php endfor ?>
                </tr>
            <?php endfor ?>
        </table>
    </main>

    <div id="overlay">
        <i id="close-overlay" class="fas fa-times-circle"></i>
        <div class="modal">
            <h3>Add a Meeting</h3>

            <h4>Manually Create a Meeting</h4>
            <form action="meeting" method="post"> 
                <input type="hidden" id="manual_session_id" name="session_id" />
                <div>
                    <label>Title</label>
                    <input type="text" name="title" id="manual_title" />
                </div>
                <div>
                    <label>Date</label>
                    <input type="date" name="date" id="manual_date"/>
                </div>
                <div>
                    <label>Start</label>
                    <input type="text" name="start" id="manual_start"/>
                </div>
                <div>
                    <label>Stop</label>
                    <input type="text" name="stop" />
                </div>
                <div class="btn">
                    <button type="submit">Create Meeting</button>
                </div>
            </form>

            <h4>Or Upload a Teams Meeting</h4>
            <form action="" method="post" enctype="multipart/form-data" id="upload_form">
                <input type="hidden" id="session_id" name="session_id" />
                <div>
                    <label>Start</label>
                    <input type="text" name="start" id="start" />
                </div>
                <div>
                    <label>File*</label>
                    <input type="file" id="list_file" name="list" />
                </div>
                <div class="btn"><button>Upload Meeting</button></div>
                <p class="right">*Filename will be used as meeting title</p>
            </form>
        </div>

    </div>

</body>

</html>