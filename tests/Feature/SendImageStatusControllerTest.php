<?php

use App\Services\Monitoring\SendImageStatusService;
use Mockery\MockInterface;

it('returns fixed detail json from send image status service', function () {
    $this->withoutMiddleware();

    $mock = Mockery::mock(SendImageStatusService::class, function (MockInterface $mock) {
        $mock->shouldReceive('detailFixed')
            ->once()
            ->with(12, 'all', 'all')
            ->andReturn([
                ['id' => 1, 'user_id' => 12],
            ]);
    });

    app()->instance(SendImageStatusService::class, $mock);

    $this->getJson(route('admin.api.v1.check.detail', [
        'user_id' => 12,
        'month' => 'all',
        'year' => 'all',
    ]))->assertOk()->assertJson([
        'status' => true,
        'data' => [
            ['id' => 1, 'user_id' => 12],
        ],
    ]);
});
