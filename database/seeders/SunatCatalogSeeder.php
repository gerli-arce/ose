<?php

namespace Database\Seeders;

use App\Models\DocumentType;
use App\Models\IdentityDocumentType;
use App\Models\TaxAffectationType;
use App\Models\UnitOfMeasure;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeder para Catálogos SUNAT oficiales - Facturación Electrónica Perú
 * 
 * Catálogos incluidos:
 * - Nº 01: Código de tipo de documento (comprobantes)
 * - Nº 06: Código de tipo de documento de identidad
 * - Nº 07: Código de tipo de afectación del IGV
 * - Nº 03: Código de tipo de unidad de medida comercial
 */
class SunatCatalogSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $this->seedIdentityDocumentTypes();
            $this->seedTaxAffectationTypes();
            $this->seedDocumentTypes();
            $this->seedUnitsOfMeasure();
        });
    }

    /**
     * Catálogo SUNAT Nº 06: Códigos de tipo de documento de identidad
     */
    private function seedIdentityDocumentTypes(): void
    {
        $types = [
            ['code' => '0', 'name' => 'DOC.TRIB.NO.DOM.SIN.RUC', 'abbreviation' => 'OTROS'],
            ['code' => '1', 'name' => 'Documento Nacional de Identidad', 'abbreviation' => 'DNI'],
            ['code' => '4', 'name' => 'Carnet de Extranjería', 'abbreviation' => 'CE'],
            ['code' => '6', 'name' => 'Registro Único de Contribuyente', 'abbreviation' => 'RUC'],
            ['code' => '7', 'name' => 'Pasaporte', 'abbreviation' => 'PAS'],
            ['code' => 'A', 'name' => 'Cédula Diplomática de Identidad', 'abbreviation' => 'CDI'],
            ['code' => 'B', 'name' => 'Doc.Ident.País Residencia - No.D', 'abbreviation' => 'DIP'],
            ['code' => 'C', 'name' => 'Tax Identification Number - TIN - Doc Trib PP.NN', 'abbreviation' => 'TIN'],
            ['code' => 'D', 'name' => 'Identification Number - IN - Doc Trib PP.JJ', 'abbreviation' => 'IN'],
            ['code' => 'E', 'name' => 'TAM - Tarjeta Andina de Migración', 'abbreviation' => 'TAM'],
            ['code' => 'F', 'name' => 'Permiso Temporal de Permanencia - PTP', 'abbreviation' => 'PTP'],
            ['code' => 'G', 'name' => 'Salvoconducto', 'abbreviation' => 'SAL'],
        ];

        foreach ($types as $type) {
            IdentityDocumentType::updateOrCreate(
                ['code' => $type['code']],
                $type
            );
        }
    }

    /**
     * Catálogo SUNAT Nº 07: Códigos de tipo de afectación del IGV
     */
    private function seedTaxAffectationTypes(): void
    {
        $types = [
            // GRAVADO (Letra S) - Código tributo 1000 (IGV)
            ['code' => '10', 'name' => 'Gravado - Operación Onerosa', 'letter' => 'S', 'tribute_code' => '1000'],
            ['code' => '11', 'name' => 'Gravado - Retiro por premio', 'letter' => 'S', 'tribute_code' => '1000'],
            ['code' => '12', 'name' => 'Gravado - Retiro por donación', 'letter' => 'S', 'tribute_code' => '1000'],
            ['code' => '13', 'name' => 'Gravado - Retiro', 'letter' => 'S', 'tribute_code' => '1000'],
            ['code' => '14', 'name' => 'Gravado - Retiro por publicidad', 'letter' => 'S', 'tribute_code' => '1000'],
            ['code' => '15', 'name' => 'Gravado - Bonificaciones', 'letter' => 'S', 'tribute_code' => '1000'],
            ['code' => '16', 'name' => 'Gravado - Retiro por entrega a trabajadores', 'letter' => 'S', 'tribute_code' => '1000'],
            ['code' => '17', 'name' => 'Gravado - IVAP', 'letter' => 'S', 'tribute_code' => '1016'],
            
            // EXONERADO (Letra E) - Código tributo 9997 (EXO)
            ['code' => '20', 'name' => 'Exonerado - Operación Onerosa', 'letter' => 'E', 'tribute_code' => '9997'],
            ['code' => '21', 'name' => 'Exonerado - Transferencia Gratuita', 'letter' => 'E', 'tribute_code' => '9997'],
            
            // INAFECTO (Letra O) - Código tributo 9998 (INA)
            ['code' => '30', 'name' => 'Inafecto - Operación Onerosa', 'letter' => 'O', 'tribute_code' => '9998'],
            ['code' => '31', 'name' => 'Inafecto - Retiro por Bonificación', 'letter' => 'O', 'tribute_code' => '9998'],
            ['code' => '32', 'name' => 'Inafecto - Retiro', 'letter' => 'O', 'tribute_code' => '9998'],
            ['code' => '33', 'name' => 'Inafecto - Retiro por Muestras Médicas', 'letter' => 'O', 'tribute_code' => '9998'],
            ['code' => '34', 'name' => 'Inafecto - Retiro por Convenio Colectivo', 'letter' => 'O', 'tribute_code' => '9998'],
            ['code' => '35', 'name' => 'Inafecto - Retiro por premio', 'letter' => 'O', 'tribute_code' => '9998'],
            ['code' => '36', 'name' => 'Inafecto - Retiro por publicidad', 'letter' => 'O', 'tribute_code' => '9998'],
            ['code' => '37', 'name' => 'Inafecto - Transferencia Gratuita', 'letter' => 'O', 'tribute_code' => '9998'],
            
            // EXPORTACIÓN (Letra Z) - Código tributo 9995 (EXP)
            ['code' => '40', 'name' => 'Exportación de Bienes o Servicios', 'letter' => 'Z', 'tribute_code' => '9995'],
        ];

        foreach ($types as $type) {
            TaxAffectationType::updateOrCreate(
                ['code' => $type['code']],
                $type
            );
        }
    }

    /**
     * Catálogo SUNAT Nº 01: Códigos de tipo de documento
     * Actualiza los registros existentes con información SUNAT completa
     */
    private function seedDocumentTypes(): void
    {
        $types = [
            // Documentos de Venta
            ['code' => '01', 'sunat_code' => '01', 'name' => 'Factura Electrónica', 'category' => 'sales', 'affects_stock' => true, 'requires_customer_ruc' => true, 'is_electronic' => true],
            ['code' => '03', 'sunat_code' => '03', 'name' => 'Boleta de Venta Electrónica', 'category' => 'sales', 'affects_stock' => true, 'requires_customer_ruc' => false, 'is_electronic' => true],
            
            // Notas
            ['code' => '07', 'sunat_code' => '07', 'name' => 'Nota de Crédito Electrónica', 'category' => 'note', 'affects_stock' => true, 'requires_customer_ruc' => false, 'is_electronic' => true],
            ['code' => '08', 'sunat_code' => '08', 'name' => 'Nota de Débito Electrónica', 'category' => 'note', 'affects_stock' => false, 'requires_customer_ruc' => false, 'is_electronic' => true],
            
            // Guías
            ['code' => '09', 'sunat_code' => '09', 'name' => 'Guía de Remisión Remitente', 'category' => 'guide', 'affects_stock' => false, 'requires_customer_ruc' => false, 'is_electronic' => true],
            ['code' => '31', 'sunat_code' => '31', 'name' => 'Guía de Remisión Transportista', 'category' => 'guide', 'affects_stock' => false, 'requires_customer_ruc' => false, 'is_electronic' => true],
            
            // Otros documentos electrónicos
            ['code' => '20', 'sunat_code' => '20', 'name' => 'Comprobante de Retención Electrónica', 'category' => 'retention', 'affects_stock' => false, 'requires_customer_ruc' => true, 'is_electronic' => true],
            ['code' => '40', 'sunat_code' => '40', 'name' => 'Comprobante de Percepción Electrónica', 'category' => 'perception', 'affects_stock' => false, 'requires_customer_ruc' => true, 'is_electronic' => true],
            
            // Documentos de compra (no electrónicos, para registro)
            ['code' => '00', 'sunat_code' => '00', 'name' => 'Otros', 'category' => 'other', 'affects_stock' => false, 'requires_customer_ruc' => false, 'is_electronic' => false],
            ['code' => '02', 'sunat_code' => '02', 'name' => 'Recibo por Honorarios', 'category' => 'purchase', 'affects_stock' => false, 'requires_customer_ruc' => false, 'is_electronic' => false],
            ['code' => '14', 'sunat_code' => '14', 'name' => 'Recibo Servicios Públicos', 'category' => 'purchase', 'affects_stock' => false, 'requires_customer_ruc' => false, 'is_electronic' => false],
        ];

        foreach ($types as $type) {
            DocumentType::updateOrCreate(
                ['code' => $type['code']],
                $type
            );
        }
    }

    /**
     * Catálogo SUNAT Nº 03: Código de tipo de unidad de medida comercial
     * Basado en UN/ECE Recommendation 20
     */
    private function seedUnitsOfMeasure(): void
    {
        $units = [
            // Unidades básicas
            ['code' => 'NIU', 'sunat_code' => 'NIU', 'name' => 'Unidad (Bienes)', 'symbol' => 'Und'],
            ['code' => 'ZZ', 'sunat_code' => 'ZZ', 'name' => 'Unidad (Servicios)', 'symbol' => 'Srv'],
            ['code' => 'C62', 'sunat_code' => 'C62', 'name' => 'Unidad', 'symbol' => 'Und'],
            
            // Peso
            ['code' => 'KGM', 'sunat_code' => 'KGM', 'name' => 'Kilogramo', 'symbol' => 'kg'],
            ['code' => 'GRM', 'sunat_code' => 'GRM', 'name' => 'Gramo', 'symbol' => 'g'],
            ['code' => 'TNE', 'sunat_code' => 'TNE', 'name' => 'Tonelada', 'symbol' => 'Tn'],
            ['code' => 'LBR', 'sunat_code' => 'LBR', 'name' => 'Libra', 'symbol' => 'lb'],
            ['code' => 'ONZ', 'sunat_code' => 'ONZ', 'name' => 'Onza', 'symbol' => 'oz'],
            
            // Longitud
            ['code' => 'MTR', 'sunat_code' => 'MTR', 'name' => 'Metro', 'symbol' => 'm'],
            ['code' => 'CMT', 'sunat_code' => 'CMT', 'name' => 'Centímetro', 'symbol' => 'cm'],
            ['code' => 'MMT', 'sunat_code' => 'MMT', 'name' => 'Milímetro', 'symbol' => 'mm'],
            ['code' => 'KMT', 'sunat_code' => 'KMT', 'name' => 'Kilómetro', 'symbol' => 'km'],
            ['code' => 'INH', 'sunat_code' => 'INH', 'name' => 'Pulgada', 'symbol' => 'in'],
            ['code' => 'FOT', 'sunat_code' => 'FOT', 'name' => 'Pie', 'symbol' => 'ft'],
            ['code' => 'YRD', 'sunat_code' => 'YRD', 'name' => 'Yarda', 'symbol' => 'yd'],
            
            // Volumen líquido
            ['code' => 'LTR', 'sunat_code' => 'LTR', 'name' => 'Litro', 'symbol' => 'L'],
            ['code' => 'MLT', 'sunat_code' => 'MLT', 'name' => 'Mililitro', 'symbol' => 'mL'],
            ['code' => 'GLL', 'sunat_code' => 'GLL', 'name' => 'Galón', 'symbol' => 'gal'],
            
            // Volumen sólido
            ['code' => 'MTQ', 'sunat_code' => 'MTQ', 'name' => 'Metro cúbico', 'symbol' => 'm³'],
            
            // Área
            ['code' => 'MTK', 'sunat_code' => 'MTK', 'name' => 'Metro cuadrado', 'symbol' => 'm²'],
            
            // Tiempo
            ['code' => 'HUR', 'sunat_code' => 'HUR', 'name' => 'Hora', 'symbol' => 'h'],
            ['code' => 'DAY', 'sunat_code' => 'DAY', 'name' => 'Día', 'symbol' => 'día'],
            ['code' => 'MON', 'sunat_code' => 'MON', 'name' => 'Mes', 'symbol' => 'mes'],
            ['code' => 'ANN', 'sunat_code' => 'ANN', 'name' => 'Año', 'symbol' => 'año'],
            
            // Empaque
            ['code' => 'BX', 'sunat_code' => 'BX', 'name' => 'Caja', 'symbol' => 'Cja'],
            ['code' => 'PK', 'sunat_code' => 'PK', 'name' => 'Paquete', 'symbol' => 'Paq'],
            ['code' => 'SET', 'sunat_code' => 'SET', 'name' => 'Juego/Set', 'symbol' => 'Set'],
            ['code' => 'DZN', 'sunat_code' => 'DZN', 'name' => 'Docena', 'symbol' => 'Doc'],
            ['code' => 'CEN', 'sunat_code' => 'CEN', 'name' => 'Ciento', 'symbol' => 'Cto'],
            ['code' => 'MIL', 'sunat_code' => 'MIL', 'name' => 'Millar', 'symbol' => 'Mil'],
            ['code' => 'BO', 'sunat_code' => 'BO', 'name' => 'Botella', 'symbol' => 'Bot'],
            ['code' => 'PR', 'sunat_code' => 'PR', 'name' => 'Par', 'symbol' => 'Par'],
            ['code' => 'ROL', 'sunat_code' => 'ROL', 'name' => 'Rollo', 'symbol' => 'Rol'],
            ['code' => 'PLT', 'sunat_code' => 'PLT', 'name' => 'Paleta', 'symbol' => 'Plt'],
            ['code' => 'BAG', 'sunat_code' => 'BAG', 'name' => 'Bolsa', 'symbol' => 'Bls'],
            
            // Otros
            ['code' => 'ST', 'sunat_code' => 'ST', 'name' => 'Pliego', 'symbol' => 'Plg'],
            ['code' => 'GLI', 'sunat_code' => 'GLI', 'name' => 'Gramo de oro', 'symbol' => 'g Au'],
        ];

        foreach ($units as $unit) {
            UnitOfMeasure::updateOrCreate(
                ['code' => $unit['code']],
                $unit
            );
        }
    }
}
