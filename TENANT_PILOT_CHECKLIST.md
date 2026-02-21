# Tenant Piloto - Checklist de Ejecucion

Fecha de inicio: 21-Feb-2026
Objetivo: tener el primer tenant cliente operando en beta SUNAT y validado end-to-end.

## Estado

- [x] Fase 1 - Cerrar base tecnica (21-Feb-2026)
- [x] Fase 2 - Crear tenant piloto (22-Feb-2026)
- [ ] Fase 3 - Configurar SUNAT beta (23-Feb-2026)
- [ ] Fase 4 - Prueba emision base (24-Feb-2026)
- [ ] Fase 5 - Pruebas complementarias (25-Feb-2026)
- [ ] Fase 6 - Cierre piloto y Go/No-Go (26-Feb-2026)

## Fase 1 - Cerrar base tecnica (21-Feb-2026)

- [x] Commit/push de fixes de guardado certificado y sesion tenant
- [x] Verificacion de uso consistente de `current_company_id`
- [x] Validacion de sintaxis y rutas criticas SUNAT

## Fase 2 - Crear tenant piloto (22-Feb-2026)

- [x] Crear empresa cliente (tenant)
- [x] Crear sucursal principal del tenant
- [x] Crear usuario admin del tenant y acceso validado
- [x] Crear series base: `F001`, `B001`, `FC01`, `FD01`, `T001`
- [x] Verificar catalogos SUNAT y tipos de nota cargados

## Fase 3 - Configurar SUNAT beta (23-Feb-2026)

- [ ] Cargar `.pfx` del tenant
- [ ] Guardar contrasena de certificado
- [ ] Guardar usuario/clave SOL beta
- [ ] Ejecutar prueba de conexion SUNAT con resultado OK

## Fase 4 - Prueba emision base (24-Feb-2026)

- [ ] Emitir 1 boleta (`03`) de prueba
- [ ] Emitir 1 factura (`01`) de prueba
- [ ] Validar XML firmado en storage
- [ ] Validar CDR en storage
- [ ] Validar `sunat_status = accepted`

## Fase 5 - Pruebas complementarias (25-Feb-2026)

- [ ] Emitir 1 nota de credito (`07`)
- [ ] Emitir 1 nota de debito (`08`)
- [ ] Emitir 1 guia de remision (`09`)
- [ ] Probar Comunicacion de Baja (RA)
- [ ] Probar Resumen Diario (RC)
- [ ] Verificar flujo ticket + consulta de estado

## Fase 6 - Cierre piloto y Go/No-Go (26-Feb-2026)

- [ ] Confirmar reenvio SUNAT por documento
- [ ] Confirmar visor XML/CDR operativo
- [ ] Confirmar logs de trazabilidad por tenant
- [ ] Checklist final firmado por equipo
- [ ] Decision Go/No-Go documentada

## Bitacora de avance

- 21-Feb-2026: Checklist creado.
- 21-Feb-2026: Fase 1 completada. Commit `e2ab09c` en `main` con fix de guardado SUNAT tenant-aware y documentacion.
- 21-Feb-2026: Fase 2 completada via `TenantPilotSeeder`. Tenant creado:
  - company_id: `3`
  - branch_id: `4`
  - admin: `admin@clientepiloto.com`
  - password inicial: `Piloto123!`
  - series: `F001`, `B001`, `FC01`, `FD01`, `T001`
  - datos minimos de prueba: 2 clientes + producto servicio `SERV-001`
