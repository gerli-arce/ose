<?php

namespace Database\Seeders;

use App\Models\Ubigeo;
use Illuminate\Database\Seeder;

/**
 * Seeder de Ubigeos de Perú (INEI)
 * Incluye departamentos, provincias y distritos principales
 */
class UbigeoSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar tabla
        Ubigeo::truncate();

        // Lima (15)
        $lima = $this->createDepartment('15', 'LIMA');
        
        $limaMetro = $this->createProvince('1501', 'LIMA', $lima);
        $this->createDistrict('150101', 'LIMA', $limaMetro);
        $this->createDistrict('150102', 'ANCON', $limaMetro);
        $this->createDistrict('150103', 'ATE', $limaMetro);
        $this->createDistrict('150104', 'BARRANCO', $limaMetro);
        $this->createDistrict('150105', 'BREÑA', $limaMetro);
        $this->createDistrict('150106', 'CARABAYLLO', $limaMetro);
        $this->createDistrict('150107', 'CHACLACAYO', $limaMetro);
        $this->createDistrict('150108', 'CHORRILLOS', $limaMetro);
        $this->createDistrict('150109', 'CIENEGUILLA', $limaMetro);
        $this->createDistrict('150110', 'COMAS', $limaMetro);
        $this->createDistrict('150111', 'EL AGUSTINO', $limaMetro);
        $this->createDistrict('150112', 'INDEPENDENCIA', $limaMetro);
        $this->createDistrict('150113', 'JESUS MARIA', $limaMetro);
        $this->createDistrict('150114', 'LA MOLINA', $limaMetro);
        $this->createDistrict('150115', 'LA VICTORIA', $limaMetro);
        $this->createDistrict('150116', 'LINCE', $limaMetro);
        $this->createDistrict('150117', 'LOS OLIVOS', $limaMetro);
        $this->createDistrict('150118', 'LURIGANCHO', $limaMetro);
        $this->createDistrict('150119', 'LURIN', $limaMetro);
        $this->createDistrict('150120', 'MAGDALENA DEL MAR', $limaMetro);
        $this->createDistrict('150121', 'PUEBLO LIBRE', $limaMetro);
        $this->createDistrict('150122', 'MIRAFLORES', $limaMetro);
        $this->createDistrict('150123', 'PACHACAMAC', $limaMetro);
        $this->createDistrict('150124', 'PUCUSANA', $limaMetro);
        $this->createDistrict('150125', 'PUENTE PIEDRA', $limaMetro);
        $this->createDistrict('150126', 'PUNTA HERMOSA', $limaMetro);
        $this->createDistrict('150127', 'PUNTA NEGRA', $limaMetro);
        $this->createDistrict('150128', 'RIMAC', $limaMetro);
        $this->createDistrict('150129', 'SAN BARTOLO', $limaMetro);
        $this->createDistrict('150130', 'SAN BORJA', $limaMetro);
        $this->createDistrict('150131', 'SAN ISIDRO', $limaMetro);
        $this->createDistrict('150132', 'SAN JUAN DE LURIGANCHO', $limaMetro);
        $this->createDistrict('150133', 'SAN JUAN DE MIRAFLORES', $limaMetro);
        $this->createDistrict('150134', 'SAN LUIS', $limaMetro);
        $this->createDistrict('150135', 'SAN MARTIN DE PORRES', $limaMetro);
        $this->createDistrict('150136', 'SAN MIGUEL', $limaMetro);
        $this->createDistrict('150137', 'SANTA ANITA', $limaMetro);
        $this->createDistrict('150138', 'SANTA MARIA DEL MAR', $limaMetro);
        $this->createDistrict('150139', 'SANTA ROSA', $limaMetro);
        $this->createDistrict('150140', 'SANTIAGO DE SURCO', $limaMetro);
        $this->createDistrict('150141', 'SURQUILLO', $limaMetro);
        $this->createDistrict('150142', 'VILLA EL SALVADOR', $limaMetro);
        $this->createDistrict('150143', 'VILLA MARIA DEL TRIUNFO', $limaMetro);

        $callao = $this->createProvince('1502', 'CALLAO', $lima);
        $this->createDistrict('150201', 'CALLAO', $callao);
        $this->createDistrict('150202', 'BELLAVISTA', $callao);
        $this->createDistrict('150203', 'CARMEN DE LA LEGUA REYNOSO', $callao);
        $this->createDistrict('150204', 'LA PERLA', $callao);
        $this->createDistrict('150205', 'LA PUNTA', $callao);
        $this->createDistrict('150206', 'VENTANILLA', $callao);
        $this->createDistrict('150207', 'MI PERU', $callao);

        // Arequipa (04)
        $arequipa = $this->createDepartment('04', 'AREQUIPA');
        
        $arequipaProv = $this->createProvince('0401', 'AREQUIPA', $arequipa);
        $this->createDistrict('040101', 'AREQUIPA', $arequipaProv);
        $this->createDistrict('040102', 'ALTO SELVA ALEGRE', $arequipaProv);
        $this->createDistrict('040103', 'CAYMA', $arequipaProv);
        $this->createDistrict('040104', 'CERRO COLORADO', $arequipaProv);
        $this->createDistrict('040105', 'CHARACATO', $arequipaProv);
        $this->createDistrict('040106', 'CHIGUATA', $arequipaProv);
        $this->createDistrict('040107', 'JACOBO HUNTER', $arequipaProv);
        $this->createDistrict('040108', 'LA JOYA', $arequipaProv);
        $this->createDistrict('040109', 'MARIANO MELGAR', $arequipaProv);
        $this->createDistrict('040110', 'MIRAFLORES', $arequipaProv);
        $this->createDistrict('040111', 'MOLLEBAYA', $arequipaProv);
        $this->createDistrict('040112', 'PAUCARPATA', $arequipaProv);
        $this->createDistrict('040113', 'POCSI', $arequipaProv);
        $this->createDistrict('040114', 'POLOBAYA', $arequipaProv);
        $this->createDistrict('040115', 'QUEQUEÑA', $arequipaProv);
        $this->createDistrict('040116', 'SABANDIA', $arequipaProv);
        $this->createDistrict('040117', 'SACHACA', $arequipaProv);
        $this->createDistrict('040118', 'SAN JUAN DE SIGUAS', $arequipaProv);
        $this->createDistrict('040119', 'SAN JUAN DE TARUCANI', $arequipaProv);
        $this->createDistrict('040120', 'SANTA ISABEL DE SIGUAS', $arequipaProv);
        $this->createDistrict('040121', 'SANTA RITA DE SIGUAS', $arequipaProv);
        $this->createDistrict('040122', 'SOCABAYA', $arequipaProv);
        $this->createDistrict('040123', 'TIABAYA', $arequipaProv);
        $this->createDistrict('040124', 'UCHUMAYO', $arequipaProv);
        $this->createDistrict('040125', 'VITOR', $arequipaProv);
        $this->createDistrict('040126', 'YANAHUARA', $arequipaProv);
        $this->createDistrict('040127', 'YARABAMBA', $arequipaProv);
        $this->createDistrict('040128', 'YURA', $arequipaProv);
        $this->createDistrict('040129', 'JOSE LUIS BUSTAMANTE Y RIVERO', $arequipaProv);

        // Cusco (08)
        $cusco = $this->createDepartment('08', 'CUSCO');
        
        $cuscoProv = $this->createProvince('0801', 'CUSCO', $cusco);
        $this->createDistrict('080101', 'CUSCO', $cuscoProv);
        $this->createDistrict('080102', 'CCORCA', $cuscoProv);
        $this->createDistrict('080103', 'POROY', $cuscoProv);
        $this->createDistrict('080104', 'SAN JERONIMO', $cuscoProv);
        $this->createDistrict('080105', 'SAN SEBASTIAN', $cuscoProv);
        $this->createDistrict('080106', 'SANTIAGO', $cuscoProv);
        $this->createDistrict('080107', 'SAYLLA', $cuscoProv);
        $this->createDistrict('080108', 'WANCHAQ', $cuscoProv);

        // Agregar más departamentos principales
        $this->createDepartment('01', 'AMAZONAS');
        $this->createDepartment('02', 'ANCASH');
        $this->createDepartment('03', 'APURIMAC');
        $this->createDepartment('05', 'AYACUCHO');
        $this->createDepartment('06', 'CAJAMARCA');
        $this->createDepartment('07', 'CALLAO');
        $this->createDepartment('09', 'HUANCAVELICA');
        $this->createDepartment('10', 'HUANUCO');
        $this->createDepartment('11', 'ICA');
        $this->createDepartment('12', 'JUNIN');
        $this->createDepartment('13', 'LA LIBERTAD');
        $this->createDepartment('14', 'LAMBAYEQUE');
        $this->createDepartment('16', 'LORETO');
        $this->createDepartment('17', 'MADRE DE DIOS');
        $this->createDepartment('18', 'MOQUEGUA');
        $this->createDepartment('19', 'PASCO');
        $this->createDepartment('20', 'PIURA');
        $this->createDepartment('21', 'PUNO');
        $this->createDepartment('22', 'SAN MARTIN');
        $this->createDepartment('23', 'TACNA');
        $this->createDepartment('24', 'TUMBES');
        $this->createDepartment('25', 'UCAYALI');

        $this->command->info('Ubigeos cargados: ' . Ubigeo::count());
    }

    private function createDepartment(string $code, string $name): Ubigeo
    {
        return Ubigeo::create([
            'code' => str_pad($code, 6, '0'),
            'department_code' => $code,
            'province_code' => null,
            'district_code' => null,
            'name' => $name,
            'level' => Ubigeo::LEVEL_DEPARTMENT,
            'parent_id' => null,
            'active' => true,
        ]);
    }

    private function createProvince(string $code, string $name, Ubigeo $department): Ubigeo
    {
        return Ubigeo::create([
            'code' => str_pad($code, 6, '0'),
            'department_code' => $department->department_code,
            'province_code' => $code,
            'district_code' => null,
            'name' => $name,
            'level' => Ubigeo::LEVEL_PROVINCE,
            'parent_id' => $department->id,
            'active' => true,
        ]);
    }

    private function createDistrict(string $code, string $name, Ubigeo $province): Ubigeo
    {
        return Ubigeo::create([
            'code' => $code,
            'department_code' => $province->department_code,
            'province_code' => $province->province_code,
            'district_code' => $code,
            'name' => $name,
            'level' => Ubigeo::LEVEL_DISTRICT,
            'parent_id' => $province->id,
            'active' => true,
        ]);
    }
}
