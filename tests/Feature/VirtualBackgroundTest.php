<?php

namespace Tests\Feature;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Livewire\Livewire;

class VirtualBackgroundTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create an enterprise user
        $this->enterpriseUser = User::factory()->create([
            'role' => 'enterprise',
            'email' => 'enterprise@example.com'
        ]);
        
        // Create some profiles for the enterprise
        $this->profiles = Profile::factory()->count(3)->create([
            'enterprise_id' => $this->enterpriseUser->id,
            'name' => 'Test Profile',
            'email' => 'test@example.com',
            'address' => '123 Test Street',
            'job_title' => 'Software Developer'
        ]);
    }

    /** @test */
    public function enterprise_user_can_access_virtual_background_page()
    {
        $this->actingAs($this->enterpriseUser)
            ->get('/enterprise/virtual-background')
            ->assertStatus(200);
    }

    /** @test */
    public function virtual_background_component_loads_profiles()
    {
        $this->actingAs($this->enterpriseUser);

        Livewire::test('enterprise.virtual-background')
            ->assertSee('Test Profile')
            ->assertSee('test@example.com');
    }

    /** @test */
    public function user_can_select_profile()
    {
        $this->actingAs($this->enterpriseUser);

        Livewire::test('enterprise.virtual-background')
            ->call('selectProfile', $this->profiles->first()->id)
            ->assertSet('selectedProfile.id', $this->profiles->first()->id);
    }

    /** @test */
    public function element_toggles_work_correctly()
    {
        $this->actingAs($this->enterpriseUser);

        $component = Livewire::test('enterprise.virtual-background')
            ->call('selectProfile', $this->profiles->first()->id);

        // Test username toggle
        $component->call('toggleElement', 'username')
            ->assertSet('showUsername', false);

        // Test email toggle
        $component->call('toggleElement', 'email')
            ->assertSet('showEmail', false);

        // Test address toggle
        $component->call('toggleElement', 'address')
            ->assertSet('showAddress', false);

        // Test QR code toggle
        $component->call('toggleElement', 'qr_code')
            ->assertSet('showQRCode', false);
    }

    /** @test */
    public function background_template_selection_works()
    {
        $this->actingAs($this->enterpriseUser);

        Livewire::test('enterprise.virtual-background')
            ->call('selectProfile', $this->profiles->first()->id)
            ->call('selectBackgroundTemplate', 'creative')
            ->assertSet('selectedBackgroundTemplate', 'creative');
    }

    /** @test */
    public function element_positions_can_be_updated()
    {
        $this->actingAs($this->enterpriseUser);

        $component = Livewire::test('enterprise.virtual-background')
            ->call('selectProfile', $this->profiles->first()->id);

        $component->call('updateElementPosition', 'username', 100, 150)
            ->assertSet('elementPositions.username.x', 100)
            ->assertSet('elementPositions.username.y', 150);
    }

    /** @test */
    public function virtual_background_can_be_generated()
    {
        $this->actingAs($this->enterpriseUser);

        // Mock the HTTP request to the API
        $this->mock(\Illuminate\Support\Facades\Http::class, function ($mock) {
            $mock->shouldReceive('withHeaders')
                ->andReturnSelf();
            $mock->shouldReceive('post')
                ->andReturn(new \Illuminate\Http\Client\Response(
                    new \GuzzleHttp\Psr7\Response(200, [], json_encode([
                        'success' => true,
                        'download_url' => 'http://example.com/image.png',
                        'preview_url' => 'http://example.com/preview.png'
                    ]))
                ));
        });

        Livewire::test('enterprise.virtual-background')
            ->call('selectProfile', $this->profiles->first()->id)
            ->call('generateVirtualBackground')
            ->assertNotSet('isGenerating', true);
    }

    /** @test */
    public function preview_can_be_generated()
    {
        $this->actingAs($this->enterpriseUser);

        // Mock the HTTP request to the API
        $this->mock(\Illuminate\Support\Facades\Http::class, function ($mock) {
            $mock->shouldReceive('withHeaders')
                ->andReturnSelf();
            $mock->shouldReceive('post')
                ->andReturn(new \Illuminate\Http\Client\Response(
                    new \GuzzleHttp\Psr7\Response(200, [], json_encode([
                        'success' => true,
                        'preview_url' => 'http://example.com/preview.png'
                    ]))
                ));
        });

        Livewire::test('enterprise.virtual-background')
            ->call('selectProfile', $this->profiles->first()->id)
            ->call('generatePreview')
            ->assertNotSet('isGeneratingPreview', true);
    }

    /** @test */
    public function background_enabled_toggle_works()
    {
        $this->actingAs($this->enterpriseUser);

        $profile = $this->profiles->first();
        $profile->update(['virtual_background_enabled' => false]);

        Livewire::test('enterprise.virtual-background')
            ->call('selectProfile', $profile->id)
            ->call('toggleBackgroundEnabled')
            ->assertSet('selectedProfile.virtual_background_enabled', true);
    }
} 