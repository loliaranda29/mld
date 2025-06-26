# Contribuyendo al Proyecto "Mi Luján Digital"

Gracias por tu interés en contribuir a este proyecto municipal. A continuación, te explicamos cómo colaborar de manera ordenada y eficiente.

## Flujo de trabajo con Git

### Ramas principales

- `main`: Contiene el código ya probado, aprobado y listo para producción.
- `dev`: Rama de integración para los desarrollos en curso.

### Ramas de desarrollo individuales

Cada colaborador debe crear una rama desde `dev`, con el siguiente formato:

```
<tu-nombre>/<descripcion-corta-del-cambio>
```

Ejemplos:

```
loli/configuracion-tramite
camila/vista-funcionario
steve/identidad-digital
```

## Pasos para contribuir

1. **Actualizar `dev` localmente:**

```bash
git checkout dev
git pull origin dev
```

2. **Crear tu rama de trabajo:**

```bash
git checkout -b tu-nombre/feature
```

3. **Realizar los cambios y commitear:**

```bash
git add .
git commit -m "Descripción clara del cambio"
```

4. **Pushear tu rama al repositorio:**

```bash
git push origin tu-nombre/feature
```

5. **Crear un Pull Request (PR):**

Desde GitHub, hacé un PR desde tu rama hacia `dev`.

6. **Revisión y aprobación:**

Alguien del equipo revisará tu código. Si está todo bien, se aprueba y se mergea.

7. **Deploy a `main`:**

Los cambios integrados en `dev` serán mergeados a `main` cuando estén probados y estables.

---

¡Gracias por tu colaboración en este proyecto clave para la comunidad!