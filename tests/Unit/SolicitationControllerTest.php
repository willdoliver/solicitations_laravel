<?php

namespace Tests\Unit;

use App\Models\Solicitation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;
use App\Http\Controllers\SolicitationController;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SolicitationControllerTest extends TestCase
{
    use RefreshDatabase;

    private $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->create([
            'name' => 'Test',
            'email' => 'test@example.com',
            'password' => Hash::make('test'),
        ]);
        Auth::login($user);

        $this->controller = new SolicitationController();
    }

    public function test_store_with_valid_data(): void
    {
        $request = new Request([
            'title' => 'Valid Title',
            'description' => 'Valid Description',
            'category' => 'TI',
        ]);
        $request->headers->set('Accept', 'application/json');

        $response = $this->controller->store($request);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $responseData = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('id', $responseData);
        $this->assertIsInt($responseData['id']);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Solicitação criada com sucesso!', $responseData['message']);
    }

    public function test_store_with_huge_description(): void
    {
        $request = new Request([
            'title' => 'Erro ao cadastrar novo colaborador',
            'description' => 'At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae. Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat.',
            'category' => 'TI'
        ]);
        $request->headers->set('Accept', 'application/json');
        $response = $this->controller->store($request);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $responseData = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('id', $responseData);
        $this->assertIsInt($responseData['id']);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Solicitação criada com sucesso!', $responseData['message']);
    }

    public function test_store_without_title(): void
    {
        $request = new Request([
            'title' => '',
            'description' => 'Estou tentando acessar o sistema',
            'category' => 'TI',
        ]);
        $request->headers->set('Accept', 'application/json');

        try {
            $this->controller->store($request);
        } catch (ValidationException $e) {
            $this->assertEquals(422, $e->status);
            $this->assertArrayHasKey('title', $e->errors());
            $this->assertEquals('O Título é obrigatório', $e->errors()['title'][0]);
            return;
        }

        $this->fail('Validation exception was not thrown.');
    }

    public function test_store_without_category(): void
    {
        $request = new Request([
            'title' => 'Acesso ao sistema',
            'description' => 'Estou tentando acessar o sistema',
            'category' => '',
        ]);
        $request->headers->set('Accept', 'application/json');

        try {
            $this->controller->store($request);
        } catch (ValidationException $e) {
            $this->assertEquals(422, $e->status);
            $this->assertArrayHasKey('category', $e->errors());
            $this->assertEquals('O campo Categoria é obrigatório', $e->errors()['category'][0]);
            return;
        }

        $this->fail('Validation exception was not thrown.');
    }

    public function test_store_with_invalid_category(): void
    {
        $request = new Request([
            'title' => 'Acesso ao sistema',
            'description' => 'Estou tentando acessar o sistema',
            'category' => 'FINANCEIRO',
        ]);
        $request->headers->set('Accept', 'application/json');

        try {
            $this->controller->store($request);
        } catch (ValidationException $e) {
            $this->assertEquals(422, $e->status);
            $this->assertArrayHasKey('category', $e->errors());
            $this->assertEquals('A Categoria selecionada é inválida', $e->errors()['category'][0]);
            return;
        }

        $this->fail('Validation exception was not thrown.');
    }

    public function test_update_with_valid_data(): void 
    {
        $solicitation = Solicitation::factory()->create();

        $request = new Request([
            'title' => 'Valid Title updated',
            'description' => 'Valid Description updated',
            'status' => 'em_andamento',
            'category' => 'TI',
        ]);
        $request->headers->set('Accept', 'application/json');

        $response = $this->controller->update($request, $solicitation->id);

        // dd([
        //     $response->getStatusCode(),
        //     $response->getContent()
        // ]);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $responseData = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('id', $responseData);
        $this->assertIsInt($responseData['id']);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Solicitação atualizada com sucesso!', $responseData['message']);
    }

    public function test_update_with_invalid_id(): void 
    {
        $request = new Request([
            'title' => 'Valid Title updated',
            'description' => 'Valid Description updated',
            'status' => 'aberta',
            'category' => 'TI',
        ]);
        $request->headers->set('Accept', 'application/json');
        $id = 99;

        $response = $this->controller->update($request, $id);

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $responseData = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Solicitação não encontrada', $responseData['message']);
    }

    public function test_update_without_title(): void
    {
        $solicitation = Solicitation::factory()->create();

        $request = new Request([
            'title' => '',
            'description' => 'Valid Description updated',
            'status' => 'em_andamento',
            'category' => 'TI',
        ]);
        $request->headers->set('Accept', 'application/json');

        try {
            $this->controller->update($request, $solicitation->id);
        } catch (ValidationException $e) {
            $this->assertEquals(422, $e->status);
            $this->assertArrayHasKey('title', $e->errors());
            $this->assertEquals('O Título é obrigatório', $e->errors()['title'][0]);
            return;
        }
    }

    public function test_update_without_category(): void
    {
        $solicitation = Solicitation::factory()->create();

        $request = new Request([
            'title' => 'Valid Description',
            'description' => 'Valid Description updated',
            'status' => 'em_andamento',
            'category' => '',
        ]);
        $request->headers->set('Accept', 'application/json');

        try {
            $this->controller->update($request, $solicitation->id);
        } catch (ValidationException $e) {
            $this->assertEquals(422, $e->status);
            $this->assertArrayHasKey('category', $e->errors());
            $this->assertEquals('O campo Categoria é obrigatório', $e->errors()['category'][0]);
            return;
        }
    }

    public function test_update_with_invalid_category(): void
    {
        $solicitation = Solicitation::factory()->create();

        $request = new Request([
            'title' => 'Valid Description',
            'description' => 'Valid Description updated',
            'status' => 'em_andamento',
            'category' => 'INFORMATICA',
        ]);
        $request->headers->set('Accept', 'application/json');

        try {
            $this->controller->update($request, $solicitation->id);
        } catch (ValidationException $e) {
            $this->assertEquals(422, $e->status);
            $this->assertArrayHasKey('category', $e->errors());
            $this->assertEquals('A Categoria selecionada é inválida', $e->errors()['category'][0]);
            return;
        }
    }

    public function test_update_without_status(): void
    {
        $solicitation = Solicitation::factory()->create();

        $request = new Request([
            'title' => 'Valid Description',
            'description' => 'Valid Description updated',
            'status' => '',
            'category' => 'RH',
        ]);
        $request->headers->set('Accept', 'application/json');

        try {
            $this->controller->update($request, $solicitation->id);
        } catch (ValidationException $e) {
            $this->assertEquals(422, $e->status);
            $this->assertArrayHasKey('status', $e->errors());
            $this->assertEquals('O Status é obrigatório', $e->errors()['status'][0]);
            return;
        }
    }

    public function test_update_with_invalid_status(): void
    {
        $solicitation = Solicitation::factory()->create();

        $request = new Request([
            'title' => 'Valid Description',
            'description' => 'Valid Description updated',
            'status' => 'finalizado',
            'category' => 'RH',
        ]);
        $request->headers->set('Accept', 'application/json');

        try {
            $this->controller->update($request, $solicitation->id);
        } catch (ValidationException $e) {
            $this->assertEquals(422, $e->status);
            $this->assertArrayHasKey('status', $e->errors());
            $this->assertEquals('O Status selecionado é inválido', $e->errors()['status'][0]);
            return;
        }
    }

    public function test_index_filters_by_search_term(): void
    {
        Solicitation::factory()->create(['title' => 'Solicitação 1', 'description' => 'Descrição 1']);
        Solicitation::factory()->create(['title' => 'Solicitação 2', 'description' => 'Descrição 2']);
        Solicitation::factory()->create(['title' => 'Outra Solicitação', 'description' => 'Outra Descrição']);

        $request = new Request(['search' => 'Solicitação']);
        $response = $this->controller->index($request);

        $this->assertInstanceOf(\Illuminate\View\View::class, $response);
        $this->assertArrayHasKey('solicitations', $response->getData());
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $response->getData()['solicitations']);
        $this->assertCount(3, $response->getData()['solicitations']);

        // Search for "Descrição 1"
        $request = new Request(['search' => 'Descrição 1']);
        $response = $this->controller->index($request);
        $this->assertCount(1, $response->getData()['solicitations']);

        // Search for "Descrição 2"
        $request = new Request(['search' => 'Descrição 2']);
        $response = $this->controller->index($request);
        $this->assertCount(1, $response->getData()['solicitations']);

        // Search for "Outra"
        $request = new Request(['search' => 'Outra']);
        $response = $this->controller->index($request);
        $this->assertCount(1, $response->getData()['solicitations']);

        // Search for "inexistente"
        $request = new Request(['search' => 'inexistente']);
        $response = $this->controller->index($request);
        $this->assertCount(0, $response->getData()['solicitations']);
    }

}
