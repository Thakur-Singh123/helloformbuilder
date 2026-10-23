<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Task Assigned</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 20px;">
    
    <div style="max-width: 600px; margin: 0 auto; background: #fff; padding: 20px; border: 1px solid #ddd; border-radius: 8px;">
        
        <h2 style="color: #0f172a; margin-top: 0;">Hello {{ $employee_name }},</h2>

        <p>A new task has been assigned to you on <strong>{{ $site_name }}</strong>. Here are the details:</p>

        <!-- Task Details Box -->
        <div style="background: #f8fafc; padding: 15px; border-left: 4px solid #3b82f6; border-radius: 4px; margin: 20px 0;">
            <h3 style="margin-top: 0; color: #1e293b;">{{ $task_title }}</h3>
            
            <p style="margin: 5px 0;"><strong>Due Date:</strong> <span style="color: #dc2626;">{{ $due_date }}</span></p>
            
            @if($task_desc)
                <p style="margin: 10px 0 0 0;"><strong>Description:</strong><br> {!! nl2br(e($task_desc)) !!}</p>
            @endif
        </div>

        <p>Please log in to your dashboard to view your complete task list and update the status.</p>

        <!-- Login Button -->
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $login_url }}" style="display: inline-block; padding: 12px 24px; background: #3b82f6; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: bold;">
                View My Tasks
            </a>
        </div>

        <p style="margin-top: 30px; font-size: 0.9em; color: #64748b;">
            Regards,<br>
            <strong>{{ $site_name }} Team</strong>
        </p>

    </div>

</body>
</html>