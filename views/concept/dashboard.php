<?php
use yii\helpers\Html;

$this->title = 'Admin Dashboard';
?>

<h1><?= Html::encode($this->title) ?></h1>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<table class="table table-bordered">
    <tr>
        <th>Concept</th>
        <th>Understood Count</th>
        <th>Not Understood Count</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($concepts as $c): ?>
        <tr>
            <td><?= Html::encode($c->title) ?></td>
            <td><?= $c->getFeedbacks()->where(['status' => 'understood'])->count() ?></td>
            <td><?= $c->getFeedbacks()->where(['status' => 'not_understood'])->count() ?></td>
            <td>
                <?= Html::a('View Understood', ['view-feedback', 'id' => $c->id, 'status' => 'understood'], ['class' => 'btn btn-sm btn-outline-success']) ?>
                <?= Html::a('View Not Understood', ['view-feedback', 'id' => $c->id, 'status' => 'not_understood'], ['class' => 'btn btn-sm btn-outline-danger']) ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<h2>Visual Feedback Summary</h2>
<canvas id="feedbackChart" width="400" height="200"></canvas>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('feedbackChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($labels) ?>,
            datasets: [{
                label: '🟢 Understood',
                data: <?= json_encode($greens) ?>,
                backgroundColor: 'rgba(75, 192, 192, 0.5)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }, {
                label: '🔴 Not Understood',
                data: <?= json_encode($reds) ?>,
                backgroundColor: 'rgba(255, 99, 132, 0.5)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
});
</script>
