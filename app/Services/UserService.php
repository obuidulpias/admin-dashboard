<?php

namespace App\Services;

class UserService
{
    /**
     * Filter and retrieve users based on request parameters
     *
     * @param bool $paginate Whether to paginate results
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function filter($query, $paginate = true)
    {
        // Apply filters
        if (request()->filled('user_id')) {
            $query->where('id', request('user_id'));
        }

        if (request()->filled('name')) {
            $query->where('name', 'like', '%' . request('name') . '%');
        }

        if (request()->filled('email')) {
            $query->where('email', request('email'));
        }

        if (request()->filled('role')) {
            $query->whereHas('roles', function($q) {
                $q->where('id', request('role'));
            });
        }

        if (request()->filled('created_date')) {
            $query->whereDate('created_at', request('created_date'));
        }

        // Apply ordering
        $orderBy = request('order_by', 'created_at');
        $orderDirection = request('order_direction', 'desc');
        $query->orderBy($orderBy, $orderDirection);

        // Return paginated or all results
        if ($paginate) {
            $perPage = request('per_page', 10);
            return $query->paginate($perPage)->appends(request()->except('page'));
        }

        return $query->get();
    }

    /**
     * Export users to CSV
     *
     * @param \Illuminate\Database\Eloquent\Collection $users
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportToCSV($users)
    {
        $filename = 'users_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, ['ID', 'Name', 'Email', 'Roles', 'Created At']);
            
            // Add data rows
            foreach ($users as $user) {
                $roles = $user->roles->pluck('name')->implode(', ');
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $roles ?: 'No roles',
                    $user->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

