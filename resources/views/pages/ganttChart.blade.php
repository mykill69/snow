<canvas id="ganttChart" height="400"></canvas>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const tasks = @json($tasks);

const ctx = document.getElementById('ganttChart').getContext('2d');

const labels = tasks.map(task => task.task_name);

const datasets = tasks.map(task => {
    const start = new Date(task.start_date).getTime();
    const end = new Date(task.end_date).getTime();
    return {
        label: task.task_name,
        data: [{ x: [start, end], y: task.task_name }],
        backgroundColor: 'rgba(54, 162, 235, 0.7)',
        borderColor: 'rgba(54, 162, 235, 1)',
        borderWidth: 1
    };
});

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            label: 'Tasks',
            data: tasks.map(task => ({
                x: [new Date(task.start_date).getTime(), new Date(task.end_date).getTime()],
                y: task.task_name
            })),
            backgroundColor: 'rgba(54, 162, 235, 0.7)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        indexAxis: 'y', // horizontal
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const task = tasks[context.dataIndex];
                        const start = task.start_date ?? 'Not set';
                        const end = task.end_date ?? 'Not set';
                        const person = task.admin ? task.admin.fname + ' ' + task.admin.lname : '-';
                        return `${task.task_name}: ${start} → ${end} (Assigned: ${person})`;
                    }
                }
            }
        },
        scales: {
            x: {
                type: 'time',
                time: { unit: 'day' },
                title: { display: true, text: 'Date' }
            },
            y: {
                title: { display: true, text: 'Tasks' }
            }
        }
    }
});
</script>
