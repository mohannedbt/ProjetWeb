<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
/* Full page background */
body {
    margin: 0;
    font-family: 'Orbitron', sans-serif;
    background: url('https://media.istockphoto.com/id/530437857/photo/beautiful-european-night-club-interior.jpg?s=612x612&w=0&k=20&c=2Xpq2Z1o9rOLsQ9-B6rHPI7FAtH3LSCMqKHKUDs8zUg=') no-repeat center center fixed;
    background-size: cover;
    color: #fff;
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
    box-shadow: 0 0 15px #0ff;
    z-index: 1000;
}

/* Navbar links on left */
.nav-left {
    display: flex;
    gap: 20px;
}

.nav-left a {
    color: #0ff;
    text-decoration: none;
    font-weight: bold;
    transition: 0.3s;
}
.nav-left a:hover { color: #ff0; }

/* Logo / title on right */
.nav-logo {
    font-size: 1.8em;
    color: #0ff;
    font-weight: bold;
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
    box-shadow: 0 0 15px #0ff;
    transition: transform 0.3s, box-shadow 0.3s;
}
.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0 25px #0ff, 0 0 35px #ff0;
}
.stat-card h2 {
    font-size: 2em;
    color: #0ff;
    margin-bottom: 5px;
}
.stat-card p {
    font-size: 1.1em;
    color: #fff;
}

/* CHARTS SECTION */
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
    box-shadow: 0 0 15px #0ff;
}

/* SECTION TITLE */
.section-title {
    text-align: center;
    font-size: 2.2em;
    color: #0ff;
    margin-top: 40px;
    text-shadow: 0 0 8px #0ff;
}
html, body {
    margin: 0;
    padding: 0;
    overflow-x: hidden; /* Prevent horizontal scroll */
    width: 100%;
    box-sizing: border-box;
}

/* Make flex containers wrap properly */
.stats-container, .chart-section {
    max-width: 100%;
    flex-wrap: wrap;
    justify-content: center;
}

/* Ensure cards don’t exceed parent */
.stat-card, .chart-card {
    max-width: 100%;
    box-sizing: border-box;
}

/* Navbar stays within viewport */
.navbar {
    width: 100%;
    box-sizing: border-box;
}
/* BODY & BACKGROUND */
body {
    margin: 0;
    font-family: 'Orbitron', sans-serif;
    background: url('https://media.istockphoto.com/id/530437857/photo/beautiful-european-night-club-interior.jpg?s=612x612&w=0&k=20&c=2Xpq2Z1o9rOLsQ9-B6rHPI7FAtH3LSCMqKHKUDs8zUg=') no-repeat center center fixed;
    background-size: cover;
    color: #fff;
    overflow-x: hidden; /* prevent horizontal scroll */
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
    font-weight: bold;
    transition: 0.3s;
}
.nav-left a:hover {
    color: #ff00ff;
}

/* Logo / title on right */
.nav-logo {
    font-size: 1.8em;
    color: #b200ff;
    font-weight: bold;
}

/* STATISTICS CARDS */
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
.chart-card {
    background: rgba(17,17,17,0.85);
    border-radius: 15px;
    width: 400px;
    padding: 20px;
    box-shadow: 0 0 15px #b200ff;
}

/* SECTION TITLE */
.section-title {
    text-align: center;
    font-size: 2.2em;
    color: #b200ff;
    margin-top: 40px;
    text-shadow: 0 0 8px #b200ff;
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
        <a href="logout.php">Logout</a>

    </div>
    <div class="nav-logo">Admin Dashboard</div>
</div>

<!-- STATISTICS CARDS -->
<div class="section-title">Statistics Overview</div>
<div class="stats-container">
    <div class="stat-card">
        <h2 class="counter" data-target="24">0</h2>
        <p>Pending Requests</p>
    </div>
    <div class="stat-card">
        <h2 class="counter" data-target="12">0</h2>
        <p>Approved Events</p>
    </div>
    <div class="stat-card">
        <h2 class="counter" data-target="5">0</h2>
        <p>Organizers</p>
    </div>
    <div class="stat-card">
        <h2 class="counter" data-target="3">0</h2>
        <p>Upcoming Festivals</p>
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
        <canvas id="requestsChart"></canvas>
    </div>
</div>

<script>
// Counter Animation
const counters = document.querySelectorAll('.counter');
counters.forEach(counter => {
    const updateCount = () => {
        const target = +counter.getAttribute('data-target');
        const count = +counter.innerText;
        const speed = 50;
        const inc = target / speed;
        if(count < target) {
            counter.innerText = Math.ceil(count + inc);
            setTimeout(updateCount, 20);
        } else {
            counter.innerText = target;
        }
    };
    updateCount();
});

// Chart.js - Example Charts
const eventsCtx = document.getElementById('eventsChart').getContext('2d');
new Chart(eventsCtx, {
    type: 'bar',
    data: {
        labels: ['Jan','Feb','Mar','Apr','May','Jun'],
        datasets: [{
            label: 'Events per Month',
            data: [5,8,4,6,7,10],
            backgroundColor: 'rgba(0,255,255,0.7)',
            borderColor: 'rgba(0,255,255,1)',
            borderWidth: 1
        }]
    },
    options: { responsive:true, plugins:{legend:{labels:{color:'#0ff'}}}, scales:{y:{beginAtZero:true, ticks:{color:'#0ff'}}, x:{ticks:{color:'#0ff'}}} }
});

const organizersCtx = document.getElementById('organizersChart').getContext('2d');
new Chart(organizersCtx, {
    type: 'doughnut',
    data: {
        labels: ['Organizer A','Organizer B','Organizer C'],
        datasets: [{
            data: [5,7,3],
            backgroundColor: ['#0ff','#ff0','#f0f']
        }]
    },
    options: { plugins:{legend:{labels:{color:'#0ff'}}} }
});

const requestsCtx = document.getElementById('requestsChart').getContext('2d');
new Chart(requestsCtx, {
    type: 'line',
    data: {
        labels: ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'],
        datasets: [{
            label: 'Requests',
            data: [2,3,5,4,6,2,1],
            fill: true,
            backgroundColor: 'rgba(0,255,255,0.2)',
            borderColor: '#0ff',
            tension: 0.3
        }]
    },
    options: { responsive:true, plugins:{legend:{labels:{color:'#0ff'}}}, scales:{y:{ticks:{color:'#0ff'}}, x:{ticks:{color:'#0ff'}}} }
});
</script>

</body>
</html>
