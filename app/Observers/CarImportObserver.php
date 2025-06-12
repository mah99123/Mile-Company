<?php

namespace App\Observers;

use App\Models\CarImport;
use App\Services\NotificationService;

class CarImportObserver
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function created(CarImport $carImport)
    {
        $this->notificationService->carImportCreated($carImport);
    }

    public function updated(CarImport $carImport)
    {
        if ($carImport->wasChanged('shipping_status')) {
            switch ($carImport->shipping_status) {
                case 'Shipped':
                    $this->notificationService->carShipped($carImport);
                    break;
                case 'Arrived':
                    $this->notificationService->carArrived($carImport);
                    break;
                case 'Delivered':
                    $this->notificationService->sendToUsersWithPermission('access carimport', 'car_delivered',
                        'تم تسليم السيارة',
                        "تم تسليم السيارة برقم اللوط {$carImport->lot_number} للعميل",
                        ['car_import_id' => $carImport->id],
                        ['color' => 'success']
                    );
                    break;
            }
        }
    }
}
