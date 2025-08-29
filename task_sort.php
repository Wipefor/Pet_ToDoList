<?php
//set a class for a task to be properly displayed by CSS at DOM
function task_sort($task)
{
    $is_completed = $task['is_completed'];
    $completed_at = $task['completed_at'];
    $deadline_at = $task['deadline_at'];

    if ($is_completed) {
        if ($deadline_at === null) {
            // Case 1: No deadline + completed = GREEN
            return 'completed-no-deadline';
        } else {
            $completed_time = strtotime($completed_at);
            $deadline_time = strtotime($deadline_at);

            if ($completed_time <= $deadline_time) {
                // Case 2: Completed before deadline = GREEN
                return 'completed-on-time';
            } else {
                // Case 3: Completed after deadline = RED
                return 'completed-overdue';
            }
        }
        /*return $completed_time <= $deadline_time ?
            'completed-on-time' : 'completed-overdue';*/ //neat way
    } else {
        if ($deadline_at !== null && strtotime($deadline_at) < time()) {
            // Case 4: Not completed + deadline passed = RED
            return 'not-completed-overdue';
        }
    }
}

function formatTaskDate($date, $emptyText = '') {
    if ($date) {
        return htmlspecialchars(date('d-m-y H:i', strtotime($date)));
    } else {
        return '<span class="empty-date">' . htmlspecialchars($emptyText) . '</span>';
    }
}

?>