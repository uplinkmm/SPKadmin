<?php

namespace App\Repositories\Notification;

use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\NotificationPerson;
use Illuminate\Support\Facades\DB;

class NotificationRepository implements NotificationInterface
{

    public function list($request)
    {
        // if ((int) $request->is_count == 1) {
        //     NotificationPerson::where('personable_type', 'user')
        //         ->join('notifications', 'notification_people.notification_id', 'notifications.id')
        //         ->where('notifications.notificationable_type', $request->type)
        //         ->where('is_read_count', 0)
        //         ->update(['is_read_count' => 1]);
        // }
        // $topupTransactionType = 'topup_transaction';
        // $withdrawlTransactionType = 'cash_withdrawl_transaction';
        $perPage = $request->per_page ?? config('common.per_page');
        $type = $request->type;
        if (ApiUser()) {
            $userId = ApiUser()->id;
        } else {
            ResponseMessage('Please login to continue',401);
            // return response()->json(['message' => 'Unauthorized'], 401);
        }
        // $userId = ApiUser()->id;
        $notificationQuery = NotificationPerson::with([
            'personable',
        ])
            ->join('notifications', 'notification_people.notification_id', 'notifications.id')
            ->where('notifications.notificationable_type', $type)
            ->where('notification_people.personable_id', $userId)
            ->when($type == 'topup_transaction', function ($q) use ($type) {
                $q->leftJoin('topup_transactions', function ($join) use ($type) {
                    $join->on('notifications.notificationable_id', '=', 'topup_transactions.id')
                        ->where('notifications.notificationable_type', '=', $type); // Adjust namespace if different
                })
                    ->leftJoin('customers', 'topup_transactions.customer_id', '=', 'customers.id')
                    ->leftJoin('accounts', 'topup_transactions.account_id', '=', 'accounts.id');
            })
            ->when($type == 'cash_withdrawl_transaction', function ($q) use ($type) {
                $q->leftJoin('cash_withdrawl_transactions', function ($join) use ($type) {
                    $join->on('notifications.notificationable_id', '=', 'cash_withdrawl_transactions.id')
                        ->where('notifications.notificationable_type', '=', $type); // Adjust namespace if different
                })
                    ->leftJoin('customers', 'cash_withdrawl_transactions.customer_id', '=', 'customers.id')
                    ->leftJoin('accounts', 'cash_withdrawl_transactions.account_id', '=', 'accounts.id');
            })
            ->where('notification_people.personable_type', 'user') // Filter for 'user' personable_type here
            ->select(
                'notification_people.id',
                'notifications.id as notification_id',
                'is_read',
                'is_read_count',
                'notifications.title',
                'notifications.preview',
                'notifications.date_time',
                'notification_people.personable_type',
                'notification_people.personable_id',
                // 'customers.name as customer_name',
                // 'accounts.name as account_name',
            )
            ->when(in_array($type, ['topup_transaction', 'cash_withdrawl_transaction']), function ($q) {
                $q->addSelect([
                    'customers.name as customer_name',
                    'accounts.name as account_name',
                ]);
            })
            ->orderBy('notification_people.id', 'desc');
        $notifications = $notificationQuery->paginate($perPage);
        $countOfUnRead = $notificationQuery->clone()->where('is_read', 0)->count();
        // $notificationWithdrawl = $notificationWithdrawlQuery->paginate(config('common.per_page'));
        // $countOfWithdrawlUnRead = $notificationWithdrawlQuery->clone()->where('is_read_count', 0)->count();
        // ->get();

        $data['count'] = $countOfUnRead;
        $data['notifications'] = $notifications;
        // $data['withdrawal_count'] = $countOfWithdrawlUnRead;
        // $data['witthdrawal_notifications'] = $notificationWithdrawl;
        return $data;
    }

    public function readNotificaiton($request)
    {

        $notify = NotificationPerson::orderBy('notification_people.id', 'desc')
            ->join('notifications', 'notification_people.notification_id', 'notifications.id')
            ->where('notification_people.personable_type', 'user') // Filter for 'user' personable_type here
            ->where('notifications.notificationable_type', $request->type)
            ->when($request->id == 0 || $request->id == "0", function ($q) {
                $q->where('is_read', 0);
            })
            ->when($request->id != 0 || $request->id != "0", function ($q) use ($request) {
                $q->where('notification_people.id', $request->id);
            })
            // ->get();
            ->update(
                [
                    'is_read' => 1,
                ]
            );
        $customRequest = new Request();
        $customRequest['type'] = $request->type;
        return $this->list($customRequest);
    }

}
