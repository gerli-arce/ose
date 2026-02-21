# Tenancy Roadmap (OSE)

## Decision base

- Modelo recomendado: multi-tenant en una sola instalacion (tenancy logico).
- Arquitectura inicial: BD compartida con aislamiento por `company_id`.
- Excepcion: cliente enterprise con requisito contractual/compliance -> despliegue dedicado.

## Por que este enfoque

- Ya existe base multiempresa en el codigo (`company_id`, `current_company_id`).
- Menor costo operativo que una instancia por cliente.
- Despliegues y mantenimiento mas simples.
- Onboarding mas rapido para nuevos clientes.

## Fase 1 (2-3 semanas)

1. Estandarizar contexto tenant
- Usar solo `current_company_id` como fuente de verdad.
- Eliminar dependencias de `session('company_id')` legacy.

2. Blindaje de datos
- Crear trait `BelongsToCompany` con scope por `company_id`.
- Aplicar policies en controladores, no solo filtros manuales.

3. Jobs y colas tenant-aware
- Pasar siempre `company_id` al job.
- Verificar pertenencia antes de procesar documentos.

4. Archivos por tenant
- Mantener estructura por empresa:
  - `certificates/{companyId}`
  - `edocs/{companyId}/xml`
  - `edocs/{companyId}/cdr`
  - `pdf/{companyId}`

5. Integridad en BD
- Definir unicos compuestos por tenant donde corresponda.
- Ejemplos:
  - `document_series`: (`company_id`, `prefix`)
  - `contacts`: (`company_id`, `tax_id`)
  - `products`: (`company_id`, `sku`)

6. Observabilidad
- Log estructurado con:
  - `company_id`
  - `sales_document_id`
  - `sunat_env`
  - `attempt_type`
  - `response_code`

## Fase 2 (30-50 clientes activos)

1. Operacion asyncrona real
- Migrar de `QUEUE_CONNECTION=sync` a Redis.
- Gestion de workers con Horizon.

2. Scheduler operativo
- Automatizar RC/RA y consultas de ticket.
- Alertas por rechazo/error recurrente.

3. Feature flags por empresa
- Habilitar/deshabilitar modulos por tenant.

## Fase 3 (enterprise)

1. Tenant dedicado opcional
- Misma base de codigo, infraestructura separada por cliente.
- Aplicar solo para clientes con SLA/compliance exigente.

## Regla practica

- Default: tenancy logico en plataforma unica.
- Upgrade: despliegue dedicado solo cuando negocio/compliance lo requiera.

