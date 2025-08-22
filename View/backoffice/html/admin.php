<?php
require_once "../../../config.php";
require_once "../../../Controller/StatsController.php";

// Database connection
$config = new config();
$pdo = $config->getConnexion();

// Stats controller
$stats = new StatsController($pdo);

// Fetch stats
$pending = $stats->getPendingEvents();
$approved = $stats->getApprovedEvents();
$organizers = $stats->getOrganizers();
$upcoming = $stats->getUpcomingEvents();

$eventsPerMonth = $stats->getEventsPerMonth();
$eventsByOrganizer = $stats->getEventsByOrganizer();
$participations = $stats->getParticipationsPerEvent();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
body {
    margin: 0;
    font-family: 'Orbitron', sans-serif;
    background: url('https://media.istockphoto.com/id/530437857/photo/beautiful-european-night-club-interior.jpg?s=612x612&w=0&k=20&c=2Xpq2Z1o9rOLsQ9-B6rHPI7FAtH3LSCMqKHKUDs8zUg=') no-repeat center center fixed;
    background-size: cover;
    color: #fff;
    overflow-x: hidden;
}
/* NAVBAR */
.navbar {
    width: 100%;
    top: 0;
    left: 0;
    position: sticky;
    background: rgba(17,17,17,0.9);
    padding: 15px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 0 15px #b200ff;
    z-index: 1000;
}
.nav-left a {
    color: #b200ff;
    text-decoration: none;
    display: inline-block;
    font-weight: bold;
    transition: 0.3s;
}

.nav-left a:hover { color: #ff00ff; }
.nav-logo {
    font-size: 1.8em;
    color: #b200ff;
    font-weight: bold;
}
.nav-left {
    display: flex;
    gap: 20px;
}

/* STATISTICS CARDS */
.stats-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 25px;
    margin: 30px;
}
.stat-card {
    background: rgba(17,17,17,0.85);
    border-radius: 15px;
    width: 220px;
    height: 140px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    box-shadow: 0 0 15px #b200ff;
    transition: transform 0.3s, box-shadow 0.3s;
}
.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0 25px #b200ff, 0 0 35px #ff00ff;
}
.stat-card h2 {
    font-size: 2em;
    color: #b200ff;
    margin-bottom: 5px;
}
.stat-card p {
    font-size: 1.1em;
    color: #fff;
}
/* CHARTS */
.chart-section {
    margin: 50px 30px;
    display: flex;
    flex-wrap: wrap;
    gap: 40px;
    justify-content: center;
}
.chart-card {
    background: rgba(17,17,17,0.85);
    border-radius: 15px;
    width: 400px;
    padding: 20px;
    box-shadow: 0 0 15px #b200ff;
}
.section-title {
    text-align: center;
    font-size: 2.2em;
    color: #b200ff;
    margin-top: 40px;
    text-shadow: 0 0 8px #b200ff;
}
* {
    box-sizing: border-box; /* Include padding/border in width calculations */
}
</style>
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
    <div class="nav-left">
        <a href="admin.php">Home</a>
        <a href="demand.php">Inbox</a>
        <a href="event.php">Manage Events</a>
        <a href="utilisateurs.php">Manage Users</a>
        <a href="logout.php">Logout</a>
    </div>
    <div class="nav-logo">Admin Dashboard</div>
</div>

<!-- STATISTICS CARDS -->
<div class="section-title">Statistics Overview</div>
<div class="stats-container">
    <div class="stat-card">
        <h2><?= $pending ?></h2>
        <p>Pending Events</p>
    </div>
    <div class="stat-card">
        <h2><?= $approved ?></h2>
        <p>Approved Events</p>
    </div>
    <div class="stat-card">
        <h2><?= $organizers ?></h2>
        <p>Organizers</p>
    </div>
    <div class="stat-card">
        <h2><?= $upcoming ?></h2>
        <p>Upcoming Events</p>
    </div>
</div>

<!-- CHARTS -->
<div class="section-title">Charts</div>
<div class="chart-section">
    <div class="chart-card">
        <canvas id="eventsChart"></canvas>
    </div>
    <div class="chart-card">
        <canvas id="organizersChart"></canvas>
    </div>
    <div class="chart-card">
        <canvas id="participationsChart"></canvas>
    </div>
</div>

<script>
// Data from PHP
const eventsData = <?= json_encode(array_values($eventsPerMonth)) ?>;
const organizersLabels = <?= json_encode(array_column($eventsByOrganizer, 'nom')) ?>;
const organizersData = <?= json_encode(array_column($eventsByOrganizer, 'total')) ?>;
const participationLabels = <?= json_encode(array_column($participations, 'event_name')) ?>;
const participationData = <?= json_encode(array_column($participations, 'total')) ?>;

// Events per month chart
new Chart(document.getElementById('eventsChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
        datasets: [{
            label: 'Events per Month',
            data: eventsData,
            backgroundColor: 'rgba(178,0,255,0.6)',
            borderColor: '#b200ff',
            borderWidth: 1
        }]
    },
    options: { responsive:true, plugins:{legend:{labels:{color:'#b200ff'}}}, 
        scales:{y:{beginAtZero:true, ticks:{color:'#b200ff'}}, x:{ticks:{color:'#b200ff'}}} }
});

// Organizers chart
new Chart(document.getElementById('organizersChart').getContext('2d'), {
    type: 'doughnut',
    data: {
        labels: organizersLabels,
        datasets: [{
            data: organizersData,
            backgroundColor: ['#b200ff','#ff00ff','#00ffff','#ffff00','#ff6600']
        }]
    },
    options: { plugins:{legend:{labels:{color:'#b200ff'}}} }
});

// Participations chart
new Chart(document.getElementById('participationsChart').getContext('2d'), {
    type: 'line',
    data: {
        labels: participationLabels,
        datasets: [{
            label: 'Participations per Event',
            data: participationData,
            fill: true,
            backgroundColor: 'rgba(178,0,255,0.2)',
            borderColor: '#b200ff',
            tension: 0.3
        }]
    },
    options: { responsive:true, plugins:{legend:{labels:{color:'#b200ff'}}}, 
        scales:{y:{ticks:{color:'#b200ff'}}, x:{ticks:{color:'#b200ff'}}} }
});
</script>
</body>
</html>
