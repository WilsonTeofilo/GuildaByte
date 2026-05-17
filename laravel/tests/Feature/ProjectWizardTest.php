<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ProjectWizardTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_submit_wizard_safely()
    {
        $user = User::factory()->create(['user_type' => 'client']);

        $payload = [
            'pack' => 'core',
            'name' => 'Projeto <script>alert("xss")</script> Teste',
            'desc' => 'Um projeto muito legal <b>com negrito</b>',
            'features' => ['Vender', 'Agendar <iframe src="evil.com"></iframe>'],
            'ref1' => 'https://google.com',
            'ref2' => 'http://evil.com/xss',
            'has_id' => 'sim',
        ];

        $response = $this->actingAs($user)->postJson(route('client.wizard.store'), $payload);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        // Verificar se os dados foram salvos no DB
        $this->assertDatabaseHas('projects', [
            'client_id' => $user->id,
            'agreed_package_name' => 'CORE',
        ]);

        $project = Project::where('client_id', $user->id)->first();

        // Verificar Sanitização XSS e formatação segura
        $this->assertStringNotContainsString('<script>', $project->name);
        $this->assertStringNotContainsString('<iframe>', $project->description);
        $this->assertStringNotContainsString('<b>', $project->description);

        $this->assertEquals('Projeto alert("xss") Teste', $project->name);
        $this->assertStringContainsString('Um projeto muito legal com negrito', $project->description);
        $this->assertStringContainsString('Vender', $project->description);
        $this->assertStringContainsString('Agendar', $project->description);
        $this->assertStringContainsString('https://google.com', $project->description);
    }
    
    public function test_wizard_blocks_invalid_data()
    {
        $user = User::factory()->create(['user_type' => 'client']);

        $payload = [
            'pack' => 'hacked_pack', // Invalid pack
            'name' => str_repeat('A', 300), // Too long
        ];

        $response = $this->actingAs($user)->postJson(route('client.wizard.store'), $payload);

        $response->assertStatus(422); // Unprocessable Entity (Validation Error)
        $response->assertJsonValidationErrors(['pack', 'name']);
    }
}
