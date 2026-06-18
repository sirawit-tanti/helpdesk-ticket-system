<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Role;
use App\Models\TicketCategory;
use App\Models\TicketPriority;
use App\Models\TicketStatus;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'description' => 'Can manage users, master data, and all tickets.',
            ],
            [
                'name' => 'agent',
                'display_name' => 'Support Agent',
                'description' => 'Can handle assigned tickets and respond to users.',
            ],
            [
                'name' => 'requester',
                'display_name' => 'Requester',
                'description' => 'Can create and track own tickets.',
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['name' => $role['name']],
                [
                    'display_name' => $role['display_name'],
                    'description' => $role['description'],
                    'is_active' => true,
                ]
            );
        }

        $departments = [
            ['name' => 'IT', 'description' => 'Information Technology department.'],
            ['name' => 'HR', 'description' => 'Human Resources department.'],
            ['name' => 'Finance', 'description' => 'Finance and accounting department.'],
            ['name' => 'Operations', 'description' => 'Operations department.'],
        ];

        foreach ($departments as $department) {
            Department::updateOrCreate(
                ['name' => $department['name']],
                [
                    'description' => $department['description'],
                    'is_active' => true,
                ]
            );
        }

        $categories = [
            ['name' => 'Hardware', 'description' => 'Computer, printer, or device issues.'],
            ['name' => 'Software', 'description' => 'Application or software problems.'],
            ['name' => 'Network', 'description' => 'Internet, Wi-Fi, VPN, or LAN issues.'],
            ['name' => 'Account', 'description' => 'Login, password, or permission requests.'],
            ['name' => 'Other', 'description' => 'Other support requests.'],
        ];

        foreach ($categories as $category) {
            TicketCategory::updateOrCreate(
                ['name' => $category['name']],
                [
                    'description' => $category['description'],
                    'is_active' => true,
                ]
            );
        }

        $priorities = [
            [
                'name' => 'Low',
                'level' => 1,
                'sla_hours' => 168,
                'color' => 'secondary',
            ],
            [
                'name' => 'Medium',
                'level' => 2,
                'sla_hours' => 72,
                'color' => 'primary',
            ],
            [
                'name' => 'High',
                'level' => 3,
                'sla_hours' => 24,
                'color' => 'warning',
            ],
            [
                'name' => 'Critical',
                'level' => 4,
                'sla_hours' => 4,
                'color' => 'danger',
            ],
        ];

        foreach ($priorities as $priority) {
            TicketPriority::updateOrCreate(
                ['name' => $priority['name']],
                [
                    'level' => $priority['level'],
                    'sla_hours' => $priority['sla_hours'],
                    'color' => $priority['color'],
                    'is_active' => true,
                ]
            );
        }

        $statuses = [
            [
                'name' => 'Open',
                'color' => 'primary',
                'is_closed' => false,
                'sort_order' => 1,
            ],
            [
                'name' => 'In Progress',
                'color' => 'info',
                'is_closed' => false,
                'sort_order' => 2,
            ],
            [
                'name' => 'Pending',
                'color' => 'warning',
                'is_closed' => false,
                'sort_order' => 3,
            ],
            [
                'name' => 'Resolved',
                'color' => 'success',
                'is_closed' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Closed',
                'color' => 'secondary',
                'is_closed' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Cancelled',
                'color' => 'dark',
                'is_closed' => true,
                'sort_order' => 6,
            ],
        ];

        foreach ($statuses as $status) {
            TicketStatus::updateOrCreate(
                ['name' => $status['name']],
                [
                    'color' => $status['color'],
                    'is_closed' => $status['is_closed'],
                    'sort_order' => $status['sort_order'],
                    'is_active' => true,
                ]
            );
        }
    }
}