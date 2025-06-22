<style>
    h1 {
        color: #333;
        text-align: center;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        border: 1px solid #ccc;
        padding: 8px;
        text-align: left;
    }
    th {
        background-color: #f2f2f2;
    }
</style>

<h1>My Feedback Report</h1>

<table>
    <thead>
        <tr>
            <th>Concept</th>
            <th>Status</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($feedbacks as $feedback): ?>
            <tr>
                <td><?= $feedback->concept->title ?></td>
                <td><?= $feedback->isUnderstood() ? 'Understood' : 'Not Understood' ?></td>
                <td><?= Yii::$app->formatter->asDatetime($feedback->created_at) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
