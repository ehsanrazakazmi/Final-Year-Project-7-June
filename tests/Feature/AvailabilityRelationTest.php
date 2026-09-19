<?php

namespace Tests\Feature;

use App\Models\Availability;
use App\Models\Services;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * The colours CRUD was repurposed as technician availability and renamed, which
 * moved the pivot table and both of its keys.
 *
 * belongsToMany(related, pivot, foreignPivotKey, relatedPivotKey) - passing the
 * last two the wrong way round still "works" (no error, rows are written) but
 * silently stores swapped ids. Only reading the pivot back catches it.
 */
class AvailabilityRelationTest extends TestCase
{
    use RefreshDatabase;

    private function makeService(string $title = 'AC Repair'): Services
    {
        return Services::create([
            'title' => $title,
            'category_id' => 1,
            'price' => 2500,
            'description' => 'test service',
            'image' => 'products/test.png',
        ]);
    }

    public function test_attaching_availabilities_writes_the_pivot_the_right_way_round(): void
    {
        $service = $this->makeService();
        $morning = Availability::create(['name' => 'subah', 'available_from' => '05:00', 'available_to' => '09:00']);
        $evening = Availability::create(['name' => 'shaam', 'available_from' => '17:00', 'available_to' => '21:00']);

        $service->availabilities()->attach([$morning->id, $evening->id]);

        $rows = DB::table('availability_services')->get();

        $this->assertCount(2, $rows);

        foreach ($rows as $row) {
            $this->assertSame($service->id, (int) $row->services_id, 'services_id holds the wrong id');
            $this->assertContains((int) $row->availability_id, [$morning->id, $evening->id]);
        }
    }

    public function test_a_service_reads_back_its_own_availabilities(): void
    {
        $service = $this->makeService();
        $other = $this->makeService('Plumbing');

        $morning = Availability::create(['name' => 'subah', 'available_from' => '05:00', 'available_to' => '09:00']);
        $evening = Availability::create(['name' => 'shaam', 'available_from' => '17:00', 'available_to' => '21:00']);

        $service->availabilities()->attach($morning->id);
        $other->availabilities()->attach($evening->id);

        $names = $service->fresh()->availabilities->pluck('name')->all();

        $this->assertSame(['subah'], $names, 'a service picked up another service\'s availability');
    }

    public function test_the_inverse_relation_works(): void
    {
        $service = $this->makeService();
        $morning = Availability::create(['name' => 'subah', 'available_from' => '05:00', 'available_to' => '09:00']);

        $service->availabilities()->attach($morning->id);

        $this->assertSame(['AC Repair'], $morning->fresh()->Services->pluck('title')->all());
    }

    public function test_sync_replaces_rather_than_duplicates(): void
    {
        $service = $this->makeService();
        $a = Availability::create(['name' => 'a', 'available_from' => '01:00', 'available_to' => '02:00']);
        $b = Availability::create(['name' => 'b', 'available_from' => '03:00', 'available_to' => '04:00']);

        $service->availabilities()->attach($a->id);
        $service->availabilities()->sync([$b->id]);

        $this->assertSame(['b'], $service->fresh()->availabilities->pluck('name')->all());
        $this->assertSame(1, DB::table('availability_services')->count());
    }
}
