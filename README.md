# Lista blogs RSS (Blogroll)
Lee un archivo OPML y crea un shortcode para mostrar la lista de blogs con enlaces RSS.
## Instrucciones para Utilizar el Plugin
1. Subir el Archivo OPML Manualmente
- Sube el archivo OPML a la raíz de la instalación de WordPress. Es decir, el archivo debe estar ubicado en:
/ruta/a/tu/wordpress/archivo.opml
- Asegúrate de que el archivo se llame archivo.opml. Si deseas utilizar otro nombre, deberás ajustar el código del plugin para reflejar el nombre y la ruta correctos.
2. Activar el Plugin
- Sube la carpeta lista-blogs-opml-simplificado al directorio wp-content/plugins/ de tu instalación de WordPress.
- Ve al panel de administración de WordPress.
- Navega a Plugins y activa Lista de Blogs OPML Simplificado.
3. Agregar el Shortcode en una Entrada o Página
- Agrega un bloque de tipo "Shortcode".
- Ve a la entrada o página donde deseas mostrar la lista de blogs.
- Agrega un bloque de tipo "Shortcode" o simplemente inserta el shortcode en el editor.
- Inserta el siguiente shortcode:
[lista_blogs_opml font_size="18"]
- Cambia 18 por el tamaño de fuente que desees.
## Seguridad
### Validación y Sanitización
- El plugin verifica si el archivo existe antes de intentar leerlo.
- Utiliza funciones de escape como esc_url() y esc_html() para sanitizar los datos antes de mostrarlos en el front-end.
### Permisos de Archivo
- Asegúrate de que el archivo OPML no contenga información sensible.
- Al estar ubicado en la raíz y ser accesible públicamente, cualquier persona con la URL puede descargar el archivo.
## Rendimiento
### Carga en Tiempo de Ejecución:
- Al procesar el archivo OPML cada vez que se muestra el bloque o shortcode, puede haber un impacto en el rendimiento si el archivo es muy grande.
- Si el archivo no cambia con frecuencia y es muy grande, puedes considerar almacenar el resultado en una opción transitoria o cachear el contenido.
## Resumen Final
El plugin "Lista de Blogs OPML Simplificado" funciona de la siguiente manera:
- El administrador sube manualmente el archivo OPML a la raíz de la instalación de WordPress.
- El plugin lee y procesa el archivo OPML directamente desde la raíz cada vez que se muestra el bloque o shortcode.
- Se muestra la lista de blogs divididos en secciones, incluyendo enlaces a los sitios y sus RSS.
- En la cabecera de la lista, se muestra la palabra "OPML" enlazada al archivo OPML.
- No se utiliza JavaScript personalizado ni se requiere configuración adicional.
