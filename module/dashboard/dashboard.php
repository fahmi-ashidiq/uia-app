<div class="head-title">
    <div class="left">
        <h1>Dashboard</h1>
        <ul class="breadcrumb">
            <li>
                <a href="#">Dashboard</a>
            </li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li>
                <a class="active" href="#">Home</a>
            </li>
        </ul>
    </div>

    <a href="#" class="btn-download">
        <i class='bx bxs-cloud-download bx-fade-down-hover'></i>
        <span class="text">Get PDF</span>
    </a>
</div>


<ul class="box-info">
    <li>
        <i class='bx bxs-calendar-check'></i>
        <span class="text">
            <h3>1,020</h3>
            <p>New Order</p>
            <span class="trend up"><i class='bx bx-up-arrow-alt'></i> 12.4% vs last month</span>
        </span>
    </li>

    <li>
        <i class='bx bxs-group'></i>
        <span class="text">
            <h3>2,834</h3>
            <p>Visitors</p>
            <span class="trend up"><i class='bx bx-up-arrow-alt'></i> 8.1% vs last month</span>
        </span>
    </li>

    <li>
        <i class='bx bxs-dollar-circle'></i>
        <span class="text">
            <h3>$2,543.00</h3>
            <p>Total Sales</p>
            <span class="trend down"><i class='bx bx-down-arrow-alt'></i> 2.3% vs last month</span>
        </span>
    </li>

    <li>
        <i class='bx bxs-star'></i>
        <span class="text">
            <h3>4.8 / 5</h3>
            <p>Customer Rating</p>
            <span class="trend up"><i class='bx bx-up-arrow-alt'></i> 0.3 vs last month</span>
        </span>
    </li>
</ul>


<div class="chart-card">
    <div class="head">
        <h3>Sales Overview</h3>
        <div class="chart-legend">
            <span><i class="dot dot-blue"></i> This Year</span>
            <span><i class="dot dot-grey"></i> Last Year</span>
        </div>
    </div>
    <div class="chart-wrap">
        <canvas id="salesChart" height="110"></canvas>
    </div>
</div>


<div class="table-data">
    <div class="order">
        <div class="head">
            <h3>Recent Orders</h3>
            <i class='bx bx-search'></i>
            <i class='bx bx-filter'></i>
        </div>

        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Date Order</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td>
                        <span class="avatar-initial avatar-blue">MJ</span>
                        <p>Micheal John</p>
                    </td>
                    <td>18-10-2021</td>
                    <td>
                        <span class="status completed">Completed</span>
                    </td>
                </tr>

                <tr>
                    <td>
                        <span class="avatar-initial avatar-orange">RD</span>
                        <p>Ryan Doe</p>
                    </td>
                    <td>01-06-2022</td>
                    <td>
                        <span class="status pending">Pending</span>
                    </td>
                </tr>

                <tr>
                    <td>
                        <span class="avatar-initial avatar-yellow">TW</span>
                        <p>Tarry White</p>
                    </td>
                    <td>14-10-2021</td>
                    <td>
                        <span class="status process">Process</span>
                    </td>
                </tr>

                <tr>
                    <td>
                        <span class="avatar-initial avatar-green">S</span>
                        <p>Selma</p>
                    </td>
                    <td>01-02-2023</td>
                    <td>
                        <span class="status pending">Pending</span>
                    </td>
                </tr>

                <tr>
                    <td>
                        <span class="avatar-initial avatar-blue">AD</span>
                        <p>Andreas Doe</p>
                    </td>
                    <td>31-10-2021</td>
                    <td>
                        <span class="status completed">Completed</span>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>


    <div class="todo">
        <div class="head">
            <h3>Todos</h3>
            <i class='bx bx-plus icon'></i>
            <i class='bx bx-filter'></i>
        </div>

        <div class="todo-progress">
            <div class="todo-progress-bar" style="width: 60%;"></div>
        </div>
        <p class="todo-progress-label">3 of 5 tasks completed</p>

        <ul class="todo-list">
            <li class="completed">
                <p><i class='bx bx-check-circle'></i> Check Inventory</p>
                <i class='bx bx-dots-vertical-rounded'></i>
            </li>

            <li class="completed">
                <p><i class='bx bx-check-circle'></i> Manage Delivery Team</p>
                <i class='bx bx-dots-vertical-rounded'></i>
            </li>

            <li class="not-completed">
                <p><i class='bx bx-circle'></i> Contact Selma: Confirm Delivery</p>
                <i class='bx bx-dots-vertical-rounded'></i>
            </li>

            <li class="completed">
                <p><i class='bx bx-check-circle'></i> Update Shop Catalogue</p>
                <i class='bx bx-dots-vertical-rounded'></i>
            </li>

            <li class="not-completed">
                <p><i class='bx bx-circle'></i> Count Profit Analytics</p>
                <i class='bx bx-dots-vertical-rounded'></i>
            </li>
        </ul>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
(function () {
    const canvas = document.getElementById('salesChart');
    if (!canvas || typeof Chart === 'undefined') return;

    const styles = getComputedStyle(document.body);
    const blue = styles.getPropertyValue('--blue').trim() || '#3c91e6';
    const grey = styles.getPropertyValue('--dark-grey').trim() || '#aaaaaa';

    new Chart(canvas.getContext('2d'), {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
            datasets: [
                {
                    label: 'This Year',
                    data: [1200, 1900, 1500, 2100, 2400, 2200, 2543],
                    borderColor: blue,
                    backgroundColor: blue + '33',
                    tension: 0.35,
                    fill: true,
                    pointRadius: 3,
                },
                {
                    label: 'Last Year',
                    data: [900, 1400, 1300, 1700, 1900, 2000, 2100],
                    borderColor: grey,
                    backgroundColor: 'transparent',
                    borderDash: [6, 4],
                    tension: 0.35,
                    fill: false,
                    pointRadius: 0,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { grid: { color: 'rgba(128,128,128,0.15)' }, ticks: { callback: (v) => '$' + v } },
                x: { grid: { display: false } }
            }
        }
    });
})();
</script>