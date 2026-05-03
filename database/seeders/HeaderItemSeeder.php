<?php

namespace Database\Seeders;

use App\Models\HeaderItem;
use App\Models\User;
use Illuminate\Database\Seeder;

class HeaderItemSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            'Admin' => [
                ['type' => 'notification', 'title' => 'System activity needs review', 'body' => 'New user and role changes were logged today.', 'action_label' => 'Open users', 'action_url' => route('admin.users'), 'priority' => 'high'],
                ['type' => 'task', 'title' => 'Check platform settings', 'body' => 'Confirm market name and contact details are updated.', 'action_label' => 'Open settings', 'action_url' => route('admin.settings')],
                ['type' => 'message', 'title' => 'Manager update', 'body' => 'Monthly vendor records are ready for admin review.'],
            ],
            'Manager' => [
                ['type' => 'notification', 'title' => 'Contracts expiring soon', 'body' => 'Review vendors with contracts ending within 30 days.', 'action_label' => 'View map', 'action_url' => route('map.index', ['contract' => 'expiring']), 'priority' => 'high'],
                ['type' => 'task', 'title' => 'Update vendor records', 'body' => 'Check stall details, categories, and rental amounts.', 'action_label' => 'Open vendors', 'action_url' => route('manager.vendors.index')],
                ['type' => 'message', 'title' => 'Treasury note', 'body' => 'Collection summaries are available in reports.', 'action_label' => 'Open reports', 'action_url' => route('manager.reports')],
            ],
            'Collector' => [
                ['type' => 'notification', 'title' => 'Unpaid route ready', 'body' => 'Use the map to visit vendors with unpaid collections.', 'action_label' => 'Open route', 'action_url' => route('map.index', ['payment' => 'unpaid'])],
                ['type' => 'task', 'title' => 'Submit today\'s collections', 'body' => 'Record proof and remarks after every stall visit.', 'action_label' => 'Open collections', 'action_url' => route('collector.collections.index'), 'priority' => 'high'],
                ['type' => 'message', 'title' => 'Treasurer reminder', 'body' => 'Attach clear proof before submitting for verification.'],
            ],
            'Treasurer' => [
                ['type' => 'notification', 'title' => 'Collections awaiting verification', 'body' => 'Review submitted collections and create treasury records.', 'action_label' => 'Verify now', 'action_url' => route('treasurer.collections.index'), 'priority' => 'high'],
                ['type' => 'task', 'title' => 'Reconcile monthly totals', 'body' => 'Compare verified collections against manager reports.'],
                ['type' => 'message', 'title' => 'Collector note', 'body' => 'New proofs were submitted from today\'s route.'],
            ],
            'Vendor' => [
                ['type' => 'notification', 'title' => 'Product visibility check', 'body' => 'Keep available products and stock counts updated.', 'action_label' => 'Open products', 'action_url' => route('vendor.products.index')],
                ['type' => 'task', 'title' => 'Review stall profile', 'body' => 'Confirm contact details and contract information.'],
                ['type' => 'message', 'title' => 'Market office reminder', 'body' => 'Please settle any pending monthly rent collection.'],
            ],
            'Customer' => [
                ['type' => 'notification', 'title' => 'Favorite vendors updated', 'body' => 'Bookmarked vendors have fresh products to browse.', 'action_label' => 'Open bookmarks', 'action_url' => route('customer.bookmarks')],
                ['type' => 'task', 'title' => 'Review cart items', 'body' => 'Check quantities before placing your next order.', 'action_label' => 'Open cart', 'action_url' => route('customer.cart')],
                ['type' => 'message', 'title' => 'Welcome to LEEMO-PALASADA', 'body' => 'Use QR search and the market map to find stalls faster.'],
            ],
        ];

        User::query()->with('roles')->get()->each(function (User $user) use ($templates): void {
            $role = $user->primaryRole() ?? 'Customer';

            foreach ($templates[$role] ?? [] as $item) {
                HeaderItem::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'type' => $item['type'],
                        'title' => $item['title'],
                    ],
                    [
                        'role' => $role,
                        'body' => $item['body'] ?? null,
                        'action_label' => $item['action_label'] ?? null,
                        'action_url' => $item['action_url'] ?? null,
                        'priority' => $item['priority'] ?? 'normal',
                    ],
                );
            }
        });
    }
}
