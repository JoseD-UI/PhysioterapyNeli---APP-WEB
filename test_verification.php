<?php

use App\Models\Principal\Persona;
use App\Models\Principal\Usuario;
use App\Models\User;
use Illuminate\Support\Str;

echo "--- START VERIFICATION ---\n";

// 1. Test Natural Person Incomplete Logic
$p1 = new Persona();
$p1->tipo_persona = 'PACIENTE-NATURAL';
$p1->dni = null; 
echo "Natural Person Incomplete (No DNI): " . ($p1->isProfileComplete() ? 'FAIL' : 'PASS') . "\n";

$p1->dni = '12345678';
$p1->nombres = 'Juan';
$p1->apellidos = 'Perez';
$p1->fecha_nacimiento = '2000-01-01';
$p1->telefono = '999999999';
$p1->direccion = 'Calle 123';
echo "Natural Person Complete: " . ($p1->isProfileComplete() ? 'PASS' : 'FAIL') . "\n";


// 2. Test Juridica Person Logic
$p2 = new Persona();
$p2->tipo_persona = 'PACIENTE-JURIDICA';
$p2->ruc = '1012345678'; // 10 digits
$p2->nombres = 'Empresa SAC';
$p2->telefono = '999';
$p2->direccion = 'Av';
echo "Juridica Person Incomplete (RUC 10): " . ($p2->isProfileComplete() ? 'FAIL' : 'PASS') . "\n";

$p2->ruc = '20123456789'; // 11 digits
echo "Juridica Person Complete (RUC 11): " . ($p2->isProfileComplete() ? 'PASS' : 'FAIL') . "\n";

echo "--- END VERIFICATION ---\n";
