<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Role;
use App\Models\Ticket;
use App\Models\TicketActivityLog;
use App\Models\TicketCategory;
use App\Models\TicketComment;
use App\Models\TicketPriority;
use App\Models\TicketStatus;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->firstOrFail();
        $agentRole = Role::where('name', 'agent')->firstOrFail();
        $requesterRole = Role::where('name', 'requester')->firstOrFail();

        $itDepartment = Department::where('name', 'IT')->firstOrFail();
        $hrDepartment = Department::where('name', 'HR')->firstOrFail();
        $financeDepartment = Department::where('name', 'Finance')->firstOrFail();
        $operationsDepartment = Department::where('name', 'Operations')->firstOrFail();

        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('password'),
                'role_id' => $adminRole->id,
                'department_id' => $itDepartment->id,
                'is_active' => true,
            ]
        );

        $agentOne = User::updateOrCreate(
            ['email' => 'agent@example.com'],
            [
                'name' => 'Demo Agent',
                'password' => Hash::make('password'),
                'role_id' => $agentRole->id,
                'department_id' => $itDepartment->id,
                'is_active' => true,
            ]
        );

        $agentTwo = User::updateOrCreate(
            ['email' => 'agent2@example.com'],
            [
                'name' => 'Support Agent Two',
                'password' => Hash::make('password'),
                'role_id' => $agentRole->id,
                'department_id' => $operationsDepartment->id,
                'is_active' => true,
            ]
        );

        $requesterOne = User::updateOrCreate(
            ['email' => 'requester@example.com'],
            [
                'name' => 'Demo Requester',
                'password' => Hash::make('password'),
                'role_id' => $requesterRole->id,
                'department_id' => $financeDepartment->id,
                'is_active' => true,
            ]
        );

        $requesterTwo = User::updateOrCreate(
            ['email' => 'requester2@example.com'],
            [
                'name' => 'HR Requester',
                'password' => Hash::make('password'),
                'role_id' => $requesterRole->id,
                'department_id' => $hrDepartment->id,
                'is_active' => true,
            ]
        );

        $inactiveRequester = User::updateOrCreate(
            ['email' => 'inactive@example.com'],
            [
                'name' => 'Inactive Requester',
                'password' => Hash::make('password'),
                'role_id' => $requesterRole->id,
                'department_id' => $financeDepartment->id,
                'is_active' => false,
            ]
        );

        $hardwareCategory = TicketCategory::where('name', 'Hardware')->firstOrFail();
        $softwareCategory = TicketCategory::where('name', 'Software')->firstOrFail();
        $networkCategory = TicketCategory::where('name', 'Network')->firstOrFail();
        $accountCategory = TicketCategory::where('name', 'Account')->firstOrFail();
        $otherCategory = TicketCategory::where('name', 'Other')->firstOrFail();

        $lowPriority = TicketPriority::where('name', 'Low')->firstOrFail();
        $mediumPriority = TicketPriority::where('name', 'Medium')->firstOrFail();
        $highPriority = TicketPriority::where('name', 'High')->firstOrFail();
        $criticalPriority = TicketPriority::where('name', 'Critical')->firstOrFail();

        $openStatus = TicketStatus::where('name', 'Open')->firstOrFail();
        $inProgressStatus = TicketStatus::where('name', 'In Progress')->firstOrFail();
        $pendingStatus = TicketStatus::where('name', 'Pending')->firstOrFail();
        $resolvedStatus = TicketStatus::where('name', 'Resolved')->firstOrFail();
        $closedStatus = TicketStatus::where('name', 'Closed')->firstOrFail();

        $tickets = [
            [
                'ticket_no' => 'TCK-20260618-0001',
                'requester_id' => $requesterOne->id,
                'assignee_id' => null,
                'department_id' => $itDepartment->id,
                'ticket_category_id' => $hardwareCategory->id,
                'ticket_priority_id' => $mediumPriority->id,
                'ticket_status_id' => $openStatus->id,
                'title' => 'Laptop keyboard is not working',
                'description' => 'The keyboard on my company laptop stopped responding after startup. External keyboard works normally.',
                'due_at' => now()->addDays(3),
                'resolved_at' => null,
                'closed_at' => null,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'ticket_no' => 'TCK-20260618-0002',
                'requester_id' => $requesterTwo->id,
                'assignee_id' => $agentOne->id,
                'department_id' => $itDepartment->id,
                'ticket_category_id' => $softwareCategory->id,
                'ticket_priority_id' => $highPriority->id,
                'ticket_status_id' => $inProgressStatus->id,
                'title' => 'Cannot access HR portal',
                'description' => 'I can login to the company network, but the HR portal shows an authorization error.',
                'due_at' => now()->addDays(1),
                'resolved_at' => null,
                'closed_at' => null,
                'created_at' => now()->subDays(4),
                'updated_at' => now()->subDays(2),
            ],
            [
                'ticket_no' => 'TCK-20260618-0003',
                'requester_id' => $requesterOne->id,
                'assignee_id' => $agentOne->id,
                'department_id' => $itDepartment->id,
                'ticket_category_id' => $networkCategory->id,
                'ticket_priority_id' => $criticalPriority->id,
                'ticket_status_id' => $pendingStatus->id,
                'title' => 'VPN disconnects every few minutes',
                'description' => 'VPN connection drops repeatedly when accessing internal systems from home.',
                'due_at' => now()->addHours(8),
                'resolved_at' => null,
                'closed_at' => null,
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDay(),
            ],
            [
                'ticket_no' => 'TCK-20260618-0004',
                'requester_id' => $requesterTwo->id,
                'assignee_id' => $agentTwo->id,
                'department_id' => $operationsDepartment->id,
                'ticket_category_id' => $accountCategory->id,
                'ticket_priority_id' => $lowPriority->id,
                'ticket_status_id' => $resolvedStatus->id,
                'title' => 'Request to reset shared mailbox password',
                'description' => 'The team shared mailbox password needs to be reset for the new support rotation.',
                'due_at' => now()->addDays(2),
                'resolved_at' => now()->subHours(8),
                'closed_at' => null,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subHours(8),
            ],
            [
                'ticket_no' => 'TCK-20260618-0005',
                'requester_id' => $requesterOne->id,
                'assignee_id' => $agentTwo->id,
                'department_id' => $financeDepartment->id,
                'ticket_category_id' => $otherCategory->id,
                'ticket_priority_id' => $mediumPriority->id,
                'ticket_status_id' => $closedStatus->id,
                'title' => 'Printer setup completed',
                'description' => 'New printer setup request for the finance team area.',
                'due_at' => now()->subDay(),
                'resolved_at' => now()->subDay(),
                'closed_at' => now()->subHours(12),
                'created_at' => now()->subDays(6),
                'updated_at' => now()->subHours(12),
            ],
        ];

        foreach ($tickets as $ticketData) {
            $ticket = Ticket::updateOrCreate(
                ['ticket_no' => $ticketData['ticket_no']],
                $ticketData
            );

            TicketActivityLog::updateOrCreate(
                [
                    'ticket_id' => $ticket->id,
                    'action' => 'created',
                    'field' => null,
                    'old_value' => null,
                    'new_value' => 'Ticket created',
                ],
                [
                    'user_id' => $ticket->requester_id,
                    'created_at' => $ticket->created_at,
                    'updated_at' => $ticket->created_at,
                ]
            );
        }

        $this->seedTicketConversation(
            ticketNo: 'TCK-20260618-0001',
            requester: $requesterOne,
            agent: $agentOne,
            admin: $admin
        );

        $this->seedTicketConversation(
            ticketNo: 'TCK-20260618-0002',
            requester: $requesterTwo,
            agent: $agentOne,
            admin: $admin
        );

        $this->seedTicketConversation(
            ticketNo: 'TCK-20260618-0003',
            requester: $requesterOne,
            agent: $agentOne,
            admin: $admin
        );
    }

    private function seedTicketConversation(
        string $ticketNo,
        User $requester,
        User $agent,
        User $admin
    ): void {
        $ticket = Ticket::where('ticket_no', $ticketNo)->firstOrFail();

        $firstComment = TicketComment::updateOrCreate(
            [
                'ticket_id' => $ticket->id,
                'user_id' => $requester->id,
                'message' => 'I have attached the details and need help with this issue.',
            ],
            [
                'is_internal' => false,
                'created_at' => $ticket->created_at->copy()->addMinutes(10),
                'updated_at' => $ticket->created_at->copy()->addMinutes(10),
            ]
        );

        TicketActivityLog::updateOrCreate(
            [
                'ticket_id' => $ticket->id,
                'action' => 'comment_added',
                'field' => null,
                'old_value' => null,
                'new_value' => 'Comment added',
            ],
            [
                'user_id' => $requester->id,
                'created_at' => $firstComment->created_at,
                'updated_at' => $firstComment->created_at,
            ]
        );

        $internalNote = TicketComment::updateOrCreate(
            [
                'ticket_id' => $ticket->id,
                'user_id' => $agent->id,
                'message' => 'Initial check completed. Need to verify user permissions and related service status.',
            ],
            [
                'is_internal' => true,
                'created_at' => $ticket->created_at->copy()->addHours(1),
                'updated_at' => $ticket->created_at->copy()->addHours(1),
            ]
        );

        TicketActivityLog::updateOrCreate(
            [
                'ticket_id' => $ticket->id,
                'action' => 'internal_note_added',
                'field' => null,
                'old_value' => null,
                'new_value' => 'Internal note added',
            ],
            [
                'user_id' => $agent->id,
                'created_at' => $internalNote->created_at,
                'updated_at' => $internalNote->created_at,
            ]
        );

        TicketActivityLog::updateOrCreate(
            [
                'ticket_id' => $ticket->id,
                'action' => 'updated',
                'field' => 'assignee',
                'old_value' => 'Unassigned',
                'new_value' => $agent->name,
            ],
            [
                'user_id' => $admin->id,
                'created_at' => $ticket->created_at->copy()->addMinutes(30),
                'updated_at' => $ticket->created_at->copy()->addMinutes(30),
            ]
        );
    }
}