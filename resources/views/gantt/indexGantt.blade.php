<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Gantt Chart</title>
    <link rel="stylesheet" href="https://unpkg.com/frappe-gantt/dist/frappe-gantt.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        #gantt { border: 1px solid #ccc; }
    </style>
</head>
<body>
    <h1>Project Gantt Chart</h1>

    <div id="gantt"></div>

    <script src="https://unpkg.com/frappe-gantt/dist/frappe-gantt.min.js"></script>
    <script>
        const tasks = @json($ganttTasks);

        const gantt = new Gantt("#gantt", tasks, {
            view_mode: 'Day', // Options: 'Day', 'Week', 'Month'
            date_format: 'YYYY-MM-DD',
            custom_popup_html: function(task) {
                return `
                    <div class="details-container">
                        <h5>${task.name}</h5>
                        <p>Start: ${task.start}</p>
                        <p>End: ${task.end}</p>
                        <p>Progress: ${task.progress}%</p>
                    </div>
                `;
            }
        });
    </script>
</body>
</html>