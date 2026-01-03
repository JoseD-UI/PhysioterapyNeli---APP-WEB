<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\Facturacion\ComprobanteService;
use App\Repositories\Facturacion\FacturacionRepository;
use App\Services\Inventario\InventarioService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class ComprobanteServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ComprobanteService $service;
    protected $repositoryMock;
    protected $inventarioMock;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->repositoryMock = Mockery::mock(FacturacionRepository::class);
        $this->inventarioMock = Mockery::mock(InventarioService::class);
        
        $this->service = new ComprobanteService(
            $this->repositoryMock,
            $this->inventarioMock
        );
    }

    public function test_validates_tipo_comprobante(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Tipo de comprobante inválido');

        $this->service->emitir([
            'tipo_comprobante' => '99', // Invalid type
            'detalles' => []
        ]);
    }

    public function test_factura_validates_ruc_requirement(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Factura requiere RUC');

        $this->repositoryMock
            ->shouldReceive('obtenerSerieYCorrelativo')
            ->never();

        $this->service->emitir([
            'tipo_comprobante' => '01',
            // Sin RUC
            'detalles' => []
        ]);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
