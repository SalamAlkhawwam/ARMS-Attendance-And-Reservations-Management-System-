<?php

namespace App\Services;

use App\Models\InterestedService;
use App\Models\Service;

class InterestedServiceService
{
    protected $controllerService;

    public function __construct(ControllerService $controllerService)
    {
        $this->controllerService = $controllerService;
    }

    public function showAll()
    {
        $allRecords = InterestedService::whereHas('service', function ($query) {
            $query->whereNull('parentServiceID')
                ->where('status', 1);
        })
            ->where('userID', auth()->id())
            ->with('service')
            ->get();

        foreach ($allRecords as $record) {
            $record->service['children'] = Service::where('parentServiceID', $record->service['id'])
                ->where('status', 1)
                ->get();
        }
        return $allRecords;
    }

    public function showAllParent()
    {
        $allRecords = Service::with(['interestedService', 'serviceManager.user', 'parentService', 'serviceYearAndSpecialization', 'assignedService.user', 'assignedService.assignedRole.role'])
            ->whereHas('interestedService', function ($query) {
                $query->where('userID', auth()->id());
            })
            ->whereNull('parentServiceID')
            ->orderByDesc('status')
            ->get();

        return $this->controllerService->getServiceData($allRecords);
    }

    public function showChild(Service $service)
    {
        $allRecords = Service::with(['interestedService', 'serviceManager.user', 'parentService', 'serviceYearAndSpecialization', 'assignedService.user', 'assignedService.assignedRole.role'])
            ->where('parentServiceID', $service['id'])
            ->orderByDesc('status')
            ->get();

        return $this->controllerService->getServiceData($allRecords);
    }

    public function interestIn(Service $service)
    {
        $data['userID'] = auth()->id();

        $data['serviceID'] = $service['id'];

        return InterestedService::create($data);
    }

    public function unInterestIn(InterestedService $interestedService)
    {
        return $interestedService->delete();
    }
}
